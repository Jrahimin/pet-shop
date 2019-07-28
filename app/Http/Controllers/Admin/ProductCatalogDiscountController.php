<?php

namespace App\Http\Controllers\Admin;

use App\Enumerations\DiscountType;
use App\Model\Discount;
use App\Model\Package;
use App\Model\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductCatalogDiscountController extends Controller
{

    public function index()
    {
        return view('admin.product_catalog.discount_index');
    }

    public function getDiscountInfoAll()
    {
        $discountInfoAll = Discount::orderBy('datefrom', 'Desc')->paginate(20);

        foreach ($discountInfoAll as $discount)
        {
            $dateFrom = gmdate('Y-m-d', $discount->datefrom);
            $dateTill = gmdate('Y-m-d', $discount->datetill);
            $discount->validity = $dateFrom." - ".$dateTill;
        }

        return response()->json($discountInfoAll);
    }

    public function addDiscountInfoPost(Request $request)
    {
        //return response()->json($request->all());
        $rules = [
            'title'=>'required',
            'datefrom'=>'required',
            'datetill'=>'required',
        ];

        if($request->show_discount_percent == 1){
            $rules['amount'] = 'required';
            $request['amount_fixed'] = null;
        }
        else{
            $rules['amount_fixed'] = 'required';
            $request['amount'] = null;
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

        $discountInfo = Discount::create($request->all());

        if(!$discountInfo)
            return response()->json(["success"=>false, "message"=>"discount is not created"]);

        if($request->discount_type==DiscountType::$PRODUCT)
        {
            foreach ($request->packages as $productPackages)
            {
                $productId = $productPackages[0]['preke'];
                //$totalProdPacks = Package::where('preke', $productId)->count();
                foreach ($productPackages as $package)
                {
                    $discountInfo->packages()->attach($package['id'], ['product_id'=>$productId]);
                }
            }
        }
        else if($request->discount_type==DiscountType::$MANUFACTURER)
        {
            foreach ($request->manufacturers as $manufacturer)
            {
                $discountInfo->manufacturers()->attach($manufacturer['id']);
            }
        }

        else if($request->discount_type==DiscountType::$CATEGORY)
        {
            foreach ($request->categoryList as $category)
            {
                $discountInfo->categories()->attach($category['id']);
            }
        }

        if($request->for_all_user === 0)
        {
            foreach ($request->users as $user)
            {
                $discountInfo->users()->attach($user['id']);
            }
        }

        return response()->json(["success"=>true, "data"=>$request->all()]);
    }

    public function editDiscountInfo(Request $request)
    {
        //return response()->json($request->all());
        $rules = [
            'title'=>'required',
            'datefrom'=>'required',
            'datetill'=>'required',
        ];

        if($request->show_discount_percent == 1){
            $rules['amount'] = 'required';
            $request['amount_fixed'] = null;
        }
        else{
            $rules['amount_fixed'] = 'required';
            $request['amount'] = null;
        }

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $discountInfo = Discount::find($request->id);
        $oldDiscountType = $discountInfo->discount_type;

        list($y, $m, $d) = explode("-", $request['datefrom']);
        $dateFrom = mktime(0, 0, 0, $m, $d, $y);

        $request['datefrom'] = $dateFrom;

        list($y, $m, $d) = explode("-", $request['datetill']);
        $dateTill = mktime(0, 0, 0, $m, $d, $y);

        $request['datetill'] = $dateTill;

        $discountInfoUpdated = $discountInfo->update($request->all());
        $discountInfo = Discount::find($request->id);
        $newDiscountType = $discountInfo->discount_type;

        if(!$discountInfoUpdated)
            return response()->json(["success"=>false, "message"=>"discount is not created"]);

        // discount data being updated in pivot table according to type
        if($request->discount_type==DiscountType::$PRODUCT)
        {
            $discountInfo->packages()->detach();
            foreach ($request->packageList as $productPackages)
            {
                $productId = $productPackages[0]['preke'];
                //$totalProdPacks = Package::where('preke', $productId)->count();
                foreach ($productPackages as $package)
                {
                    $discountInfo->packages()->attach($package['id'], ['product_id'=>$productId]);
                }
            }
        }

        else if($request->discount_type==DiscountType::$MANUFACTURER)
        {
            $manufactureIds = [];
            foreach ($request->manufacturers as $manufacturer)
            {
                $manufactureIds[] = $manufacturer['id'];
            }
            $discountInfo->manufacturers()->sync($manufactureIds);
        }

        else if($request->discount_type==DiscountType::$CATEGORY)
        {
            $categoryIds = [];
            foreach ($request->categoryList as $category)
            {
                $categoryIds[] = $category['id'];
            }
            $discountInfo->categories()->sync($categoryIds);
        }
        // discount data updated to pivot table according to type

        // detach data from other pivot table if data updated to a different table
        if($oldDiscountType!==$newDiscountType)
        {
            switch ($oldDiscountType){
                case DiscountType::$PRODUCT:
                    $discountInfo->packages()->detach();
                    break;
                case DiscountType::$MANUFACTURER:
                    $discountInfo->manufacturers()->detach();
                    break;
                case DiscountType::$CATEGORY:
                    $discountInfo->categories()->detach();
                    break;
            }
        }

        // for specific users discount linked to them
        if($request->for_all_user === 0)
        {
            $userIds = [];
            foreach ($request->users as $user)
            {
                $userIds[] = $user['id'];
            }
            $discountInfo->users()->sync($userIds);
        }
        else if ($request->for_all_user === 1)
        {
            $discountInfo->users()->detach();
        }

        return response()->json(["success"=>true, "data"=>$discountInfo],200);
    }

    public function getDiscountInfo($id)
    {
        $discountInfo = Discount::find($id);

        $discountInfo->datefrom = gmdate('Y-m-d', $discountInfo->datefrom);
        $discountInfo->datetill = gmdate('Y-m-d', $discountInfo->datetill);

        foreach ($discountInfo->users as $user)
        {
            $user->label = $user->name." ".$user->surname;
        }
        $discountInfo->users = $discountInfo->users;

        if($discountInfo->discount_type == DiscountType::$PRODUCT)
        {
            /*if(count($discountInfo->packages) === 0){
                DB::table('discount_package')->where('product_id', 1);
            }*/
            $products = [];

            //return response()->json($discountInfo);
            foreach ($discountInfo->packages as $package)
            {
                $products[] = $package->preke;
            }
            $productIds = array_unique($products);
            $productObjList = [];

            $splitPackageToProducts = [];
            foreach ($productIds as $productId)
            {
                $packagesInsert = [];
                foreach ($discountInfo->packages as $aPack)
                {
                    if($aPack->preke == $productId)
                        $packagesInsert[] = $aPack;
                }
                $splitPackageToProducts[] = $packagesInsert;
                $oneProduct = Products::find($productId);
                $oneProduct->label = $oneProduct->pavadinimas_lt;
                $productObjList[] = $oneProduct;
            }
            $discountInfo->product = $productObjList;
            $discountInfo->packageList = $splitPackageToProducts;
            return response()->json($discountInfo);
            //$discountInfo->packages = $splitPackagesToProduct; //left is attribute of discount, right side is relational method data
        }
        else if($discountInfo->discount_type == DiscountType::$MANUFACTURER)
        {
            foreach ($discountInfo->manufacturers as $manufacturer)
            {
                $manufacturer->label = $manufacturer->title;
            }
            $discountInfo->manufacturers = $discountInfo->manufacturers;
        }
        else
        {
            foreach ($discountInfo->categories as $category)
            {
                $category->label = $category->pavadinimas_lt;
            }
            $discountInfo->categoryList = $discountInfo->categories;
        }
        return response()->json($discountInfo);
    }

    public function deleteDiscountInfo($id)
    {
        $discountInfo = Discount::find($id);
        $discountInfo->delete();
    }
}
