<?php

namespace App\Http\Controllers\Admin;

use App\Libraries\DateConverter;
use App\Model\Banner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banner.banner_index');
    }
    public function getBanners()
    {
        $banners=Banner::orderBy('pavadinimas')->get();
        foreach ($banners as $banner)
        {
            $banner->data_nuo=$banner->data_nuo>0?date('Y-m-d',$banner->data_nuo):'-';
            $banner->place=$banner->vieta>0?"Perkamiausių prekių viršuje":"Naujų prekių viršuje";
            $banner->act= $banner->aktyvus==1?'Taip':'Ne';

            if ($banner->parodyta>0)
                $banner->effect=round($banner->paspausta/$banner->parodyta*100, 2);
            else  $banner->effect=0;

            if($banner->kriterijus==1)
            {
              $banner->kriterijus="Laikotarpis";
              $banner->limit="iki ".date('Y-m-d',$banner->data_iki);
            }
            elseif ($banner->kriterijus==2)
            {
                $banner->kriterijus="Parodymų skaičius";
                $banner->limit=$banner->parodymai;
            }
            else{
                $banner->kriterijus="Paspaudimų skaičius";
                $banner->limit=$banner->paspaudimai;
            }
        }



        return response()->json($banners);
    }

    public function addBanner()
    {
        return view('admin.banner.add_banner');
    }

    public function storeBanner(Request $request)
    {

       $request['banner'] = $request->banner == "undefined" ? null : $request->banner ;
        $request['parodymai'] = $request->parodymai == "null" ? null : $request->parodymai ;
        $request['paspaudimai'] = $request->paspaudimai == "null" ? null : $request->paspaudimai ;
        $rules = [
            'banner'=>'required',
            'aktyvus'=>'required',
            'vieta'=>'required',
            'link'=>'required',
            'data_iki'=>'required',
            'kriterijus'=>'required',
            'kodas'=>'required',
            'data_nuo'=>'required',
            'pavadinimas'=>'required',

        ];
        if($request->kriterijus==2)
        {
            $rules['parodymai'] ='required|numeric';
        }
        if($request->kriterijus==3)
        {
            $rules['paspaudimai']  ='required|numeric';
        }

        $messages = [
            'banner.required'=>'You must upload a banner photo',
            'aktyvus.required'=>'You must specify whether the banner is active or not',
            'vieta.required'=>'Specify where the banner will be shown',
            'link.required'=>'You must provide a link',
            'data_iki.required'=>'Sepecify the date till the banner is active',
            'kriterijus.required'=>'Specify parameters',
            'kodas.required'=>'Provide a code',
            'data_nuo.required'=>'Specify a date from which the banner will be active',
            'pavadinimas.required'=>'Provide a title',
            'parodymai.required' => 'Parodymai is required',
            'paspaudimai.required'=>'Paspaudimai is required'
        ];

        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $file = $request->file('banner');
        $fileName = $file->getClientOriginalName();
        $extention = $file->getClientOriginalExtension();
        $fileNewName=time()."_".$this->encodeFileName($fileName,12,$extention);
        $path = public_path()."/storage/images/baneriai";
        //moving file to public/image folder with new name
        $file->move($path, $fileNewName);
        $request['img']=$fileNewName;


          list($y, $m, $d) = explode("-", $request['data_nuo']);
         $data_nuo = mktime(0, 0, 0, $m, $d, $y);

        list($y, $m, $d) = explode("-", $request['data_iki']);
        $data_iki = mktime(23, 59, 59, $m, $d, $y);

        $request['data_iki']=$data_iki;
        $request['data_nuo']=$data_nuo;
        $request['clicktag']="null";
        $request['pages']="null";
        if($request->kriterijus==2)
        {
            $request['paspaudimai']= 0;
        }
        if($request->kriterijus==3)
        {
            $request['parodymai']= 0;
        }

        $banner=Banner::create($request->all());
        return response()->json($banner);
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

    public function deleteBanner($id)
    {
        $banner=Banner::find($id);
        File::delete(public_path()."/storage/images/baneriai/".$banner->img);
        $banner->delete();
    }

    public function getBanner($id)
    {
        $banner=Banner::find($id);
        $banner->data_nuo=$banner->data_nuo>0?date('Y-m-d',$banner->data_nuo):'-';
        $banner->data_iki=$banner->data_iki>0?date('Y-m-d',$banner->data_iki):'-';
        return response()->json($banner);
    }
    public function editBanner(Request $request)
    {
       $request['parodymai'] = $request->parodymai == "null" ? null : $request->parodymai ;
       $request['paspaudimai'] = $request->paspaudimai == "null" ? null : $request->paspaudimai ;

        $rules = [
            'aktyvus'=>'required',
            'vieta'=>'required',
            'link'=>'required',
            'data_iki'=>'required',
            'kriterijus'=>'required',
            'kodas'=>'required',
            'data_nuo'=>'required',
            'pavadinimas'=>'required',

        ];
        if($request->kriterijus==2)
        {
            $rules['parodymai'] ='required|numeric';
        }
        if($request->kriterijus==3)
        {
            $rules['paspaudimai']  ='required|numeric';
        }

        $messages = [
            'banner.required'=>'You must upload a banner photo',
            'aktyvus.required'=>'You must specify whether the banner is active or not',
            'vieta.required'=>'Specify where the banner will be shown',
            'link.required'=>'You must provide a link',
            'data_iki.required'=>'Sepecify the date till the banner is active',
            'kriterijus.required'=>'Specify parameters',
            'kodas.required'=>'Provide a code',
            'data_nuo.required'=>'Specify a date from which the banner will be active',
            'pavadinimas.required'=>'Provide a title',
            'parodymai.required' => 'Parodymai is required',
            'paspaudimai.required'=>'Paspaudimai is required'
        ];
        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $banner=Banner::find($request->id);


        if($request->hasFile('banner'))
        {
            File::delete(public_path()."/storage/images/baneriai/".$banner->img);
            $file = $request->file('banner');
            $fileName = $file->getClientOriginalName();
            $extention = $file->getClientOriginalExtension();
            $fileNewName=time()."_".$this->encodeFileName($fileName,12,$extention);
            $path = public_path()."/storage/images/baneriai";
            //moving file to public/image folder with new name
            $file->move($path, $fileNewName);
            $request['img']=$fileNewName;
        }
        else{
            $request['img'] = $banner->img;
        }

        list($y, $m, $d) = explode("-", $request['data_nuo']);
        $data_nuo = mktime(0, 0, 0, $m, $d, $y);

        list($y, $m, $d) = explode("-", $request['data_iki']);
        $data_iki = mktime(23, 59, 59, $m, $d, $y);

        $request['data_iki']=$data_iki;
        $request['data_nuo']=$data_nuo;
        $request['clicktag']=null;
        $request['pages']=null;

        if($request->kriterijus==2)
        {
            $request['paspaudimai']= 0;
        }
        if($request->kriterijus==3)
        {
            $request['parodymai']= 0;
        }

        $banner->update($request->all());
        return response()->json($banner);
    }
}
