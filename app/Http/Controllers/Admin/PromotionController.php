<?php

namespace App\Http\Controllers\Admin;

use App\Model\Promotion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        return view('admin.product_catalog.promotion');
    }

    public function getPromotionInfoAll()
    {
        $promotionInfoAll = Promotion::orderBy('code')->paginate(20);

        foreach ($promotionInfoAll as $promotion)
        {
            $dateFrom = gmdate('Y-m-d', $promotion->datefrom);
            $dateTill = gmdate('Y-m-d', $promotion->datetill);
            $promotion->validity = $dateFrom." - ".$dateTill;
        }

        return response()->json($promotionInfoAll);
    }

    public function addPromotionInfoPost(Request $request)
    {
        $rules = [
            'title'=>'required',
            'code'=>'required',
            'datefrom'=>'required',
            'datetill'=>'required',
            'all_product'=>'required|numeric'
        ];

        if($request->show_promotion_percent == 1){
            $rules['amount_percent'] = 'required';
            $request['amount_fixed'] = null;
        }
        else{
            $rules['amount_fixed'] = 'required';
            $request['amount_percent'] = null;
        }

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $request->active ? $request['active'] = 1 : $request['active'] = 0;

        //format vue calender date to seconds
        list($y, $m, $d) = explode("-", $request['datefrom']);
        $dateFrom = mktime(0, 0, 0, $m, $d, $y);
        $request['datefrom'] = $dateFrom;

        list($y, $m, $d) = explode("-", $request['datetill']);
        $dateTill = mktime(0, 0, 0, $m, $d, $y);
        $request['datetill'] = $dateTill;

        count($request->manufacturers) > 0 ? $request['manufacturer_check'] = 1 : $request['manufacturer_check'] = 0;
        count($request->categoryList) > 0 ? $request['category_check'] = 1 : $request['category_check'] = 0;

        //return response()->json($request->all());

        $promotionInfo = Promotion::create($request->all());

        if(!$promotionInfo)
            return response()->json(["success"=>false, "message"=>"promotion is not created"]);

        if($request->all_product===1)
        {
            foreach ($request->manufacturers as $manufacturer)
            {
                $promotionInfo->manufacturers()->attach($manufacturer['id']);
            }

            foreach ($request->categoryList as $category)
            {
                $promotionInfo->categories()->attach($category['id']);
            }
        }
        else{
            if($request->all_product===0)
            {
                foreach ($request->product as $product)
                {
                    $promotionInfo->products()->attach($product['id']);
                }
            }
        }

        return response()->json(["success"=>true, "data"=>$request->all()]);
    }

    public function editPromotionInfo(Request $request)
    {
        //return response()->json($request->all());
        $rules = [
            'title'=>'required',
            'code'=>'required',
            'datefrom'=>'required',
            'datetill'=>'required',
            'all_product'=>'required|numeric'
        ];

        if($request->show_promotion_percent == 1){
            $rules['amount_percent'] = 'required';
            $request['amount_fixed'] = null;
        }
        else{
            $rules['amount_fixed'] = 'required';
            $request['amount_percent'] = null;
        }

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $promotionInfo = Promotion::find($request->id);
        $oldAllProduct = $promotionInfo->all_product;

        list($y, $m, $d) = explode("-", $request['datefrom']);
        $dateFrom = mktime(0, 0, 0, $m, $d, $y);
        $request['datefrom'] = $dateFrom;

        list($y, $m, $d) = explode("-", $request['datetill']);
        $dateTill = mktime(0, 0, 0, $m, $d, $y);
        $request['datetill'] = $dateTill;

        count($request->manufacturers) > 0 ? $request['manufacturer_check'] = 1 : $request['manufacturer_check'] = 0;
        count($request->categoryList) > 0 ? $request['category_check'] = 1 : $request['category_check'] = 0;

        $promotionInfoUpdated = $promotionInfo->update($request->all());
        $promotionInfo = Promotion::find($request->id);
        $newAllProduct = $promotionInfo->all_product;

        if(!$promotionInfoUpdated)
            return response()->json(["success"=>false, "message"=>"promotion is not created"]);

        // promotion data being updated in pivot table according to type
        if($request->all_product===1)
        {
            $manufactureIds = [];
            foreach ($request->manufacturers as $manufacturer)
            {
                $manufactureIds[] = $manufacturer['id'];
            }
            $promotionInfo->manufacturers()->sync($manufactureIds);

            $categoryIds = [];
            foreach ($request->categoryList as $category)
            {
                $categoryIds[] = $category['id'];
            }
            $promotionInfo->categories()->sync($categoryIds);
        }
        else{
            if($request->all_product===0)
            {
                $productIds = [];
                foreach ($request->product as $product)
                {
                    $productIds[] = $product['id'];
                }
                $promotionInfo->products()->sync($productIds);
            }
        }

        if($oldAllProduct!==$newAllProduct)
        {
            if($oldAllProduct===1){
                $promotionInfo->manufacturers()->detach();
                $promotionInfo->categories()->detach();
            }
            else{
                $promotionInfo->products()->detach();
            }
        }

        return response()->json(["success"=>true, "data"=>$promotionInfo],200);
    }

    public function getPromotionInfo($id)
    {
        $promotionInfo = Promotion::find($id);

        $promotionInfo->datefrom = gmdate('Y-m-d', $promotionInfo->datefrom);
        $promotionInfo->datetill = gmdate('Y-m-d', $promotionInfo->datetill);

        if($promotionInfo->all_product==1)
        {
            foreach ($promotionInfo->manufacturers as $manufacturer)
            {
                $manufacturer->label = $manufacturer->title;
            }
            $promotionInfo->manufacturers = $promotionInfo->manufacturers;

            foreach ($promotionInfo->categories as $category)
            {
                $category->label = $category->pavadinimas_lt;
            }
            $promotionInfo->categoryList = $promotionInfo->categories;

        }
        else
        {
            foreach ($promotionInfo->products as $product)
            {
                $product->label = $product->pavadinimas_lt;
            }
            $promotionInfo->product = $promotionInfo->products;
        }

        return response()->json($promotionInfo);
    }

    public function deletePromotionInfo($id)
    {
        $promotionInfo = Promotion::find($id);
        $promotionInfo->delete();
    }
}
