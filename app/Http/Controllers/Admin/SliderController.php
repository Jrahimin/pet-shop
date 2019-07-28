<?php

namespace App\Http\Controllers\Admin;

use App\Model\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function index()
    {
        return view('admin.slider.slider_index');
    }

    public function getSliders()
    {
        $sliders = Slider::orderBy('pozicija')->paginate(20);

        $baseDir = asset('storage/images').'/slider/';

        return response()->json(["sliders"=>$sliders, "base_dir"=>$baseDir]);
    }

    public function addSliderPost(Request $request)
    {
        $rules = [
            'image'=>'required|max:5120',
            'title'=>'required',
            'link'=>'required',
            'description'=>'required',
            'active'=>'required',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }


        if($request->active)
            $request['active'] = 1;
        else
            $request['active'] = 0;


        $maxPosition = Slider::max('pozicija');

        if(!$maxPosition)
            $maxPosition = 0;

        $request['pozicija'] = $maxPosition + 1;

        //return response()->json(["success"=>true, "data"=>$request->all()]);

        //generating data for building image
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newFileName = time() . "_" . str_random(4) . "_" . $fileName;
            $path = public_path()."/storage/images/slider/";
            $request['img'] = $newFileName;

            $image1 = Image::make($file)->resize(104, 104)->save($path . "s1_" . $newFileName);
            $image2 = Image::make($file)->resize(800, 800)->save($path . "s2_" . $newFileName);
            $image3 = Image::make($file)->resize(570, 570)->save($path . "s3_" . $newFileName);
        }

        $slider = Slider::create($request->all());


        if(!$slider)
            return response()->json(["success"=>false, "message"=>"contact is not created"]);

        return response()->json(["success"=>true, "data"=>$slider]);
    }

    public function editSlider(Request $request)
    {
        $rules = [
            'image'=>'required|max:5120',
            'title'=>'required',
            'link'=>'required',
            'description'=>'required',
            'active'=>'required',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }


        if($request->active)
            $request['active'] = 1;
        else
            $request['active'] = 0;

        //return response()->json(["success"=>true, "data"=>$request->all()]);

        $slider = Slider::find($request->id);

        //generating data for building image
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newFileName=time()."_".str_random(4)."_".$fileName;
            $path = public_path()."/storage/images/slider/";
            $request['img'] = $newFileName;

            $image1= Image::make($file)->resize(104,104)->save($path."s1_".$newFileName);
            $image2 = Image::make($file)->resize(800,800)->save($path."s2_".$newFileName);
            $image3 = Image::make($file)->resize(570,570)->save($path."s3_".$newFileName);
        }

        if($request->remove_image)
        {
            $path=public_path()."/storage/images/slider/";
            File::delete($path."s1_".$slider->img);
            File::delete($path."s2_".$slider->img);
            File::delete($path."s3_".$slider->img);
        }

        $sliderUpdated = $slider->update($request->all());


        if(!$sliderUpdated)
            return response()->json(["success"=>false, "message"=>"contact is not created"]);

        return response()->json(["success"=>true, "data"=>$sliderUpdated]);
    }

    public function getSlider($id)
    {
        $slider = Slider::find($id);

        $slider->show_image = url('/')."/storage/images/slider/s1_".$slider->img;

        return response()->json($slider);
    }

    public function sliderUp($id)
    {
        $slider = Slider::find($id);
        $oldPosition = $slider->pozicija;

        $belowSlider = Slider::where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowSlider->pozicija;

        $slider->pozicija = $newPosition;
        $slider->save();

        $belowSlider->pozicija = $oldPosition;
        $belowSlider->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function sliderDown($id)
    {
        $slider = Slider::find($id);
        $oldPosition = $slider->pozicija;

        $belowSlider = Slider::where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowSlider->pozicija;

        $slider->pozicija = $newPosition;
        $slider->save();

        $belowSlider->pozicija = $oldPosition;
        $belowSlider->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function deleteSlider($id)
    {
        $slider = Slider::find($id);

        $path=public_path()."/storage/images/slider/";

        File::delete($path."s1_".$slider->img);
        File::delete($path."s2_".$slider->img);
        File::delete($path."s3_".$slider->img);

        $slider->delete();
    }
}
