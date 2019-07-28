<?php

namespace App\Http\Controllers\Admin;

use App\model\LoyalityInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductCatalogLoyalityController extends Controller
{


    public function index()
    {
        return view('admin.product_catalog.loyality_index');
    }

    public function getLoyalityInfoAll()
    {
        $loyalityInfoAll = LoyalityInfo::orderBy('pozicija')->paginate(20);

        return response()->json($loyalityInfoAll);
    }

    public function addLoyalityInfoPost(Request $request)
    {
        $rules = [
            'title'=>'required',
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


        $maxPosition = LoyalityInfo::max('pozicija');

        if(!$maxPosition)
            $maxPosition = 0;
        $request['pozicija'] = $maxPosition + 1;

        $loyalityInfo = LoyalityInfo::create($request->all());

        if(!$loyalityInfo)
            return response()->json(["success"=>false, "message"=>"loyality is not created"]);

        return response()->json(["success"=>true, "data"=>$loyalityInfo]);
    }

    public function editLoyalityInfo(Request $request)
    {
        $rules = [
            'title'=>'required',
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

        $loyalityInfo = LoyalityInfo::find($request->id);

        $loyalityInfoUpdated = $loyalityInfo->update($request->all());


        if(!$loyalityInfoUpdated)
            return response()->json(["success"=>false, "message"=>"loyality is not created"]);

        return response()->json(["success"=>true, "data"=>$loyalityInfoUpdated]);
    }

    public function getLoyalityInfo($id)
    {
        $loyalityInfo = LoyalityInfo::find($id);
        return response()->json($loyalityInfo);
    }

    public function loyalityInfoUp($id)
    {
        $loyalityInfo = LoyalityInfo::find($id);
        $oldPosition = $loyalityInfo->pozicija;

        $belowLoyalityInfo  = LoyalityInfo::where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowLoyalityInfo->pozicija;

        $loyalityInfo->pozicija = $newPosition;
        $loyalityInfo->save();

        $belowLoyalityInfo->pozicija = $oldPosition;
        $belowLoyalityInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function loyalityInfoDown($id)
    {
        $loyalityInfo = LoyalityInfo::find($id);
        $oldPosition = $loyalityInfo->pozicija;

        $belowLoyalityInfo = LoyalityInfo::where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowLoyalityInfo->pozicija;

        $loyalityInfo->pozicija = $newPosition;
        $loyalityInfo->save();

        $belowLoyalityInfo->pozicija = $oldPosition;
        $belowLoyalityInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function deleteLoyalityInfo($id)
    {
        $loyalityInfo = LoyalityInfo::find($id);
        $loyalityInfo->delete();
    }
}
