<?php

namespace App\Http\Controllers\Admin;

use App\model\CustomerInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductCatalogCustomerController extends Controller
{
    public function index()
    {
        return view('admin.product_catalog.customer_index');
    }

    public function getCustomerInfoAll()
    {
        $customerInfoAll = CustomerInfo::orderBy('pozicija')->paginate(20);

        return response()->json($customerInfoAll);
    }

    public function addCustomerInfoPost(Request $request)
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


        $maxPosition = CustomerInfo::max('pozicija');

        if(!$maxPosition)
            $maxPosition = 0;
        $request['pozicija'] = $maxPosition + 1;

        $customerInfo = CustomerInfo::create($request->all());

        if(!$customerInfo)
            return response()->json(["success"=>false, "message"=>"customer is not created"]);

        return response()->json(["success"=>true, "data"=>$customerInfo]);
    }

    public function editCustomerInfo(Request $request)
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

        $customerInfo = CustomerInfo::find($request->id);

        $customerInfoUpdated = $customerInfo->update($request->all());


        if(!$customerInfoUpdated)
            return response()->json(["success"=>false, "message"=>"customer is not updated"]);

        return response()->json(["success"=>true, "data"=>$customerInfoUpdated]);
    }

    public function getCustomerInfo($id)
    {
        $customerInfo = CustomerInfo::find($id);
        return response()->json($customerInfo);
    }

    public function customerInfoUp($id)
    {
        $customerInfo = CustomerInfo::find($id);
        $oldPosition = $customerInfo->pozicija;

        $belowCustomerInfo  = CustomerInfo::where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowCustomerInfo->pozicija;

        $customerInfo->pozicija = $newPosition;
        $customerInfo->save();

        $belowCustomerInfo->pozicija = $oldPosition;
        $belowCustomerInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function customerInfoDown($id)
    {
        $customerInfo = CustomerInfo::find($id);
        $oldPosition = $customerInfo->pozicija;

        $belowCustomerInfo = CustomerInfo::where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowCustomerInfo->pozicija;

        $customerInfo->pozicija = $newPosition;
        $customerInfo->save();

        $belowCustomerInfo->pozicija = $oldPosition;
        $belowCustomerInfo->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function deleteCustomerInfo($id)
    {
        $customerInfo = CustomerInfo::find($id);
        $customerInfo->delete();
    }
}
