<?php

namespace App\Http\Controllers\Admin;

use App\model\DeliveryInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductCatalogDeliveryController extends Controller
{
    public function index()
    {
        return view('admin.product_catalog.delivery_index');
    }

    public function getDeliveryInfoAll()
    {
        $deliveryInfoAll = DeliveryInfo::orderBy('pozicija')->paginate(20);

        return response()->json($deliveryInfoAll);
    }

    public function addDeliveryInfoPost(Request $request)
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


        $maxPosition = DeliveryInfo::max('pozicija');

        if(!$maxPosition)
            $maxPosition = 0;
        $request['pozicija'] = $maxPosition + 1;

        $deliveryInfo = DeliveryInfo::create($request->all());

        if(!$deliveryInfo)
            return response()->json(["success"=>false, "message"=>"delivery is not created"]);

        return response()->json(["success"=>true, "data"=>$deliveryInfo]);
    }

    public function editDeliveryInfo(Request $request)
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

        $deliveryInfo = DeliveryInfo::find($request->id);

        $deliveryInfoUpdated = $deliveryInfo->update($request->all());


        if(!$deliveryInfoUpdated)
            return response()->json(["success"=>false, "message"=>"delivery is not created"]);

        return response()->json(["success"=>true, "data"=>$deliveryInfoUpdated]);
    }

    public function getDeliveryInfo($id)
    {
        $deliveryInfo = DeliveryInfo::find($id);
        return response()->json($deliveryInfo);
    }

    public function deliveryInfoUp($id)
    {
        $deliveryInfo = DeliveryInfo::find($id);
        $oldPosition = $deliveryInfo->pozicija;

        $belowDeliveryInfo  = DeliveryInfo::where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowDeliveryInfo->pozicija;

        $deliveryInfo->pozicija = $newPosition;
        $deliveryInfo->save();

        $belowDeliveryInfo->pozicija = $oldPosition;
        $belowDeliveryInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function deliveryInfoDown($id)
    {
        $deliveryInfo = DeliveryInfo::find($id);
        $oldPosition = $deliveryInfo->pozicija;

        $belowDeliveryInfo = DeliveryInfo::where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowDeliveryInfo->pozicija;

        $deliveryInfo->pozicija = $newPosition;
        $deliveryInfo->save();

        $belowDeliveryInfo->pozicija = $oldPosition;
        $belowDeliveryInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function deleteDeliveryInfo($id)
    {
        $deliveryInfo = DeliveryInfo::find($id);
        $deliveryInfo->delete();
    }
}
