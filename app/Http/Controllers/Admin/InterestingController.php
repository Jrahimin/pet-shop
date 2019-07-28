<?php

namespace App\Http\Controllers\Admin;

use App\Model\Interesting;
use App\Model\SeoSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Libraries\Functions;

class InterestingController extends Controller
{
    public function index()
    {
        return view('admin.interesting.interesting_index');
    }

    public function getInterestings()
    {
        $interestings = Interesting::orderBy('data', 'DESC')->paginate(20);
        foreach ($interestings as $interesting)
        {
            $interesting->data = gmdate('Y-m-d',$interesting->data);
        }
        $baseDir = asset('storage/images').'/idomu/';

        return response()->json(["interestings"=>$interestings, "base_dir"=>$baseDir]);
    }

    public function addInterestingPost(Request $request)
    {
        foreach ($request->all() as $requestKey=>$requestValue)
        {
            if($requestValue=='undefined' || $requestValue=='null' || $requestValue==''){
                $request[$requestKey] = null;
            }
        }

        $rules = [
            'image'=>'required|max:5120',
            'title'=>'required',
            'autorius'=>'required',
            'data'=>'required',
            'shortdesc'=>'required',
            'description'=>'required',
            'active'=>'required',

            'meta_key'=>'required',
            'meta_desc'=>'required',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $function = new Functions();

        $count = 0;
        $url = $function->url_translator($request->title);
        $prevIdomus = Interesting::where('url',$url)->get();

        if(!$prevIdomus->isEmpty())
            $count = $prevIdomus->count();

        $url = $url."_$count";
        $request['url'] = $url;

        $maxPosition = DB::table('idomu')->max('pozicija');
        if(!$maxPosition)
            $maxPosition = 0;
        $request['pozicija'] = $maxPosition + 1;

        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $newFileName=time()."_".str_random(4)."_".$fileName;
        $path = public_path()."/storage/images/idomu/";

        $request['img'] = $newFileName;

        list($y, $m, $d) = explode("-", $request['data']);
        $data = mktime(0, 0, 0, $m, $d, $y);

        $request['data'] = $data;

        $slider1 = Image::make($file)->resize(80,80)->save($path."s1_".$newFileName);
        $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
        $slider3 = Image::make($file)->resize(390,390)->save($path."s3_".$newFileName);
        $slider4 = Image::make($file)->resize(170,170)->save($path."s4_".$newFileName);

        $interesting = Interesting::create($request->all());

        $function->saveSeo('idomu',$interesting->id,'lt',$request->meta_key,$request->meta_desc);

        if(!$interesting)
            return response()->json(["success"=>false, "message"=>"interesting is not created"]);

        return response()->json(["success"=>true, "data"=>$interesting]);
    }

    public function getInteresting($id)
    {
        $interesting = Interesting::find($id);
        $seoSetting = SeoSettings::where('lenta', 'idomu')->where('id', $id)->first();

        $interesting->data = gmdate('Y-m-d', $interesting->data);
        $interesting->showImage = url('/')."/storage/images/idomu/s1_".$interesting->img;
        $interesting->meta_key =  $seoSetting ? $seoSetting->meta_key : "";
        $interesting->meta_desc = $seoSetting ? $seoSetting->meta_desc : "";
        return response()->json($interesting);
    }

    public function editInteresting(Request $request)
    {
        foreach ($request->all() as $requestKey=>$requestValue)
        {
            if($requestValue=='undefined' || $requestValue=='null' || $requestValue==''){
                $request[$requestKey] = null;
            }
        }

        $rules = [
            'title'=>'required',
            'autorius'=>'required',
            'data'=>'required',
            'shortdesc'=>'required',
            'description'=>'required',
            'active'=>'required',
        ];

        if($request->remove){
            $rules['image'] = 'required';
        }
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $function = new Functions();

        $count = 0;
        $url = $function->url_translator($request->title);
        $prevIdomus = Interesting::where('url',$url)->get();

        if(!$prevIdomus->isEmpty())
            $count = $prevIdomus->count();

        $url = $url."_$count";
        $request['url'] = $url;

        $interesting = Interesting::find($request->id);

        if($request->remove)
        {
            $request['remove'] = 1;

            $path=public_path()."/storage/images/idomu/";
            File::delete($path."s1_".$interesting->img);
            File::delete($path."s2_".$interesting->img);
            File::delete($path."s3_".$interesting->img);
            File::delete($path."s4_".$interesting->img);

            $request['img'] = "";
        }
        else
            $request['remove'] = 0;

        if($request->hasFile('image'))
        {
            //save photo for the directory
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newFileName=time()."_".str_random(4)."_".$fileName;
            $path = public_path()."/storage/images/idomu/";
            $request['img'] = $newFileName;

            $slider1 = Image::make($file)->resize(80,80)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
            $slider3 = Image::make($file)->resize(390,390)->save($path."s3_".$newFileName);
            $slider4 = Image::make($file)->resize(170,170)->save($path."s4_".$newFileName);
        }

        list($y, $m, $d) = explode("-", $request['data']);
        $data = mktime(0, 0, 0, $m, $d, $y);
        $request['data'] = $data;

        $interestingUpdated = $interesting->update($request->all());

        $function->saveSeo('idomu',$interesting->id,'lt',$request->meta_key,$request->meta_desc);

        if(!$interestingUpdated)
            return response()->json(["success"=>false, "message"=>"interesting is not updated"],200);

        return response()->json(["success"=>true, "data"=>$interestingUpdated],200);
    }

    public function deleteInteresting($id)
    {
        $interesting = Interesting::find($id);

        $path=public_path()."/storage/images/idomu/";

        File::delete($path."s1_".$interesting->img);
        File::delete($path."s2_".$interesting->img);
        File::delete($path."s3_".$interesting->img);
        File::delete($path."s4_".$interesting->img);

        $interesting->delete();
    }
}
