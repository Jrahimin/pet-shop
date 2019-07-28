<?php

namespace App\Http\Controllers\Admin;

use App\Model\Advantage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class AdvantagesController extends Controller
{
    public function index()
    {
        return view('admin.whyYzipet.index');
    }

    public function getAdvantages()
    {
        $advantages=DB::table('privalumai')->orderBy('pozicija','asc')->get();
        foreach ($advantages as $advantage)
        {
            $advantage->img=url('/')."/storage/images/privalumai/s1_".$advantage->img;
        }
        return response()->json($advantages);
    }
    public function saveAdvantage( Request $request)
    {
        $request['image'] = $request->image == "undefined" ? null : $request->image ;
        $rules = [
            'image'=>'required',
            'active'=>'required',
            'title'=>'required',
            'description'=>'required',

        ];
        $messages = [
            'image.required'=>'You must upload a photo',
            'active.required'=>'You must specify whether the advantage is active or not',
            'title.required'=>'You must provide a title',
            'description.required'=>'You must provide description ',
        ];
        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }


        //save avatar
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $extention = $file->getClientOriginalExtension();
        $newFileName=time()."_".$this->encodeFileName($fileName,12,$extention);
        $request['img']=$newFileName;

        $path = public_path()."/storage/images/privalumai/";

        $slider1 = Image::make($file)->resize(60,60)->save($path."s1_".$newFileName);
        $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
        $slider3 = Image::make($file)->resize(150,100)->save($path."s3_".$newFileName);

        $max_poz=DB::table('privalumai')->max('pozicija');
        if(empty($max_poz))
            $max_poz=0;
        $request['pozicija']=$max_poz+1;
        $advantage=Advantage::create($request->all());
        return response()->json($advantage);
    }
    function encodeFileName($str, $length=0, $extention) {
        $str = mb_strtolower($str, "utf-8");;
        $fname = substr($str, 0, strlen($str)-strlen($extention));
        $lt=array("ą","č","ę","ė","į","š","ų","ū","ž","A","Č","Ę","Ė","Į","Š","Ų","Ū","Ž");
        $rlt=array("a","c","e","e","i","s","u","u","z","A","C","E","E","I","S","U","U","Z");
        $fname=str_replace($lt,$rlt,$fname);
        $fname = $fname.$extention;
        return $fname;
    }

    public function getAdvantage($id)
    {
        $advantage=Advantage::find($id);
        return response()->json($advantage);
    }
    public function editAdvantage(Request $request)
    {
        $request['image'] = $request->image == "undefined" ? null : $request->image ;

        $rules = [
            'active'=>'required',
            'title'=>'required',
            'description'=>'required',
        ];

        $messages = [
            'active.required'=>'You must specify whether the advantage is active or not',
            'title.required'=>'You must provide a title',
            'description.required'=>'You must provide description ',
        ];

        $validation = Validator::make($request->all(), $rules,$messages);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $advantage=Advantage::find($request->id);

        if($request->hasFile('image'))
        {
            //delete avatar
            $path=public_path()."/storage/images/privalumai/";
            File::delete($path."s1_".$advantage->img);
            File::delete($path."s2_".$advantage->img);
            File::delete($path."s3_".$advantage->img);

            //save avatar
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $extention = $file->getClientOriginalExtension();
            $newFileName=time()."_".$this->encodeFileName($fileName,12,$extention);
            $request['img']=$newFileName;
            $path = public_path()."/storage/images/privalumai/";

            $slider1 = Image::make($file)->resize(60,60)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
            $slider3 = Image::make($file)->resize(150,100)->save($path."s3_".$newFileName);
        }
        else{
            $request['img'] = $advantage->img ;
        }


        $advantage=$advantage->update($request->all());
        return response()->json($advantage);
    }

    public function deleteAdvantage($id)
    {
       $advantage=Advantage::find($id);
        //delete avatar
        $path=public_path()."/storage/images/privalumai/";
        File::delete($path."s1_".$advantage->img);
        File::delete($path."s2_".$advantage->img);
        File::delete($path."s3_".$advantage->img);

        $advantage->delete();
    }

    public function moveUp($id)
    {
        $advantage=Advantage::find($id);
        $nextAdvantage=Advantage::where('pozicija','<',$advantage->pozicija)->orderBy('pozicija','desc')->limit(1)->first();
        if(!empty($nextAdvantage))
        {
            $pozOld=$advantage->pozicija;
            $advantage->pozicija=$nextAdvantage->pozicija;
            $nextAdvantage->pozicija=$pozOld;
            $advantage->save();
            $nextAdvantage->save();
        }
    }

    public function moveDown($id)
    {
        $advantage=Advantage::find($id);
        $nextAdvantage=Advantage::where('pozicija','>',$advantage->pozicija)->orderBy('pozicija','asc')->limit(1)->first();
        if(!empty($nextAdvantage))
        {
            $pozOld=$advantage->pozicija;
            $advantage->pozicija=$nextAdvantage->pozicija;
            $nextAdvantage->pozicija=$pozOld;
            $advantage->save();
            $nextAdvantage->save();
        }
    }
}
