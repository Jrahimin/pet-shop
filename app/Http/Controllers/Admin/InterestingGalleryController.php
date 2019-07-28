<?php

namespace App\Http\Controllers\Admin;

use App\Model\InterestingGallery;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class InterestingGalleryController extends Controller
{
    public function getInterestingGalleries($id)
    {
        $galleries = InterestingGallery::where('skiltis', $id)->orderBy('pozicija')->paginate(20);

        $baseDir = asset('storage/images').'/idomu/';
        return response()->json(["base_dir"=>$baseDir, "galleries"=>$galleries]);
    }

    public function addGallery(Request $request)
    {
        $rules = [
            'aprasymas_lt'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $maxPosition = DB::table('idomu_nuotraukos')->where('skiltis', $request->skiltis)->max('pozicija');
        if(!$maxPosition)
            $maxPosition = 1;

        $request['pozicija'] = $maxPosition + 1;

        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newFileName=time()."_".str_random(4)."_".$fileName;
            $path = public_path()."/storage/images/idomu/";
            $request['img'] = $newFileName;

            $slider1 = Image::make($file)->resize(210,210)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
            $slider3 = Image::make($file)->resize(128,128)->save($path."s3_".$newFileName);
        }

        $interestingGallery = InterestingGallery::create($request->all());

        if(!$interestingGallery)
            return response()->json(["success"=>false, "message"=>"interesting is not created"]);

        return response()->json(["success"=>true, "data"=>$interestingGallery]);
    }

    public function galleryUp($id)
    {
        $image = InterestingGallery::find($id);
        $interestingId = $image->skiltis;
        $oldPosition = $image->pozicija;

        $belowImage = InterestingGallery::where('skiltis', $interestingId)->where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowImage->pozicija;

        $image->pozicija = $newPosition;
        $image->save();

        $belowImage->pozicija = $oldPosition;
        $belowImage->save();
    }

    public function galleryDown($id)
    {
        $image = InterestingGallery::find($id);
        $interestingId = $image->skiltis;
        $oldPosition = $image->pozicija;

        $belowImage = InterestingGallery::where('skiltis', $interestingId)->where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowImage->pozicija;

        $image->pozicija = $newPosition;
        $image->save();

        $belowImage->pozicija = $oldPosition;
        $belowImage->save();
    }

    public function deleteGallery($id)
    {
        $gallery = InterestingGallery::find($id);

        $path=public_path()."/storage/images/idomu/";

        File::delete($path."s1_".$gallery->img);
        File::delete($path."s2_".$gallery->img);
        File::delete($path."s3_".$gallery->img);

        $gallery->delete();
    }
}
