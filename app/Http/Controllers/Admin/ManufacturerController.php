<?php

namespace App\Http\Controllers\Admin;

use App\Model\Manufacturer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ManufacturerController extends Controller
{
    public function index()
    {
        return view('admin.manufacturer.index');
    }

    public function getManufacturers()
    {
        $manufacturers=Manufacturer::orderBy('pozicija','asc')->get();

        foreach ($manufacturers as $manufacturer)
        {
            $manufacturer->img=url('/')."/storage/images/gamintojai/s3_".$manufacturer->img;
            $manufacturer->img2=url('/')."/storage/images/gamintojai/s3_".$manufacturer->img2;
        }

        return response()->json($manufacturers);
    }

    public function saveManufacturer(Request $request)
    {
        $request['file1'] = $request->file1 == "undefined" ? null : $request->file1 ;
        $request['file2'] = $request->file2 == "undefined" ? null : $request->file2 ;

        $rules = [
            'active'=>'required',
            'title'=>'required',
            'description'=>'required',
            'prod'=>'required',
            'file1'=>'required',
            'file2'=>'required'
        ];

        $messages = [
            'active.required'=>'You must specify whether the banner is active or not',
            'title.required'=>'You must provide a title',
            'description.required'=>'You must provide description',
            'prod.required'=>'You must specify the type of products',
            'file1.required'=>'You must upload photo for logo in directory',
            'file2.required'=>'You must upload photo for showing in homepage'
        ];
        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        //save logo for the directory
        $file = $request->file('file1');
        $fileName = $file->getClientOriginalName();
        $extention = $file->getClientOriginalExtension();
        $newFileName=str_random(4)."_".$this->encodeFileName($fileName,12,$extention);
        $request['img']=$newFileName;
        $path = public_path()."/storage/images/gamintojai/";

        $slider1 = Image::make($file)->resize(325,205)->save($path."s1_".$newFileName);
        $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
        $slider3 = Image::make($file)->resize(150,70)->save($path."s3_".$newFileName);

      //save logo for home page
        $file2 = $request->file('file2');
        $fileName2 = $file2->getClientOriginalName();
        $extention2 = $file2->getClientOriginalExtension();
        $newFileName2=str_random(5)."_".$this->encodeFileName($fileName2,12,$extention2);
        $request['img2']=$newFileName2;
        $path2 = public_path()."/storage/images/gamintojai/";

        $slider1 = Image::make($file2)->resize(325,205)->save($path2."s1_".$newFileName2);
        $slider2 = Image::make($file2)->resize(800,600)->save($path2."s2_".$newFileName2);
        $slider3 = Image::make($file2)->resize(150,70)->save($path2."s3_".$newFileName2);




        if($request->prod==1)
        {
            $request['prod1']=1;
            $request['prod2']=0;
            $request['prod3']=0;
        }
        elseif($request->prod==2)
        {
            $request['prod1']=0;
            $request['prod2']=1;
            $request['prod3']=0;
        }
        elseif($request->prod==3)
        {
            $request['prod1']=0;
            $request['prod2']=0;
            $request['prod3']=1;
        }
       $request['url']=mb_strtolower($request->title);
        $maxPoz=Manufacturer::max('pozicija');
        if(empty($maxPoz))
            $maxPoz=0;
        $request['pozicija']=$maxPoz+1;
        $manufacturer=Manufacturer::create($request->all());

        return response()->json($manufacturer);

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


    public function deleteManufacturers($id)
    {
        $manufacturer=Manufacturer::find($id);
        $path=public_path()."/storage/images/gamintojai/";
        File::delete($path."s1_".$manufacturer->img);
        File::delete($path."s2_".$manufacturer->img);
        File::delete($path."s3_".$manufacturer->img);
        File::delete($path."s1_".$manufacturer->img2);
        File::delete($path."s2_".$manufacturer->img2);
        File::delete($path."s3_".$manufacturer->img2);

        $manufacturer->delete();
    }
    public function getManufacturer($id)
    {
        $manufacturer=Manufacturer::find($id);
        $manufacturer->showimg=url('/')."/storage/images/gamintojai/s3_".$manufacturer->img;
        $manufacturer->showimg2=url('/')."/storage/images/gamintojai/s3_".$manufacturer->img2;
        return response()->json($manufacturer);
    }

    public function editManufacturer(Request $request)
    {
        $request['file1'] = $request->file1 == "undefined" ? null : $request->file1 ;
        $request['file2'] = $request->file2 == "undefined" ? null : $request->file2 ;

        $rules = [
            'active'=>'required',
            'title'=>'required',
            'description'=>'required',
            'prod'=>'required',
            'remove1'=>'required',
            'remove2'=>'required'
        ];

        if($request->remove1 == 1)
        {
            $rules['file1'] = 'required';
        }
        if($request->remove2 == 1)
        {
            $rules['file2'] = 'required';
        }

        $messages = [
            'active.required'=>'You must specify whether the banner is active or not',
            'title.required'=>'You must provide a title',
            'description.required'=>'You must provide description',
            'prod.required'=>'You must specify the type of products',
            'file1.required'=>'You must upload photo for logo in directory',
            'file2.required'=>'You must upload photo for showing in homepage'
        ];

        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }
        $manufacturer=Manufacturer::find($request->id);

        if($request->hasFile('file1'))
        {
            //save logo for the directory
            $file = $request->file('file1');
            $fileName = $file->getClientOriginalName();
            $extention = $file->getClientOriginalExtension();
            $newFileName=str_random(4)."_".$this->encodeFileName($fileName,12,$extention);
            $request['img']=$newFileName;
            $path = public_path()."/storage/images/gamintojai/";

            $slider1 = Image::make($file)->resize(325,205)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
            $slider3 = Image::make($file)->resize(150,70)->save($path."s3_".$newFileName);
        }

        if($request->hasFile('file2'))
        {
            //save logo for home page
            $file2 = $request->file('file2');
            $fileName2 = $file2->getClientOriginalName();
            $extention2 = $file2->getClientOriginalExtension();
            $newFileName2=str_random(5)."_".$this->encodeFileName($fileName2,12,$extention2);
            $request['img2']=$newFileName2;
            $path2 = public_path()."/storage/images/gamintojai/";

            $slider1 = Image::make($file2)->resize(325,205)->save($path2."s1_".$newFileName2);
            $slider2 = Image::make($file2)->resize(800,600)->save($path2."s2_".$newFileName2);
            $slider3 = Image::make($file2)->resize(150,70)->save($path2."s3_".$newFileName2);
        }

        if($request->remove1)
        {
            $path=public_path()."/storage/images/gamintojai/";
            File::delete($path."s1_".$manufacturer->img);
            File::delete($path."s2_".$manufacturer->img);
            File::delete($path."s3_".$manufacturer->img);
        }
        if($request->remove2)
        {
            $path=public_path()."/storage/images/gamintojai/";
            File::delete($path."s1_".$manufacturer->img2);
            File::delete($path."s2_".$manufacturer->img2);
            File::delete($path."s3_".$manufacturer->img2);
        }

        if($request->prod==1)
        {
            $request['prod1']=1;
            $request['prod2']=0;
            $request['prod3']=0;
        }
        elseif($request->prod==2)
        {
            $request['prod1']=0;
            $request['prod2']=1;
            $request['prod3']=0;
        }
        elseif($request->prod==3)
        {
            $request['prod1']=0;
            $request['prod2']=0;
            $request['prod3']=1;
        }
        $request['url']=mb_strtolower($request->title);
        $manufacturer->update($request->all());

    }

    public function moveUpManufacturer($id)
    {
        $manufacturer=Manufacturer::find($id);
        $nextManufacturer=Manufacturer::where('pozicija','<',$manufacturer->pozicija)->orderBy('pozicija','desc')->limit(1)->first();
        if(!empty($nextManufacturer))
        {
            $pozOld=$manufacturer->pozicija;
            $manufacturer->pozicija=$nextManufacturer->pozicija;
            $nextManufacturer->pozicija=$pozOld;
            $manufacturer->save();
            $nextManufacturer->save();
        }

    }

    public function moveDownManufacturer($id)
    {
        $manufacturer=Manufacturer::find($id);
        $previousManufacturer=Manufacturer::where('pozicija','>',$manufacturer->pozicija)->orderBy('pozicija','asc')->limit(1)->first();
        // return response()->json($previousManufacturer);
        if(!empty($previousManufacturer))
        {
            $pozOld=$manufacturer->pozicija;
            $manufacturer->pozicija=$previousManufacturer->pozicija;
            $previousManufacturer->pozicija=$pozOld;
            $manufacturer->save();
            $previousManufacturer->save();
        }
    }


}
