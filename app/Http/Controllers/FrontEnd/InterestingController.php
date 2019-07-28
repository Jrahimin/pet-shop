<?php

namespace App\Http\Controllers\FrontEnd;

use App\Model\Interesting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InterestingController extends Controller
{
    public function index()
    {
        return view('frontend.interesting.index');
    }

    public function getInterestings()
    {
        $interestings = Interesting::where('active', 1)->orderBy('data', 'Desc')->get();
        $baseDir = asset('storage/images').'/idomu/s3_';
        foreach ($interestings as $interesting)
        {
            $interesting->data = gmdate('Y-m-d',$interesting->data);
            $interesting->imageDir = $baseDir.$interesting->img;
        }
        return response()->json($interestings);
    }

    public function getInteresting($id)
    {
        $interesting = Interesting::find($id);

        $interesting->data = gmdate('Y-m-d', $interesting->data);
        $interesting->imageDir = url('/')."/storage/images/idomu/s4_".$interesting->img;
        $interesting->gallery = $interesting->galleries()->orderBy('pozicija')->get();

        foreach ($interesting->gallery as $aGallery)
        {
            if($aGallery->img)
                $aGallery->imageDir = url('/')."/storage/images/idomu/s1_".$aGallery->img;
            else
                $aGallery->imageDir = asset('images/yzipet_logo.png');

            if($aGallery->video){
                $embedYoutubeLink = str_replace('watch?v=', 'embed/', $aGallery->video);
                $aGallery->embedVideoLink = $embedYoutubeLink;
            }
        }

        return response()->json($interesting);
    }
}
