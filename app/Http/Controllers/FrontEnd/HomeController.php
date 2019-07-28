<?php

namespace App\Http\Controllers\FrontEnd;

use App\Enumerations\SliderEffectType;
use App\Model\Advantage;
use App\Model\Contact;
use App\Model\Manufacturer;
use App\Model\Setting;
use App\Model\Slider;
use App\Model\SliderImage;
use App\Model\SliderOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home.index');
    }
    public function indexNew()
    {
        $sliderOption = new SliderOption();
        $sliderOptionObj = $sliderOption->setSliderOption();
        return view('frontend.home.index_new', compact('sliderOptionObj'));
    }

    public function testIndex()
    {
        return view('frontend.home.test_home');
    }

    public function getAdvantages()
    {
        $advantages=DB::table('privalumai')->where('active',1)->orderBy('pozicija','asc')->get();
        foreach ($advantages as $advantage)
        {
            $advantage->img=url('/')."/storage/images/privalumai/s1_".$advantage->img;
        }
        return response()->json($advantages);
    }
    public function getContacts()
    {
        $contacts = Contact::where('active',1)->first();
        return response()->json($contacts);
    }

    public function getHomeData()
    {
        $sliders = Slider::orderBy('pozicija')->get();
        $path = asset('storage/images/slider/s1_');
        foreach ($sliders as $slider)
        {
            $slider->imageSlider = $path.$slider->img;
        }

        $advantages = Advantage::orderBy('pozicija')->get();
        $path2 = asset('storage/images/privalumai/s1_');
        foreach ($advantages as $advantage)
        {
            $advantage->imageAdvantage = $path2.$advantage->img;
        }

        $manufacturers = Manufacturer::orderBy('pozicija')->get();
        $path3 = asset('storage/images/gamintojai/s3_');
        foreach ($manufacturers as $manufacturer)
        {
            $manufacturer->imageManufacturer = $path3.$manufacturer->img;
        }

        $youtubeLink = Setting::where('key', 'tit_youtube')->first()->value;


        $data = array(
            "sliders" => $sliders,
            "youtubeLink" => $youtubeLink,
            "advantages" => $advantages,
            "manufacturers" => $manufacturers
        );

        return response()->json($data);
    }

    public function getSliderForHome()
    {
        $slider = SliderImage::where('active', 1)->orderBy('pozicija')->get();
        foreach ($slider as $image )
        {
            if($image->image!=''){
                $image->image = url('/').'/storage/images/s2_'.$image->image;
            }
        }
        return response()->json($slider);
    }
}
