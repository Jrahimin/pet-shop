<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogCategory extends Model
{
    protected $table = "gaminiu_kategorijos";
    public $timestamps=false;

    protected $fillable = ['page', 'tipas', 'pavadinimas_lt', 'pavadinimas_en', 'imgpavadinimas',
                            'url', 'aktyvus', 'tevas', 'paryskinta', 'pozicija', 'raktazodziai'];

    public function discounts()
    {
        return $this->belongsToMany('App\Model\Discount');
    }

    public function products()
    {
        return $this->belongsToMany('App\Model\Products','category_darbai','category_id','darbai_id') ;
    }

    public function promotions()
    {
        return $this->belongsToMany('App\Model\Promotion');
    }


    public function categoryMatch($userCategoryId,$selectedItemId)
    {

        $menuTree = $this->multiLevelCategory($userCategoryId);


        $stack = array();
        $stack = $menuTree;
        $categoryList = array();

        while(!empty($stack)) {
            $element = array_pop($stack);

            if($element->id == $selectedItemId)
              return true;

            $children = array_reverse($element->children);
            foreach ( $children as $child) {
                if($child->id == $selectedItemId)
                    return true;

                $stack[] = $child;
            }
        }
        return false;
    }

    public function multiLevelCategory($userCategoryId)
    {

        $items = DB::table('gaminiu_kategorijos')->where('tevas', $userCategoryId)->orWhere('id', $userCategoryId)->get()->all();

        $itemAll = DB::table('gaminiu_kategorijos')->get()->all();

        $stack = $items;
        $categoryTree = $items;
        $date = time() ;

        while(!empty($stack))
        {
            $parent_element = array_pop($stack);
            $parent_element->children = [];
            $parent_element->catDiscount = DB::table('catalog_category_discount')
                ->join('discounts','catalog_category_discount.discount_id','discounts.id')
                ->where('discounts.datefrom','<=',$date)
                ->where('discounts.datetill','>=',$date)
                ->where('catalog_category_id',$parent_element->id)->get();

            foreach ($itemAll as &$item)
            {
                if($item->tevas == $parent_element->id)
                {
                    $parent_element->children[] =  $item;
                    $stack[] = $item;
                }
            }
        }

        return $categoryTree;
    }

    public function catagoryDiscountTree()
    {
        $items = DB::table('gaminiu_kategorijos')->where('tevas', 0)->get()->all();

        $itemAll = DB::table('gaminiu_kategorijos')->get()->all();

        $stack = $items;
        $categoryTree = $items;
        $date = time() ;
        while(!empty($stack))
        {
            $parent_element = array_pop($stack);
            $parent_element->children = [];
            $parent_element->catDiscount = DB::table('catalog_category_discount')
                ->join('discounts','catalog_category_discount.discount_id','discounts.id')
                ->where('discounts.datefrom','<=',$date)
                ->where('discounts.datetill','>=',$date)
                ->where('discounts.for_all_user',1)
                ->where('catalog_category_id',$parent_element->id)->get()->all() ;

            foreach ($itemAll as &$item)
            {

                if($item->tevas == $parent_element->id)
                {
                    $parent_element->children[] =  $item;
                    $stack[] = $item;
                }
            }
        }

        return $categoryTree;
    }

    public function newDiscountCategory()
    {
        $items = DB::table('gaminiu_kategorijos')->where('tevas', 0)->get()->all();

        $itemAll = DB::table('gaminiu_kategorijos')->get()->all();

        $stack = $items;
        $categoryTree = $items;
        $date = time() ;
        while(!empty($stack))
        {
            $parent_element = array_pop($stack);
            $parent_element->children = [];
        $categroyDiscountQuery = DB::table('catalog_category_discount')
            ->join('discounts','catalog_category_discount.discount_id','discounts.id')
            ->where('discounts.datefrom','<=',$date)
            ->where('discounts.datetill','>=',$date) ;
        if(Auth::check())
        {
            $user = Auth::user();
            $categroyDiscountQuery =  $categroyDiscountQuery->where(function ($query1)use($user) {
                $query1->where(function ($query2)use($user) {
                    $query2->whereRaw("discounts.id in (select discount_id from discount_user where user_id={$user->id})")
                        ->where('for_all_user', 0);
                })->orWhere('for_all_user', 1);
            });
        }
        else{
            $categroyDiscountQuery = $categroyDiscountQuery->where('discounts.for_all_user',1);
        }



        $parent_element->catDiscount = $categroyDiscountQuery
            ->where('catalog_category_id',$parent_element->id)->get()->all() ;
            foreach ($itemAll as &$item)
            {

                if($item->tevas == $parent_element->id)
                {
                    $parent_element->children[] =  $item;
                    $stack[] = $item;
                }
            }
        }

        return $categoryTree;
    }

}
