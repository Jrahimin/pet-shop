<?php

namespace App\Http\Controllers\Admin;

use App\Enumerations\SliderEffectType;
use App\Model\SliderImage;
use App\Model\SliderOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class SliderImageController extends Controller
{
    public function index()
    {
        return view('admin.slider_image.index');
    }

    public function getSliderImages()
    {
        $sliderImages = SliderImage::orderBy('pozicija')->get();
        foreach ($sliderImages as $image )
        {
            $image->image = url('/').'/storage/images/s2_'.$image->image;
        }
        return response()->json($sliderImages);
    }
    public function deleteSliderImage($id)
    {
        $sliderImage=SliderImage::find($id);
        $path=public_path()."/storage/images/";
        File::delete($path."s1_".$sliderImage->image);
        File::delete($path."s2_".$sliderImage->image);
        $sliderImage->delete();
    }
    public function saveSliderImage(Request $request)
    {
        foreach ($request->all() as $key=>$value)
        {
            if($value=='null' || $value=='undefined' || $value=='null'){
                $request[$key] = null;
            }
        }

        $rules = [
            'img'=>'required',
            'text'=>'required',
            'active'=>'required',
        ];

        $messages = [
            'img.required'  =>'You must upload an image',
            'text.required'=>'You must provide image title'
        ];
        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $maxPosition = SliderImage::max('pozicija');

        if(!$maxPosition)
            $maxPosition = 0;

        $request['pozicija'] = $maxPosition + 1;

        if($request->hasFile('img'))
        {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $extention = $file->getClientOriginalExtension();
            $newFileName=str_random(5)."_".$this->encodeFileName($fileName,12,$extention);
            $request['image']=$newFileName;
            $path = public_path()."/storage/images/";

            $slider1 = Image::make($file)->resize(800,600)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(150,70)->save($path."s2_".$newFileName);
        }

        $sliderImage=SliderImage::create($request->all());

        return response()->json($sliderImage);
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

    public function getSliderImage($id)
    {
        $sliderImage = SliderImage::find($id);
        if(!empty($sliderImage->image))
            $sliderImage->image = url('/').'/storage/images/s2_'.$sliderImage->image;
        return response()->json($sliderImage) ;
    }

    public function editSliderImage(Request $request)
    {
        foreach ($request->all() as $key=>$value)
        {
            if($value=='null' || $value=='undefined' || $value=='null'){
                $request[$key] = null;
            }
        }

        $rules = [
            'text'=>'required',
            'removeIt' =>'required',
        ];

        $messages = [
            'img.required'=>'You must provide an image',
            'text.required'=>'You must provide image title'
        ];
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

         $sliderImage = SliderImage::find($request->id) ;
        $sliderImage->text = $request->text ;
        $sliderImage->active = $request->active;
        $sliderImage->link = $request->link;
        $sliderImage->parallax = $request->parallax;

        if($request->hasFile('img'))
        {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $extention = $file->getClientOriginalExtension();
            $newFileName=str_random(5)."_".$this->encodeFileName($fileName,12,$extention);

            $path = public_path()."/storage/images/";

            $slider1 = Image::make($file)->resize(800,600)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(150,70)->save($path."s2_".$newFileName);

            $sliderImage->image = $newFileName;
        }

        if($request->removeIt == 1)
        {
            $path=public_path()."/storage/images/";
            File::delete($path."s1_".$sliderImage->image);
            File::delete($path."s2_".$sliderImage->image);
            $sliderImage->image = "";
        }

        $sliderImage->save();
        return response()->json($sliderImage);
    }

    public function sliderUp($id)
    {
        $slider = SliderImage::find($id);
        $oldPosition = $slider->pozicija;

        $belowSlider = SliderImage::where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowSlider->pozicija;

        $slider->pozicija = $newPosition;
        $slider->save();

        $belowSlider->pozicija = $oldPosition;
        $belowSlider->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function sliderDown($id)
    {
        $slider = SliderImage::find($id);
        $oldPosition = $slider->pozicija;

        $belowSlider = SliderImage::where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowSlider->pozicija;

        $slider->pozicija = $newPosition;
        $slider->save();

        $belowSlider->pozicija = $oldPosition;
        $belowSlider->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function getSliderOption()
    {
        $sliderOption = SliderOption::first();
        return response()->json($sliderOption);
    }
    public function addSliderOptions(Request $request)
    {
        //return response()->json($request->all());
        $sliderOption = SliderOption::first();
        $rules = [
            'title'=>'required',
            'height'=>'required|numeric',
        ];

        $numericKeys = ['navigation', 'pagination_type', 'verticle', 'slidesPerView',
            'spaceBetween', 'loop', 'effect_type', 'speed', 'parallax', 'autoplay'];

        $allRequests = $request->all();
        foreach ($allRequests as $key=>$value)
        {
            if(in_array($key, $numericKeys) && !empty($value)){
                $rules[$key] = 'integer';

                if($key == "autoplay" && $value==1){
                    $rules['autoplay_delay'] = 'required|integer';
                }
            }
        }

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        if($request->active === 1){
            $prevActiveOptions = SliderOption::where('active', 1)->update(['active'=> 0]);
        }

        $sliderOptionUpdated = $sliderOption->update($request->all());

        if(!$sliderOptionUpdated)
            return response()->json("data is not updated");

        return response()->json("option updated");
    }
}
