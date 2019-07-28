<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return view('frontend.category.index');
    }

    public function categoryListBuilder()
    {
        $menuTree = $this->multiLevelCategory();
        $imageSrc = asset('storage/images/medis/folder-icon.png');

        $stack = array();
        $categoryList = array();
        foreach($menuTree as $aRootItem)
        {
            $aRootItem->space = "";
            $aRootItem->img = "<img src=$imageSrc>"."&nbsp;";
            $stack[] = $aRootItem;
        }
        $stack = array_reverse($stack);

        while(!empty($stack)) {
            $element = array_pop($stack);

            //dd($element);
            $categoryList[] = [
                'id' => $element->id,
                'value' => $element->space.$element->img.$element->pavadinimas_lt."</img>"
            ];

            $childSpaces = $element->space. '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;' ;
            $children = array_reverse($element->children);
            foreach ( $children as $child) {
                $child->space = $childSpaces;
                $child->img = "<img src=$imageSrc>";
                $stack[] = $child;
            }
        }

        return response()->json($categoryList);
    }

    public function multiLevelCategory()
    {
        $parent_menu = array();
        $sub_menu = array();
        $list = array();
        $items = DB::table('gaminiu_kategorijos')->orderBy('pozicija')->get();

        $stack = array();
        $categoryTree = array();

        foreach( $items as &$anItem )
        {
            if($anItem->tevas=='0')
            {
                $stack[] = $anItem;
                $categoryTree[] = $anItem;
            }
        }


        while(!empty($stack))
        {
            $parent_element = array_pop($stack);
            $parent_element->children = [];
            foreach ($items as &$item)
            {
                if($item->tevas == $parent_element->id)
                {
                    $parent_element->children[] =  $item;
                    $stack[] = $item;
                }

            }

        }
        return($categoryTree);
    }
}
