<?php
namespace App\Libraries ;

use App\Model\CatalogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsDiscount{

    public function getDiscounts($products)
    {
        $manufacturerDiscounts = [] ;

        $manufacturerIds = $this->getManufacturerIds($products);
        $date = time() ;

        //get discount for those manufactureer

        $manufacturerDiscountsQuery = DB::table('discount_manufacturer')
            ->selectRaw('discount_manufacturer.manufacturer_id as manufacturerId,discounts.* ')
            ->join('discounts','discount_manufacturer.discount_id','discounts.id')
            ->where('discounts.datefrom', '<=', $date)
            ->where('discounts.datetill', '>=', $date);


        $catalogCategory = new CatalogCategory();
        $discountCategoryTree = $catalogCategory->newDiscountCategory();
        //take discount from category
        foreach($products as $product)
        {
            $productCategories = DB::table('category_darbai')->where('darbai_id',$product->id)->get() ;
            $categories = [];
            foreach ($productCategories as $category)
            {
                $categories[] = $category->category_id ;
            }

            $product->categories = $categories;
            $categoryDiscounts = [];
            foreach ($productCategories as $productCategory)
            {
                $arrayDiscount = $this->getCategoryDiscount($discountCategoryTree, $productCategory->category_id) ;
                if($arrayDiscount != false)
                $categoryDiscounts = array_merge($categoryDiscounts,$arrayDiscount) ;
            }
            $product->categoryDiscounts = $categoryDiscounts ;

        }


        //get discount for product and package. please match only product id for that
        $productIds = $this->getProductIds($products);
        $productDiscountsQuery =  DB::table('discount_package')
            ->selectRaw('discount_package.product_id as productId, discount_package.package_id ,discounts.* ')
            ->join('discounts','discount_package.discount_id','discounts.id')
            ->where('discounts.datefrom', '<=', $date)
            ->where('discounts.datetill', '>=', $date);


        if(Auth::check())
        {
            $date = time();
            $user = Auth::user();


            $manufacturerDiscountsQuery = $manufacturerDiscountsQuery->where(function ($query1)use($user) {
                $query1->where(function ($query2)use($user) {
                    $query2->whereRaw("discounts.id in (select discount_id from discount_user where user_id={$user->id})")
                        ->where('for_all_user', 0);
                })->orWhere('for_all_user', 1);
            });

            $productDiscountsQuery = $productDiscountsQuery->where(function($query1) use($user){
                $query1->where(function ($query2)use($user) {
                    $query2->whereRaw("discounts.id in (select discount_id from discount_user where user_id={$user->id})")
                        ->where('for_all_user', 0);
                })->orWhere('for_all_user', 1);
            });



            /**/
        }
        else
        {
            $manufacturerDiscountsQuery = $manufacturerDiscountsQuery->where('for_all_user', 1);
            $productDiscountsQuery = $productDiscountsQuery->where('for_all_user', 1);

            foreach ($products as $product)
            {
                $catDiscounts = [] ;
                foreach ($product->categoryDiscounts as $catDiscount)
                {

                    if(!empty($catDiscount->for_all_user == 1))
                    {
                        $catDiscounts[] = $catDiscount ;
                    }

                }

                $product->categoryDiscounts = $catDiscounts ;
            }
        }
        $manufacturerDiscounts = $manufacturerDiscountsQuery->whereIn('discount_manufacturer.manufacturer_id',$manufacturerIds)->get()->all();
        $productDiscounts = $productDiscountsQuery->whereIn('discount_package.product_id',$productIds)->get()->all();


       /*$queries = DB::getQueryLog dd($manufacturerDiscounts) ;*/

      /* $result['products'] = $products ;*/
       $result['manDiscount'] = $manufacturerDiscounts ;
       $result['productDiscount'] = $productDiscounts ;
       return $result ;



}

    public function getManufacturerIds($products)
    {
        $manufacturerIds = [];
        foreach ($products as $product)
        {
            if(!in_array($product->manufacturerId,$manufacturerIds))
            {
                $manufacturerIds[] = $product->manufacturerId ;
            }
        }
        return $manufacturerIds ;
    }

    public function getProductIds($products)
    {
        $productIds = [];
        foreach ($products as $product)
        {
            if(!in_array($product->id , $productIds))
                $productIds[] = $product->id ;
        }
        return $productIds ;
    }

    public function getDiscountsForOneProduct($product)
    {
        $date = time() ;
        $manufacturerDiscountsQuery = DB::table('discount_manufacturer')
            ->selectRaw('discount_manufacturer.manufacturer_id as manufacturerId,discounts.* ')
            ->join('discounts','discount_manufacturer.discount_id','discounts.id')
            ->where('discounts.datefrom', '<=', $date)
            ->where('discounts.datetill', '>=', $date);


        $catalogCategory = new CatalogCategory();
        $discountCategoryTree = $catalogCategory->newDiscountCategory();
        $productCategories = DB::table('category_darbai')->where('darbai_id',$product->id)->get()->all() ;
        $categoryDiscounts = [] ;
        foreach ($productCategories as $productCategory)
        {
            $arrayDiscount = $this->getCategoryDiscount($discountCategoryTree, $productCategory->category_id) ;
            if($arrayDiscount != false)
                $categoryDiscounts = array_merge($categoryDiscounts,$arrayDiscount) ;
        }
        $product->categoryDiscounts = $categoryDiscounts ;

        $productDiscountsQuery =  DB::table('discount_package')
            ->selectRaw('discount_package.product_id as productId, discount_package.package_id ,discounts.* ')
            ->join('discounts','discount_package.discount_id','discounts.id')
            ->where('discounts.datefrom', '<=', $date)
            ->where('discounts.datetill', '>=', $date);


        if(Auth::check())
        {
            $date = time();
            $user = Auth::user();


            $manufacturerDiscountsQuery = $manufacturerDiscountsQuery->where(function ($query1)use($user) {
                $query1->where(function ($query2)use($user) {
                    $query2->whereRaw("discounts.id in (select discount_id from discount_user where user_id={$user->id})")
                        ->where('for_all_user', 0);
                })->orWhere('for_all_user', 1);
            });

            $productDiscountsQuery = $productDiscountsQuery->where(function($query1) use($user){
                $query1->where(function ($query2)use($user) {
                    $query2->whereRaw("discounts.id in (select discount_id from discount_user where user_id={$user->id})")
                        ->where('for_all_user', 0);
                })->orWhere('for_all_user', 1);
            });



            /**/
        }
        else
        {
            $manufacturerDiscountsQuery = $manufacturerDiscountsQuery->where('for_all_user', 1);
            $productDiscountsQuery = $productDiscountsQuery->where('for_all_user', 1);

                $catDiscounts = [] ;
                foreach ($product->categoryDiscounts as $catDiscount)
                {

                    if(!empty($catDiscount->for_all_user == 1))
                    {
                        $catDiscounts[] = $catDiscount ;
                    }

                }

                $product->categoryDiscounts = $catDiscounts ;

        }
        $manufacturerDiscounts = $manufacturerDiscountsQuery->where('discount_manufacturer.manufacturer_id',$product->manufacturerId)->get()->all();
        $productDiscounts = $productDiscountsQuery->where('discount_package.product_id',$product->id)->get()->all();


        /*$queries = DB::getQueryLog dd($manufacturerDiscounts) ;*/

        /* $result['products'] = $products ;*/
        $result['manDiscount'] = $manufacturerDiscounts ;
        $result['productDiscount'] = $productDiscounts ;

        return $result ;


    }




    private function getCategoryDiscount($categoryTreeArr, $category,$discounts=[])
    {


        foreach( $categoryTreeArr as $aCategoryTree )
        {
            $newChildDiscount = array_merge( $discountArr = $aCategoryTree->catDiscount,  $discounts);

            if( $aCategoryTree->id==$category)
                return $newChildDiscount;
            if( $aCategoryTree->children && count( $aCategoryTree->children )>0 )
            {
                $retData = $this->getCategoryDiscount($aCategoryTree->children, $category, $newChildDiscount);
                if(!empty($retData))
                    return $retData;

            }
        }
        return false;
    }

}