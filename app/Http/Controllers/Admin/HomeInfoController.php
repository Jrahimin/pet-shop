<?php

namespace App\Http\Controllers\Admin;

use App\Enumerations\FoodCategory;
use App\Model\HomeInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomeInfoController extends Controller
{
    public function index()
    {
        return view('admin.home-info.home_info_index');
    }

    public function getHomeInfoAll()
    {
        $homeInfoAll = HomeInfo::orderBy('pozicija')->paginate(20);

        foreach ($homeInfoAll as $homeInfo)
        {
            switch ($homeInfo->cat)
            {
                case FoodCategory::$DOG:
                    $homeInfo->category_name = "for dogs";
                    break;

                case FoodCategory::$CAT:
                    $homeInfo->category_name = "for cats";
                    break;

                case FoodCategory::$HORSE:
                    $homeInfo->category_name = "for horses";
                    break;

            }
        }

        return response()->json($homeInfoAll);
    }

    public function addHomeInfoPost(Request $request)
    {
        $rules = [
            'cat'=>'required',
            'title'=>'required',
            'link'=>'required',
            'description'=>'required',
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


        $maxPosition = HomeInfo::max('pozicija');

        if(!$maxPosition)
            $maxPosition = 0;
        $request['pozicija'] = $maxPosition + 1;

        $homeInfo = HomeInfo::create($request->all());

        if(!$homeInfo)
            return response()->json(["success"=>false, "message"=>"contact is not created"]);

        return response()->json(["success"=>true, "data"=>$homeInfo]);
    }

    public function editHomeInfo(Request $request)
    {
        $rules = [
            'cat'=>'required',
            'title'=>'required',
            'link'=>'required',
            'description'=>'required',
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

        $homeInfo = HomeInfo::find($request->id);

        $homeInfoUpdated = $homeInfo->update($request->all());


        if(!$homeInfoUpdated)
            return response()->json(["success"=>false, "message"=>"contact is not created"]);

        return response()->json(["success"=>true, "data"=>$homeInfoUpdated]);
    }

    public function getHomeInfo($id)
    {
        $homeInfo = HomeInfo::find($id);
        return response()->json($homeInfo);
    }

    public function homeInfoUp($id)
    {
        $homeInfo = HomeInfo::find($id);
        $oldPosition = $homeInfo->pozicija;

        $belowHomeInfo  = HomeInfo::where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowHomeInfo->pozicija;

        $homeInfo->pozicija = $newPosition;
        $homeInfo->save();

        $belowHomeInfo->pozicija = $oldPosition;
        $belowHomeInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function homeInfoDown($id)
    {
        $homeInfo = HomeInfo::find($id);
        $oldPosition = $homeInfo->pozicija;

        $belowHomeInfo = HomeInfo::where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowHomeInfo->pozicija;

        $homeInfo->pozicija = $newPosition;
        $homeInfo->save();

        $belowHomeInfo->pozicija = $oldPosition;
        $belowHomeInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function deleteHomeInfo($id)
    {
        $homeInfo = HomeInfo::find($id);
        $homeInfo->delete();
    }
}
