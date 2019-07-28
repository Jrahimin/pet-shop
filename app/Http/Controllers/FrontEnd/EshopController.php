<?php

namespace App\Http\Controllers\FrontEnd;

use App\Enumerations\AttributeType;
use App\Http\Controllers\Admin\ProductCatalogCategoriesController;
use App\Libraries\ProductsDiscount;
use App\Model\Banner;
use App\Model\CatalogCategory;
use App\Model\Discount;
use App\Model\Manufacturer;
use App\Model\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EshopController extends Controller
{
    public function index()
    {
        return view('frontend.eshop.category_index');
    }

    public function checkOutTwo()
    {
        return view('frontend.eshop.checkout_step2');
    }

    public function checkOutThree()
    {
        return view('frontend.eshop.checkout_step3');
    }

    public function checkOutFour()
    {
        return view('frontend.eshop.checkout_step4');
    }

    public function eshopCategoryIndex($id)
    {
        /*return view('frontend.eshop.cart_index');*/

        return view('frontend.eshop.category_index');
    }

    public function getNewProducts()
    {
        $products = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title, gaminiu_kategorijos.pavadinimas_lt as catTitle, 
            gamintojai.title as manufacturer,gamintojai.img as manufacturerPhoto,darbai.foto, 
            darbai.id,darbai.akcija,darbai.svoris,darbai.gamintojas as manufacturerId')
            ->leftJoin('gaminiu_kategorijos','darbai.cat','=','gaminiu_kategorijos.id')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->where('eshop',1)
            ->where('newitem',1)
            ->orderBy('darbai.pavadinimas_lt','asc')->get();

        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s4_".$product->foto;
            $product->manufacturerPhoto = url('/')."/storage/images/gamintojai/s3_".$product->manufacturerPhoto;
            $product->detailLink = url('/')."/product-detail/".$product->id;


        }

        $packages = $this->getProductPackages($products);
       //return response()->json($packages);
        foreach ($products as $product)
        {
            foreach ($packages as $package)
            {
                if($product->id == $package->preke)
                {
                    $product->packages[] = $package ;
                }
            }

            $packageLength = count($product->packages) ;
            if($packageLength>1)
            {
                $packageArray = [];
                foreach ($product->packages as $package)
                {
                    if($package->default === 0)
                    {
                        $packageArray[] = $package ;
                    }
                }
                $product->packages = $packageArray ;
            }
        }
        foreach ($products as $product)
        {
            if(empty($product->packages))
            {
                $product->packageLength = 0;
            }
            else{

				$product->selectedPackageName = $product->packages[0]->pavadinimas == 'default' ? 'One Size' : $product->packages[0]->pavadinimas;
				$product->selectedPackageId = $product->packages[0]->id;
                $product->packageLength = count($product->packages);
                $product->selectedPackagePrice = $product->packages[0]->kaina ;
            }

            foreach ($product->packages as $package)
            {
                $package->pavadinimas = $package->pavadinimas == 'default' ? 'One Size' : $package->pavadinimas ;
            }

        }

        $productDiscount = new ProductsDiscount();
        $discount = $productDiscount->getDiscounts($products) ;

        return response()->json(['products'=>$products, 'discounts'=>$discount]);
    }

    public function getProductsByCat($id)
    {
        $items = DB::table('gaminiu_kategorijos')->where('id', $id)->get()->all();

        $itemAll = DB::table('gaminiu_kategorijos')->get()->all();

        $stack = $items;
        $categoryTree = $items;
        $date = time() ;

        $categoryIds[] = $id;
        while(!empty($stack))
        {
            $parent_element = array_pop($stack);
            //$parent_element->children = [];

            foreach ($itemAll as &$item)
            {
                if($item->tevas == $parent_element->id)
                {
                    $categoryIds[] = $item->id;
                    $parent_element->children[] =  $item;
                    $stack[] = $item;
                }
            }
        }

        $productsFromPivot = DB::table('category_darbai')->whereIn('category_id', $categoryIds)->get();
        $productIds = [] ;
        foreach ($productsFromPivot as $pivotProduct)
        {
            if(!in_array($pivotProduct->darbai_id,$productIds))
            $productIds[] = $pivotProduct->darbai_id ;
        }

        $products = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title,darbai.gamintojas as manufacturerId, gamintojai.title as manufacturer,gamintojai.img as manufacturerPhoto,darbai.foto, darbai.id,darbai.akcija,darbai.svoris')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->where('eshop',1)
            ->whereIn('darbai.id',$productIds)
            ->orderBy('darbai.pavadinimas_lt','asc')->get();

        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s4_".$product->foto;
            $product->manufacturerPhoto = url('/')."/storage/images/gamintojai/s3_".$product->manufacturerPhoto;
            $product->detailLink = url('/')."/product-detail/".$product->id;
            $product->title = ucwords($product->title) ;
        }

        $packages = $this->getProductPackages($products);
        // return response()->json($packages);
        foreach ($products as $product)
        {
            foreach ($packages as $package)
            {
                if($product->id == $package->preke)
                {
                    $product->packages[] = $package ;
                }
            }

            $packageLength = count($product->packages) ;
            if($packageLength>1)
            {
                $packageArray = [];
                foreach ($product->packages as $package)
                {
                    if($package->default === 0)
                    {
                        $packageArray[] = $package ;
                    }
                }
                $product->packages = $packageArray ;
            }

        }
        foreach ($products as $product)
        {
            if(!empty($product->packages))
            {
                $product->packageLength = count($product->packages);
                $product->selectedPackageName = $product->packages[0]->pavadinimas == 'default' ? 'One Size' : $product->packages[0]->pavadinimas;
                $product->selectedPackageId = $product->packages[0]->id;
                $product->selectedPackagePrice = $product->packages[0]->kaina ;
            }
            else
                $product->packageLength = 0;

            foreach ($product->packages as $package)
            {
                $package->pavadinimas = $package->pavadinimas == 'default' ? 'One Size' : $package->pavadinimas ;
            }

        }

        $productDiscount = new ProductsDiscount();
        $discount = $productDiscount->getDiscounts($products) ;

        return response()->json(['products'=>$products, 'discounts'=>$discount]);
    }

    public function getProductsByKey($keyword)
    {
        $products = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title,darbai.gamintojas as manufacturerId, gamintojai.title as manufacturer,gamintojai.img as manufacturerPhoto,darbai.foto, darbai.id,darbai.price,darbai.old_price,darbai.akcija,darbai.svoris')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->where('eshop',1)
            ->where('darbai.pavadinimas_lt', 'like', '%'.$keyword.'%')
            ->orderBy('darbai.pavadinimas_lt','asc')->get();

        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s4_".$product->foto;
            $product->manufacturerPhoto = url('/')."/storage/images/gamintojai/s3_".$product->manufacturerPhoto;
            $product->detailLink = url('/')."/product-detail/".$product->id;
        }

        $packages = $this->getProductPackages($products);
        // return response()->json($packages);
        foreach ($products as $product)
        {
            foreach ($packages as $package)
            {
                if($product->id == $package->preke)
                {
                    $product->packages[] = $package ;
                }
            }

            $packageLength = count($product->packages) ;
            if($packageLength>1)
            {
                $packageArray = [];
                foreach ($product->packages as $package)
                {
                    if($package->default === 0)
                    {
                        $packageArray[] = $package ;
                    }
                }
                $product->packages = $packageArray ;
            }

        }
        foreach ($products as $product)
        {
            if(!empty($product->packages))
            {
                $product->packageLength = count($product->packages);
                $product->selectedPackageName = $product->packages[0]->pavadinimas == 'default' ? 'One Size' : $product->packages[0]->pavadinimas;
                $product->selectedPackageId = $product->packages[0]->id;
                $product->selectedPackagePrice = $product->packages[0]->kaina ;
            }
            else
                $product->packageLength = 0;


            foreach ($product->packages as $package)
            {
                $package->pavadinimas = $package->pavadinimas == 'default' ? 'One Size' : $package->pavadinimas ;
            }

        }
        $productDiscount = new ProductsDiscount();
        $discount = $productDiscount->getDiscounts($products) ;
        return response()->json(['products'=>$products, 'discounts'=>$discount]);
    }


    public function getBestProducts()
    {
        $products = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title, gaminiu_kategorijos.pavadinimas_lt as catTitle, 
            gamintojai.title as manufacturer,gamintojai.img as manufacturerPhoto,darbai.foto, 
            darbai.id,darbai.akcija,darbai.svoris, darbai.gamintojas as manufacturerId')
            ->leftJoin('gaminiu_kategorijos','darbai.cat','=','gaminiu_kategorijos.id')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->where('eshop',1)
            ->where('popitem',1)
            ->orderBy('darbai.pavadinimas_lt','asc')->get();
        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s4_".$product->foto;
            $product->manufacturerPhoto = url('/')."/storage/images/gamintojai/s3_".$product->manufacturerPhoto;
            $product->detailLink = url('/')."/product-detail/".$product->id;
        }
        $packages = $this->getProductPackages($products);
        foreach ($products as $product)
        {
            foreach ($packages as $package)
            {
                if($product->id == $package->preke)
                {
                    $product->packages[] = $package ;
                }
            }

            $packageLength = count($product->packages) ;
            if($packageLength>1)
            {
                $packageArray = [];
                foreach ($product->packages as $package)
                {
                    if($package->default === 0)
                    {
                        $packageArray[] = $package ;
                    }
                }
                $product->packages = $packageArray ;
            }

        }
        foreach ($products as $product)
        {
            if(!empty($product->packages))
            {
                $product->packageLength = count($product->packages);
                $product->selectedPackageName = $product->packages[0]->pavadinimas == 'default' ? 'One Size' : $product->packages[0]->pavadinimas;
                $product->selectedPackageId = $product->packages[0]->id;
                $product->selectedPackagePrice = $product->packages[0]->kaina ;

            }
            else
                $product->packageLength = 0;


            foreach ($product->packages as $package)
            {
                $package->pavadinimas = $package->pavadinimas == 'default' ? 'One Size' : $package->pavadinimas ;
            }

        }

        $productDiscount = new ProductsDiscount();
        $discount = $productDiscount->getDiscounts($products) ;

        return response()->json(['products'=>$products,'discounts'=>$discount]);
    }




    public function getProductPackages($products)
    {
        $productIds = array();
        foreach ($products as $product)
        {
            array_push($productIds,$product->id);
        }
        $packages = DB::table('pakuotes')->whereIn('preke',$productIds)->orderBy('position','asc')->get();



        return $packages;


    }


    public function getBestProductsBanners()
    {
        //DB::connection()->enableQueryLog();
       // return response()->json('hello');
        $query = Banner::where('vieta',1)
                           ->where('data_nuo','<',time())
                           ->where('aktyvus',1);

        $query=$query->where(function ($queryNested)
        {
            $queryNested=$queryNested->where(function ($query1)
            {
                $query1 ->where('kriterijus',1)
                    ->where('data_iki','>',time());

            });

            $queryNested=$queryNested->orWhere(function ($query2)
            {
                $query2 ->where('kriterijus',2)
                    ->whereColumn('parodyta','<','parodymai');

            });
            $queryNested=$queryNested->orWhere(function ($query3)
            {
                $query3 ->where('kriterijus',3)
                    ->whereColumn('paspausta','<','paspaudimai');

            });
        });


        $banners = $query->inRandomOrder()->limit(2)->get();
       // dd(DB::getQueryLog());

        foreach ($banners as $banner)
        {
            $banner->parodyta= $banner->parodyta+1 ;
            $banner->save();
            $banner->img = url('/')."/storage/images/baneriai/".$banner->img ;
        }

        return response()->json($banners);


    }


    public function getNewProductsBanners()
    {
        // return response()->json('hello');
        $query = Banner::where('vieta',0)
            ->where('data_nuo','<',time())
            ->where('aktyvus',1);

        $query=$query->where(function ($queryNested)
        {
            $queryNested=$queryNested->where(function ($query1)
            {
                $query1 ->where('kriterijus',1)
                    ->where('data_iki','>',time());

            });

            $queryNested=$queryNested->orWhere(function ($query2)
            {
                $query2 ->where('kriterijus',2)
                    ->whereColumn('parodyta','<','parodymai');

            });
            $queryNested=$queryNested->orWhere(function ($query3)
            {
                $query3 ->where('kriterijus',3)
                    ->whereColumn('paspausta','<','paspaudimai');

            });
        });

        $banners = $query->inRandomOrder()->limit(2)->get();
        foreach ($banners as $banner)
        {
            $banner->parodyta= $banner->parodyta+1 ;
            $banner->save();
            $banner->image = url('/')."/storage/images/baneriai/".$banner->img ;
        }


        return response()->json($banners);


    }

    public function increaseBannerClick($id)
    {
        $banner = Banner::find($id);
        $banner->paspausta = $banner->paspausta+1;
        $banner->save();
    }



    public function productDetailsPage($id)
    {
        return view('frontend.eshop.product_details');
    }

    public function getProduct($id)
    {
        $product = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title, gaminiu_kategorijos.pavadinimas_lt as catTitle,
            gamintojai.title as manufacturer,gamintojai.img as manufacturerPhoto,darbai.foto, darbai.foto2, darbai.id, darbai.prodfile,
            darbai.price,darbai.old_price,darbai.akcija,darbai.svoris, darbai.tekstas_lt as info, darbai.gamintojas as manufacturerId')
            ->leftJoin('gaminiu_kategorijos','darbai.cat','=','gaminiu_kategorijos.id')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->where('darbai.id',$id)
            ->first();
        $photo1['smallImage'] = url('/')."/storage/images/katalogas/s6_".$product->foto;
        $photo1['bigImage'] = url('/')."/storage/images/katalogas/s1_".$product->foto;
        if($product->foto2 != '' && $product->foto2 != null)
        {
            $product->qualityAwards = url('/')."/storage/images/katalogas/s1_".$product->foto2;
        }

        else  $product->qualityAwards = null ;

        if($product->prodfile !='' && $product->prodfile!= null )
        {
            $product->productDescription = url('/')."/storage/images/katalogas/aprasymai/".$product->prodfile ;
        }

        else{
            $product->productDescription = null ;
        }

        $additionalFile = DB::table('darbai_files')->where('skiltis',$id)->first();
        if($additionalFile != null)
        {
            $product->additionalFile =url('/')."/storage/images/katalogas/failai/".$additionalFile->file ;
        }

        $product->manufacturerPhoto = url('/')."/storage/images/gamintojai/s3_".$product->manufacturerPhoto;

        $products =array($product);
        $product->packages = $this->getProductPackages($products);
        $packageLength = count($product->packages) ;
        if($packageLength>1)
        {
            $packageArray = [];
            foreach ($product->packages as $package)
            {
                if($package->default === 0)
                {
                    $packageArray[] = $package ;
                }
            }
            $product->packages = $packageArray ;
        }
       /* dd($product->packages);*/
        $product->gallery = $this->getProductGallery($id);
        $product->gallery[] = $photo1 ;
        $colors = [];
        $colorIds =[];
        $colorArrayLength = null;
        foreach ($product->packages as $package)
        {

            if($package->color_id !== null)
            {

                if(!in_array($package->color_id,$colorIds))
                {

                    if($package->color_id != 0)
                    {

                        $colorIds[] = $package->color_id ;
                        $colors[] = DB::table('package_colors')->find($package->color_id) ;

                    }
                    else{
                        $colorArrayLength = 0 ;
                        break ;
                    }

                }
            }
            else{
                $colorArrayLength = 0 ;
                break ;
            }


        }

        if( $colorArrayLength=== 0)
        {
            $product->colors = [];
        }
        else{
            $product->colors = $colors ;
        }





        $product->comments = $this->getProductComments($id);

        if(!empty($product->packages))
        {
            $product->packageLength = $packageLength;
            $product->selectedPackageName = $product->packages[0]->pavadinimas;
            $product->selectedPackageId = $product->packages[0]->id;
            $product->selectedPackagePrice = $product->packages[0]->kaina ;
            if($product->packages[0]->color_id !== null && $product->packages[0]->color_id !== 0)
            {
                $product->selectedPackageColor = DB::table('package_colors')->where('id',$product->packages[0]->color_id)->first()->name;
            }
            else $product->selectedPackageColor = '';
            if($product->packages[0]->size_id !== null && $product->packages[0]->size_id !== 0)
            {
                $product->selectedPackageSize = DB::table('package_sizes')->where('id',$product->packages[0]->size_id)->first()->name;
            }
            else{
                $product->selectedPackageSize = '' ;
            }

        }
        else
            $product->packageLength = 0;

        $productDiscount = new ProductsDiscount();
        $discount = $productDiscount->getDiscountsForOneProduct($product) ;

        return response()->json(['product'=>$product,'discounts'=>$discount]);
        
      

    }

    public function getPackageColorSizeAttribute($id)
    {
        $package = DB::table('pakuotes')->find($id);

        if($package->color_id !== null && $package->color_id !==0)
        {
            $attribute['color'] = DB::table('package_colors')->find($package->color_id)->name;
        }
        else{
            $attribute['color']='';
        }
        if($package->size_id !== null && $package->size_id !==0)
        {
            $attribute['size'] = DB::table('package_sizes')->find($package->size_id)->name;
        }
        else{
            $attribute['size']='';
        }

        $attribute['packageAttributes'] = DB::table('package_attributes')->where('package_id',$id)->get();
        return response()->json($attribute);
    }


    public function getAvailableSizes(Request $request)
    {

        $product = $request->product ;

        if($request->color != 0)
        {
            $packages = DB::table('pakuotes')->where('preke',$product['id'])->where('color_id',$request->color)->orderBy('position','asc')->get();
        }
        else{
            $packages = DB::table('pakuotes')->where('preke',$product['id'])->orderBy('position','asc')->get();
        }

        $packageLength = count($packages) ;
        if($packageLength>1)
        {
            $packageArray = [];
            foreach ($packages as $package)
            {
                if($package->default === 0)
                {
                    $packageArray[] = $package ;
                }
            }
            $packages = $packageArray ;
        }

        $sizes = [] ;
        $sizeIds = [];
        $sizeArrLength = null ;
        foreach ($packages as $package)
        {
            if($package->size_id !== null)
            {
                if(!in_array($package->size_id,$sizeIds))
                {
                    if($package->size_id == 0)
                    {
                       /* $sizeIds[] = 0;
                        $size['name'] = 'One Size' ;
                        $size['details'] = '';
                        $size['id'] = 0 ;

                        $sizes[] = $size;*/
                        $sizeArrLength = 0;
                        break ;

                    }
                    else{
                        $sizeIds[] = $package->size_id;
                        $sizes[] = DB::table('package_sizes')->find($package->size_id);
                    }

                }
            }
            else{
                $sizeArrLength = 0;
                break ;
            }

        }
        if($sizeArrLength===0)
        {
            $sizes = [];
        }

        return response()->json(['packages'=>$packages,'sizes'=>$sizes]);
    }


    public function getAvailablePackages(Request $request)
    {
        $product = $request->product ;
        $query = DB::table('pakuotes')
            ->where('preke',$product['id']);
           /* ->where('size_id',$request->size)
            ->where('color_id',$request->color)->get();*/
        if($request->size != 0)
        {
            $query = $query->where('size_id',$request->size);

        }
        if ($request->color != 0){
            $query = $query->where('color_id',$request->color) ;

        }
        if($request->size == 0 && $request->color == 0 ){
            $packages = $query ;
        }

        $packages = $query->orderBy('position','asc')->get();

        $packageLength = count($packages) ;
        if($packageLength>1)
        {
            $packageArray = [];
            foreach ($packages as $package)
            {
                if($package->default === 0)
                {
                    $packageArray[] = $package ;
                }
            }
            $packages = $packageArray ;
        }

        $packageIds = [] ;

        foreach ($packages as $package)
        {
            $packageIds[] = $package->id;
        }

        /*$attributes = DB::table('package_attributes')->whereIn('package_id', $packageIds)->get();
        foreach ($attributes as $attribute)
        {
            if($attribute->attribute_id == AttributeType::$Capacity)
            {
                $attribute->type = "Capacity";
            }
            elseif($attribute->attribute_id == AttributeType::$Volume)
            {
                $attribute->type = "Volume";
            }
            elseif($attribute->attribute_id == AttributeType::$Length)
            {
                $attribute->type = "Length";
            }
            elseif($attribute->attribute_id == AttributeType::$Dianeter)
            {
                $attribute->type = "Capacity";
            }

        }*/
        return response()->json($packages) ;
    }


    public function getPackage(Request $request)
    {
        $query = DB::table('pakuotes')->where('preke',$request->productId);
        $query = $request->color != 0 ? $query->where('color_id',$request->color) : $query ;
        $query = $request->size != 0 ? $query->where('size_id',$request->size) : $query ;
        $packages = $query->get()->all() ;
        $attribute = null ;
        if($request->capacity != 0)
        {
            $attribute = DB::table('package_attributes')->find($request->capacity);
        }
        elseif ($request->volume != 0 )
        {
            $attribute = DB::table('package_attributes')->find($request->volume);
        }
        elseif ($request->length != 0 )
        {
            $attribute = DB::table('package_attributes')->find($request->length);
        }
        elseif ($request->diameter != 0 )
        {
            $attribute = DB::table('package_attributes')->find($request->diameter);
        }

        if($attribute != null)
        {
            foreach ($packages as $package)
            {
                if($package->id == $attribute->package_id)
                {
                    return response()->json($package);
                }
            }
        }
        else return response()->json($packages);


    }

    public function refineVolumes(Request $request)
    {
        $capacity = DB::table('package_attributes')->find($request->capacity);
        $volumes = $request->volumes ;
        $volumeArr = [] ;
        foreach ($volumes as $volume)
        {
            if($volume['package_id'] == $capacity->package_id)
            {
                $volumeArr[] =$volume ;
            }
        }

        return response()->json($volumeArr);
    }



    public function refineLengths(Request $request)
    {
        $volume = DB::table('package_attributes')->find($request->volume);
        $lengths = $request->lengths ;
        $lengthsArr = [] ;
        foreach ($lengths as $length)
        {
            if($length['package_id'] == $volume->package_id)
            {
                $lengthsArr[] =$length ;
            }
        }

        return response()->json($lengthsArr);
    }



    public function refineDiameters(Request $request)
    {
        $length = DB::table('package_attributes')->find($request->length);
        $diameters = $request->diameters ;
        $diametersArr = [] ;
        foreach ($diameters as $diameter)
        {
            if($diameter['package_id'] == $length->package_id)
            {
                $diametersArr[] =$diameter ;
            }
        }

        return response()->json($diametersArr);
    }




    public function getProductGallery($id)
    {
      $gallery = DB::table('darbai_nuotraukos')
                    ->where('straipsnis',$id)
                    ->orderBy('pozicija','asc')
                    ->get()->all();
        foreach ($gallery as $image)
        {
            $image->smallImage = url('/')."/storage/images/katalogas/s6_".$image->img;
            $image->bigImage = url('/')."/storage/images/katalogas/s1_".$image->img;
        }
        return $gallery;

    }

    public function getProductVideos($id)
    {
        $gallery = DB::table('darbai_nuotraukos')
            ->where('straipsnis',$id)
            ->orderBy('pozicija','asc')
            ->get()->all();
        $videos = [];
        foreach ($gallery as $image)
        {
           if($image->video != null && $image->video !='')
           {
               $embedYoutubeLink = str_replace('watch?v=', 'embed/', $image->video);
               $image->embedVideoLink = $embedYoutubeLink;
               $videos[] = $image ;
           }
        }
        return $videos;
    }

    public function getProductComments($id)
    {
        $comments = DB::table('atsiliepimai')
                        ->where('skiltis',$id)
                        ->where('active',1)
                        ->orderBy('pozicija','asc')
                        ->get();
        return $comments;
    }
    public function getRelatedProducts($id)
    {
        $related = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title,  gamintojai.title as manufacturer,
            gamintojai.img as manufacturerPhoto,darbai.foto, darbai.id,
            darbai.price,darbai.old_price,darbai.akcija,darbai.svoris')
            ->join('darbai_related','darbai.id','=','darbai_related.itemid')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->where('darbai.eshop',1)
            ->where('darbai_related.itemid',$id)
            ->limit(8)->get();
       // dd($related);
       // return response()->json($related);
        if(count($related)<8)
        {
            $existingIds = array();
            if(count($related)>0)
            {
                foreach ($related as $relatedProduct)
                {
                    array_push($existingIds,$relatedProduct->id);
                }
            }
            $catagory = DB::table('darbai')->where('id',$id)->first();
            $remainingRelated = DB::table('darbai')
                                  ->selectRaw('darbai.pavadinimas_lt as title,
                                   gamintojai.title as manufacturer,gamintojai.img as manufacturerPhoto,
                                   darbai.foto, darbai.id,darbai.price,darbai.old_price,
                                   darbai.akcija,darbai.svoris')
                              ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
                              ->where('darbai.eshop',1)
                              ->where('cat',$catagory->cat)
                              ->whereNotIn('id',$existingIds)
                              ->limit(8-count($related))
                              ->get();
             $related = $related->merge($remainingRelated);

            ;

        }
        foreach ($related as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s4_".$product->foto;
            $product->manufacturerPhoto = url('/')."/storage/images/gamintojai/s3_".$product->manufacturerPhoto;
            $product->detailLink = url('/')."/product-detail/".$product->id;
        }
        return response()->json($related);

    }

    public function getProductsManufacturer(Request $request)
    {

        $manufacturerIds = array();
        foreach ($request->products as $product)
        {
            array_push($manufacturerIds,$product['manufacturerId']);
        }

        $manufacturers = Manufacturer::whereIn('id',$manufacturerIds)->orderBy('url')->distinct()->get();
        return response()->json($manufacturers);
    }



    public function getProductsByManufacturer(Request $request)
    {
        $manufacturerList = $request->manufacturers;
        $manufactCount = count($manufacturerList);

        if($request->category)
        {
            $items = DB::table('gaminiu_kategorijos')->where('id', $request->category)->get()->all();

            $itemAll = DB::table('gaminiu_kategorijos')->get()->all();

            $stack = $items;
            $categoryTree = $items;
            $date = time() ;

            $categoryIds[] = $request->category;
            while(!empty($stack))
            {
                $parent_element = array_pop($stack);
                $parent_element->children = [];

                foreach ($itemAll as &$item)
                {
                    if($item->tevas == $parent_element->id)
                    {
                        $categoryIds[] = $item->id;
                        $parent_element->children[] =  $item;
                        $stack[] = $item;
                    }
                }
            }
        }

        $request->category ? $filter = $categoryIds : $filter = $request->keyword;

        $products = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title,darbai.gamintojas as manufacturerId, gamintojai.title as manufacturer,gamintojai.img as manufacturerPhoto,darbai.foto, darbai.id,darbai.price,darbai.old_price,darbai.akcija,darbai.svoris')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->where('inproducts',1)
            ->when($request->category, function ($query) use ($filter) {
                return $query->whereIn('cat', $filter);
            })
            ->when($request->keyword, function ($query) use ($filter) {
                return $query->where('darbai.pavadinimas_lt', 'like', '%'.$filter.'%');
            })
            ->when($manufactCount>0, function ($query) use ($manufacturerList) {
                return $query->whereIn('darbai.gamintojas', $manufacturerList);
            })
            ->orderBy('darbai.pavadinimas_lt','asc')->get();

        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s4_".$product->foto;
            $product->manufacturerPhoto = url('/')."/storage/images/gamintojai/s3_".$product->manufacturerPhoto;
            $product->detailLink = url('/')."/product-detail/".$product->id;
            $product->title = ucwords($product->title) ;
        }

        $packages = $this->getProductPackages($products);
        // return response()->json($packages);
        foreach ($products as $product)
        {
            foreach ($packages as $package)
            {
                if($product->id == $package->preke)
                {
                    $product->packages[] = $package ;
                }
            }
        }
        foreach ($products as $product)
        {
            if(!empty($product->packages))
            {
                $product->packageLength = count($product->packages);
                $product->selectedPackageName = $product->packages[0]->pavadinimas == 'default' ? 'One Size' : $product->packages[0]->pavadinimas;
                $product->selectedPackageId = $product->packages[0]->id;
                $product->selectedPackagePrice = $product->packages[0]->kaina ;
            }


            foreach ($product->packages as $package)
            {
                $package->pavadinimas = $package->pavadinimas == 'default' ? 'One Size' : $package->pavadinimas ;
            }


        }

        $productDiscount = new ProductsDiscount();
        $discount = $productDiscount->getDiscounts($products) ;

        return response()->json(['products'=>$products, 'discounts'=>$discount]);
    }

}
