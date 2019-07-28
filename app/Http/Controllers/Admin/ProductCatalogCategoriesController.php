<?php

namespace App\Http\Controllers\Admin;

use App\Libraries\Functions;
use App\Model\CatalogCategory;
use App\Model\SeoSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductCatalogCategoriesController extends Controller
{
    public function index()
    {
        return view('admin.product_catalog.categories_index');
    }

    public function categoryListMenu()
    {
        $menuList = $this->multiLevelCategory();

        $totalString = "<div class='container'><div class='row'><div class='col-lg-12 col-xl-12'><div class='d-lg-flex d-xl-flex'><div class='menu mx-auto'><ul>";
        foreach ($menuList as $menu)
        {
            $rootItem = $menu;
            $stack = array();
            $stack[] = ["type" => "menuItem",
                "element" => $rootItem];

            while (!empty($stack)) {
                $menu_element = array_pop($stack);
                if($menu_element['type']=="menuItem")
                {
                    if($menu_element['element']->aktyvus!==1)
                        continue;
                }
                if ($menu_element['type'] == 'menuItem') {
                    $url = route('eshop_category_index', ["id"=>$menu_element['element']->id]);
                    $children = array_reverse($menu_element['element']->children);
                    if (empty($children)) {
                        $totalString .= "<li><a href=$url";
                        $totalString .= " >{$menu_element['element']->pavadinimas_lt}</a></li>";
                    }

                    if (count($children) > 0) {
                        $totalString .= "<li>";
                        $totalString .= " <a href=$url>";
                        $totalString .= "{$menu_element['element']->pavadinimas_lt}</a>";
                        $totalString .= " <ul>";

                        $stack[] = [
                            "type" => 'endTag',
                            "element" => " </ul></li>"
                        ];


                        foreach ($children as $child) {
                            $stack[] = ["type" => "menuItem", "element" => $child];
                            // echo $child;
                        }
                    }

                }
                else {
                    $totalString .= $menu_element['element'];
                }
            }
        }
        $totalString .= "</ul></div></div></div></div></div>";
        return $totalString;
        //return view('frontend.layouts.master', compact('totalString'));
    }

    public function categoryListBuilder()
    {
        $menuTree = $this->multiLevelCategory();
        $imageSrc = asset('storage/images/medis/folder-icon.png');

        $stack = array();
        $categoryList = array();
        foreach($menuTree as $aRootItem)
        {
            if($aRootItem == reset($menuTree))
                $aRootItem->count = "last";
            else if($aRootItem == end($menuTree))
                $aRootItem->count = "first";
            else
                $aRootItem->count = "other";

            if($aRootItem->imgpavadinimas!="")
                $imageSrc = asset('storage/images/katalogas/kategorijos')."/".$aRootItem->imgpavadinimas;

            $aRootItem->space = "";
            $aRootItem->img = "<img src=$imageSrc>"."&nbsp;&nbsp;";
            $stack[] = $aRootItem;
        }
        $stack = array_reverse($stack);

        while(!empty($stack)) {
            $element = array_pop($stack);

                $categoryList[] = [
                    'id' => $element->id,
                    'value' => $element->space.$element->img.$element->pavadinimas_lt."</img>",
                    'label' => $element->pavadinimas_lt,
                    'valueNoImage' => $element->space.$element->pavadinimas_lt,
                    "count" => $element->count,
                ];

            $childSpaces = $element->space.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp';
            $children = array_reverse($element->children);
            foreach ( $children as $child) {
                if($child->imgpavadinimas!="")
                    $imageSrc = asset('storage/images/katalogas/kategorijos')."/".$child->imgpavadinimas;
                else
                    $imageSrc = asset('storage/images/medis/folder-icon.png');

                $child->space = $childSpaces;
                $child->img = "<img src=$imageSrc>";
                if($child == reset($children))
                    $child->count = "first";
                else if($child == end($children))
                    $child->count = "last";
                else
                    $child->count = "other";
                $stack[] = $child;
            }
        }

        return response()->json($categoryList);
    }

    // return a tree of categories including child nodes
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


    public function getCategoriesInfo($id)
    {
        $category = CatalogCategory::find($id);
        $seoSetting = SeoSettings::where('lenta', 'gaminiu_kategorijos')->where('id', $id)->first();

        $category->data = gmdate('Y-m-d', $category->data);
        $category->showImage = url('/')."/storage/images/katalogas/kategorijos/".$category->imgpavadinimas;
        $category->meta_key =  $seoSetting ? $seoSetting->meta_key : "";
        $category->meta_desc = $seoSetting ? $seoSetting->meta_desc : "";

        return response()->json($category);
    }

    public function addCategoryPost(Request $request)
    {
        $rules = [
            'pavadinimas_lt'=>'required',
            'aktyvus'=>'required|integer',
            'tevas'=>'required|integer',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $function = new Functions();
        $url = $function->url_translator($request->pavadinimas_lt);
        $request['url'] = $url;

        if($request->hasFile('image'))
        {
            //save photo for the directory
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newFileName=time()."_".str_random(4)."_".$fileName;
            $path = public_path()."/storage/images/katalogas/kategorijos/";
            $request['imgpavadinimas'] = $newFileName;

            if(!is_dir($path)){
                mkdir($path);
            }
            $categoryImage = Image::make($file)->resize(26,26)->save($path.$newFileName);
        }

        // generate request parameters
        $request['page'] = 453;
        $request['tipas'] = 0;
        $request['paryskinta'] = 0;

        $request['pozicija'] = 1;
        $categorySameParent = CatalogCategory::where('tevas', $request->tevas)->get();
        if(!$categorySameParent->isEmpty())
            $request['pozicija'] = $categorySameParent->max('pozicija') + 1;

        $category =  CatalogCategory::create($request->all());

        $function->saveSeo('gaminiu_kategorijos',$category->id,'lt',$request->meta_key,$request->meta_desc);

        if(!$category)
            return response()->json(["success"=>false, "message"=>"category is not created"],200);

        return response()->json(["success"=>true, "data"=>$category],200);
    }

    public function editCategoriesInfo(Request $request)
    {
        $rules = [
            'pavadinimas_lt'=>'required',
            'aktyvus'=>'required|integer',
            'tevas'=>'required|integer',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $function = new Functions();
        $url = $function->url_translator($request->pavadinimas_lt);
        $request['url'] = $url;

        $category = CatalogCategory::find($request->id);
        $previousParent = $category->tevas;

        if($request->hasFile('image'))
        {
            //save photo for the directory
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newFileName=time()."_".str_random(4)."_".$fileName;
            $path = public_path()."/storage/images/katalogas/kategorijos/";
            $request['imgpavadinimas'] = $newFileName;

            if(!is_dir($path)){
                mkdir($path);
            }

            $categoryImage = Image::make($file)->resize(26,26)->save($path.$newFileName);
        }

        // generate request parameters
        $request['page'] = 453;
        $request['tipas'] = 0;
        $request['paryskinta'] = 0;

        if($previousParent != $request->tevas)
        {
            $request['pozicija'] = 1;
            $categorySameParent = CatalogCategory::where('tevas', $request->tevas)->get();
            if(!$categorySameParent->isEmpty())
                $request['pozicija'] = $categorySameParent->max('pozicija') + 1;
        }

        $categoryUpdated = $category->update($request->all());

        $function->saveSeo('gaminiu_kategorijos',$category->id,'lt',$request->meta_key,$request->meta_desc);

        if(!$categoryUpdated)
            return response()->json(["success"=>false, "message"=>"category is not updated"],200);

        return response()->json(["success"=>true, "data"=>$categoryUpdated],200);
    }

    public function deleteCategory($id)
    {
        $category = CatalogCategory::find($id);

        $path = public_path()."/storage/images/katalogas/kategorijos/";
        File::delete($path.$category->imgpavadinimas);

        $category->delete();
    }

    public function categoryInfoUp($id)
    {
        $categoryInfo = CatalogCategory::find($id);
        $oldPosition = $categoryInfo->pozicija;

        $belowCatalogCategory  = CatalogCategory::where('tevas', $categoryInfo->tevas)->where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $belowCatalogCategory->pozicija;

        $categoryInfo->pozicija = $newPosition;
        $categoryInfo->save();

        $belowCatalogCategory->pozicija = $oldPosition;
        $belowCatalogCategory->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }

    public function categoryInfoDown($id)
    {
        $categoryInfo = CatalogCategory::find($id);
        $oldPosition = $categoryInfo->pozicija;

        $belowCatalogCategory = CatalogCategory::where('tevas', $categoryInfo->tevas)->where('pozicija', '>', $oldPosition)->orderBy('pozicija')->first();
        $newPosition = $belowCatalogCategory->pozicija;

        $categoryInfo->pozicija = $newPosition;
        $categoryInfo->save();

        $belowCatalogCategory->pozicija = $oldPosition;
        $belowCatalogCategory->save();

        return response()->json(["old"=>$oldPosition, "new"=>$newPosition]);
    }
}
