<?php

namespace App\Http\Controllers\FrontEnd;

use App\model\CustomerInfo;
use App\model\DeliveryInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCatalogController extends Controller
{
    public function deliveryIndex()
    {
        return view('frontend.home.delivery_info');
    }

    public function buyerInfoIndex()
    {
        return view('frontend.home.buyer_info');
    }

    public function filterdProductsIndex($keyword)
    {
        return view('frontend.home.filtered_product', compact('keyword'));
    }

    public function getDeliveryInfo()
    {
        $deliveryInfoList = DeliveryInfo::orderBy('pozicija')->where('active', 1)->get();

        return response()->json($deliveryInfoList);
    }

    public function getCustomerInfo()
    {
        $customerInfoList = CustomerInfo::orderBy('pozicija')->where('active', 1)->get();

        return response()->json($customerInfoList);
    }
}
