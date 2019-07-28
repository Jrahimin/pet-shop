<?php

namespace App\Http\Controllers\Admin;

use App\Libraries\Functions;
use App\Model\Manufacturer;
use App\Model\Products;
use App\Enumerations\AttributeType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class ProductCatalogController extends Controller
{
    public function index()
    {
        return view('admin.product_catalog.index');
    }

    public function getGoods()
    {

        $products = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title, gaminiu_kategorijos.pavadinimas_lt as catTitle,
             gamintojai.title as manufacturer,darbai.foto, darbai.id')
            ->leftJoin('gaminiu_kategorijos','darbai.cat','=','gaminiu_kategorijos.id')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->orderBy('darbai.pavadinimas_lt','asc')->paginate(20);
         foreach ($products as $product)
         {
             $product->image = url('/')."/storage/images/katalogas/s3_".$product->foto;

             $categories = DB::table('category_darbai')
                 ->join('gaminiu_kategorijos','category_darbai.category_id','=','gaminiu_kategorijos.id')
                 ->where('darbai_id',$product->id)->get() ;

             $totalCategories = count($categories) ;
             $categoryNames = '';
             foreach ($categories as $key=>$category)
             {
                 $categoryNames .= $category->pavadinimas_lt ;
                 $categoryNames .= $totalCategories >1 && $key<$totalCategories-1 ? " , " : "";
             }
             $product->categoryNames = $categoryNames ;
         }
        return response()->json($products);
    }

    public function filterGoods($searchKey)
    {
        $query = DB::table('darbai')
            ->selectRaw('darbai.pavadinimas_lt as title, gaminiu_kategorijos.pavadinimas_lt as catTitle, gamintojai.title as manufacturer,darbai.foto, darbai.id,darbai.price, darbai.old_price')
            ->leftJoin('gaminiu_kategorijos','darbai.cat','=','gaminiu_kategorijos.id')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id');
        $query=$query->where(function ($query1) use ($searchKey)
        {
            $query1 ->where('darbai.pavadinimas_lt','like','%'.$searchKey.'%')
                ->orWhere('gaminiu_kategorijos.pavadinimas_lt','like','%'.$searchKey.'%');

        });
        $products = $query ->orderBy('darbai.pavadinimas_lt','asc')->paginate(20);
        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s3_".$product->foto;

            $categories = DB::table('category_darbai')
                ->join('gaminiu_kategorijos','category_darbai.category_id','=','gaminiu_kategorijos.id')
                ->where('darbai_id',$product->id)->get() ;

            $totalCategories = count($categories) ;
            $categoryNames = '';
            foreach ($categories as $key=>$category)
            {
                $categoryNames .= $category->pavadinimas_lt ;
                $categoryNames .= $totalCategories >1 && $key<$totalCategories-1 ? " , " : "";
            }
            $product->categoryNames = $categoryNames ;
        }
        return response()->json($products);

    }

    public function deleteGoods($id)
    {
        $good = DB::table('darbai')->where('id',$id)->first();
        $path=public_path()."/storage/images/katalogas/";
        File::delete($path."s1_".$good->foto);
        File::delete($path."s2_".$good->foto);
        File::delete($path."s3_".$good->foto);
        File::delete($path."s4_".$good->foto);
        if( !empty($good->foto2))
        {
            File::delete($path."s1_".$good->foto2);
            File::delete($path."s2_".$good->foto2);
            File::delete($path."s3_".$good->foto2);
            File::delete($path."s4_".$good->foto2);

        }

        //$good_related = DB::table('darbai_nuotraukos')->where('produktas',$id)->first();
        DB::table('darbai')->where('id',$id)->delete();
       // $good_related->delete();


    }

    public function saveProduct(Request $request)
    {
        /*dd(json_decode($request->packages)) ;*/

       $request['file1'] = $request->file1 == "undefined" ? null : $request->file1 ;
       $request['file2'] = $request->file2 == "undefined" ? null : $request->file2 ;
       $request['file3'] = $request->file3 == "undefined" ? null : $request->file3 ;
       $request['file4'] = $request->file4 == "undefined" ? null : $request->file4 ;
       $request['packages'] =json_decode($request->packages);

        $rules = [
            'eshop'=>'required',
            'meta_description'=>'required',
            'meta_key'=>'required',
            'description'=>'required',
            'cat'=>'required',
            'gamintojas'=>'required',
            'pavadinimas_lt'=>'required',
            'full_text'=>'required',
            'file1'=>'required',

        ];
        if($request->eshop==1)
        {
            $rules['havpacks']='required';
            $rules['popitem']='required';
            $rules['newitem']='required';
            if($request->havpacks==0)
            {
                $rules['price']='required';
                $rules['svoris']='required';
            }
            else{
                $rules['packages']='required';
               /* $rules['pweights']='required';
                $rules['ptitle']='required';
                if($request->hasCapacity == 1 )
                {
                    $rules['pcapacity'] = 'required';
                    $rules['pcapacityUnit'] = 'required' ;
                }

                if($request->hasVolume == 1 )
                {
                    $rules['pvolume'] = 'required';
                    $rules['pvolumeUnit'] = 'required' ;
                }

                if($request->hasLength == 1 )
                {
                    $rules['plength'] = 'required';
                    $rules['plengthUnit'] = 'required' ;
                }

                if($request->hasDiameter == 1 )
                {
                    $rules['pdiameter'] = 'required';
                    $rules['pdiameterUnit'] = 'required' ;
                }
                if($request->hasColor == 1 )
                {
                    $rules['pcolors'] = 'required';

                }
                if($request->hasSize == 1 )
                {
                    $rules['psizes'] = 'required';

                }*/

            }

        }

        $messages = [
            'inproducts.required'=>'Please specify if the product should be shown in catalog or not',
            'eshop.required'=>'Please specify if the product is for sale or not',
            'meta_description.required'=>'You must specify meta data',
            'meta_key.required'=>'You must specify meta data',
            'description.required'=>'You must add some description',
            'cat.required'=>'You must specify category of the product ',
            'gamintojas.required'=>'You must specify the manufacturer of the product',
            'pavadinimas_lt.required'=>'Product cannot be without a title',
            'full_text.required'=>'You must write some text',
            'file1.required'=>'You must add a list photo',
            'file2.required'=>'You must add a quality awards photo' ,
            'file3.required' =>'You must add a product description photo',
            'havpacks.required'=>'You must specify whether the product has packages or not',
            'popitem.required'=>'You must specify if the product is a popular item or not',
            'newitem.required'=>'You must specify if the product is a new item or not',
            'price.required'=>'You must specify the price ',
            'svoris.required'=>'You must specify the weight ',
            'poldprice.required'=>'Please provide old price of the packages',
            'pprices.required'=>'Please provide new price of the packages',
            'pweights.required'=>'Please provide weight of the packages',
            'ptitle.required'=>'Please provide title of the packages',

        ];

        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }


        //list photo
        $file = $request->file('file1');
        $fileName = $file->getClientOriginalName();
        $extention = $file->getClientOriginalExtension();
        $newFileName=time()."_".$this->encodeFileName($fileName,12,$extention);
        $path = public_path()."/storage/images/katalogas/";

        $slider1 = Image::make($file)->resize(320,220)->save($path."s1_".$newFileName);
        $slider2 = Image::make($file)->resize(600,600)->save($path."s2_".$newFileName);
        $slider3 = Image::make($file)->resize(128,128)->save($path."s3_".$newFileName);
        $slider4 = Image::make($file)->resize(208,180)->save($path."s4_".$newFileName);
        $slider5 = Image::make($file)->resize(320,320)->save($path."s5_".$newFileName);
        $slider6 = Image::make($file)->resize(86,96)->save($path."s6_".$newFileName);

        //quality awards
        if($request->file2 != null)
        {
            $file2 = $request->file('file2');
            $fileName2 = $file2->getClientOriginalName();
            $extention2 = $file2->getClientOriginalExtension();
            $newFileName2=time()."_".$this->encodeFileName($fileName2,12,$extention2);
            $path = public_path()."/storage/images/katalogas/";

            $slider1 = Image::make($file2)->resize(320,220)->save($path."s1_".$newFileName2);
            $slider2 = Image::make($file2)->resize(600,600)->save($path."s2_".$newFileName2);
            $slider3 = Image::make($file2)->resize(128,128)->save($path."s3_".$newFileName2);
            $slider4 = Image::make($file2)->resize(208,180)->save($path."s4_".$newFileName2);
            $slider5 = Image::make($file2)->resize(320,320)->save($path."s5_".$newFileName2);
            $slider6 = Image::make($file2)->resize(86,96)->save($path."s6_".$newFileName2);
        }
        else{

            $newFileName2 = null ;
        }



        // product description
        if($request->file3 != null)
        {
            $file3 = $request->file('file3');
            $fileName3 = $file3->getClientOriginalName();
            $extention3 = $file3->getClientOriginalExtension();
            $newFileName3=time()."_".$this->encodeFileName($fileName3,12,$extention3);
            $path = public_path()."/storage/images/katalogas/aprasymai";
            $file3->move($path, $newFileName3);
        }
        else{
            $newFileName3 = null ;
        }


        $data= array(
            'pavadinimas_lt'=>$request->pavadinimas_lt,
            'tekstas_lt'=>$request->full_text,
            'description'=>$request->description,
            'gamintojas'=>$request->gamintojas,
             'eshop'=>$request->eshop,
            'inproducts'=>1,
            'haspacks'=>$request->havpacks,
            'popitem'=>$request->popitem,
            'newitem'=>$request->newitem,
            'price'=>$request->price,
            'svoris'=>$request->svoris,
            'akcija'=>0,
            'old_price'=>0.00,
            'pozicija'=>0,
            'aktyvus'=>1,
            'foto'=>$newFileName,
            'foto2'=>$newFileName2,
            'prodfile'=>$newFileName3,
            'pavadinimas_en'=>" "
        );
        $goodId = DB::table('darbai')->insertGetId($data);

        $categories = explode(',',$request->cat);
        foreach ($categories as $cat)
        {
            $category_darbai = DB::table('category_darbai')
                               ->insert(['category_id'=>$cat,
                                         'darbai_id'=>$goodId]);
        }


        if($request->eshop == 1)
        {
            if($request->havpacks==1)
            {
                /*$request->poldprice = explode(',',$request->poldprice);
                $request->pprices = explode(',',$request->pprices);
                $request->pweights = explode(',',$request->pweights);
                $request->ptitle = explode(',',$request->ptitle);
                $request->pcolors = explode(',',$request->pcolors);
                $request->psizes  = explode(',', $request->psizes);
                $request->pcapacity  =explode(',',$request->pcapacity);
                $request->pcapacityUnit=explode(',',$request->pcapacityUnit);
                $request->pvolume  = explode(',',$request->pvolume);
                $request->pvolumeUnit  = explode(',',$request->pvolumeUnit);
                $request->plength  = explode(',',$request->plength);
                $request->plengthUnit  = explode(',',$request->plengthUnit);

                $request->pdiameter  = explode(',',$request->pdiameter);
                $request->pdiameterUnit  = explode(',',$request->pdiameterUnit);

                $length = count($request->pprices);*/

               /* for($i = 1; $i < $length; $i++)
                {
                    $packageData['kaina'] = $request->pprices[$i];
                    $packageData['svoris'] = $request->pweights[$i];
                    $packageData['pavadinimas'] = str_replace('_',',',$request->ptitle[$i]);
                    $packageData['akcija'] = 1;
                    $packageData['preke'] = $goodId;
                    $packageData['sena_kaina'] = 0 ;
                    $packageData['color_id'] = $request->hasColor == 1 ?$request->pcolors[$i]:null;
                    $packageData['size_id']  = $request->hasSize == 1 ? $request->psizes[$i] : null ;

                    $package = DB::table('pakuotes')->insertGetId($packageData);

                    if($request->hasCapacity == 1)
                    {
                        $attribute['package_id'] = $package ;
                        $attribute['attribute_id'] = AttributeType::$Capacity ;
                        $attribute['value'] = $request->pcapacity[$i];
                        $attribute['unit'] = $request->pcapacityUnit[$i];

                        DB::table('package_attributes')->insert($attribute);

                    }

                    if($request->hasVolume == 1)
                    {
                        $attribute['package_id'] = $package ;
                        $attribute['attribute_id'] = AttributeType::$Volume ;
                        $attribute['value'] = $request->pvolume[$i];
                        $attribute['unit'] = $request->pvolumeUnit[$i];

                        DB::table('package_attributes')->insert($attribute);

                    }


                    if($request->hasLength == 1)
                    {
                        $attribute['package_id'] = $package ;
                        $attribute['attribute_id'] = AttributeType::$Length ;
                        $attribute['value'] = $request->plength[$i];
                        $attribute['unit'] = $request->plengthUnit[$i];

                        DB::table('package_attributes')->insert($attribute);

                    }
                    if($request->hasDiameter == 1)
                    {
                        $attribute['package_id'] = $package ;
                        $attribute['attribute_id'] = AttributeType::$Dianeter ;
                        $attribute['value'] = $request->pdiameter[$i];
                        $attribute['unit'] = $request->pdiameterUnit[$i];

                        DB::table('package_attributes')->insert($attribute);

                    }

                }*/

                $packages = $request->packages ;


                $packageIds = [];


                foreach ($packages as $package)
                {
                    /*dd($packages) ;*/
                    $package->color_id =  $package->color_id == '' ? null : $package->color_id ;
                    $package->size_id =  $package->size_id == '' ? null : $package->size_id ;
                    if($package->hasColor == 1 && $package->color_id == null)
                        $package->color_id = 0 ;
                    if($package->hasSize == 1 && $package->size_id == null)
                        $package->size_id = 0 ;


                    $newPackageId =    DB::table('pakuotes')->insertGetId( ['pavadinimas' => $package->pavadinimas ,
                        'svoris' => $package->svoris,
                        'kaina' => $package->kaina,
                        'color_id'=>$package->color_id,
                        'size_id' => $package->size_id ,
                        'sena_kaina'=>0,
                        'preke'=>$goodId,
                        'akcija'=>1,
                        'default' =>0 ,
                        'position' => $package->position
                        ] );

                    foreach ($package->attributes as $attribute)
                    {
                        if($attribute->value != '' && $attribute->unit != '')
                        {
                            DB::table('package_attributes')->insert(
                                ['value'=>$attribute->value,'unit'=>$attribute->unit,'package_id'=>$newPackageId,'attribute_id'=>$attribute->attribute_id]);
                        }

                    }

                }



            }
            else{
                $packageData['sena_kaina'] = 0.00 ;
                $packageData['kaina'] =$request->price ;
                $packageData['svoris'] = $request->svoris ;
                $packageData['pavadinimas'] = 'default' ;
                $packageData['akcija'] = 1;
                $packageData['preke'] = $goodId;
                $packageData['default'] = 1 ;
                $packageData['position'] = 0 ;
                $package = DB::table('pakuotes')->insert($packageData);
            }
        }

        //Additional files
        if($request->file4 != null)
        {
            $file4 = $request->file('file4');
            $fileName4 = $file4->getClientOriginalName();
            $extention4 = $file4->getClientOriginalExtension();
            $size4 = $file4->getSize();
            $newFileName4=time()."_".$this->encodeFileName($fileName4,12,$extention4);
            $path = public_path()."/storage/images/katalogas/failai";
            $file4->move($path, $newFileName4);
            $additionalFiles['title']= $newFileName4;
            $additionalFiles['skiltis'] = $goodId;
            $additionalFiles['file'] = $newFileName4;
            $additionalFiles['size'] = $size4;
            $additionalFiles['ext'] = $extention4;

            DB::table('darbai_files')->insert($additionalFiles);
        }

        $function = new Functions();

        $function->saveSeo('darbai',$goodId,'lt',$request->meta_key,$request->meta_description);

        return response()->json($goodId);
    }

    function encodeFileName($str, $length=0, $extention) {
        $str = mb_strtolower($str, "utf-8");;
        $fname = substr($str, 0, strlen($str)-strlen($extention));
        $lt=array("ą","č","ę","ė","į","š","ų","ū","ž","A","Č","Ę","Ė","Į","Š","Ų","Ū","Ž");
        $rlt=array("a","c","e","e","i","s","u","u","z","A","C","E","E","I","S","U","U","Z");
        $fname=str_replace($lt,$rlt,$fname);
        $fname = $fname.$extention;
        return $fname;
    }


    public function getProductCategories()
    {
        $categories = DB::table('gaminiu_kategorijos')->get();
        return response()->json($categories);
    }
    public function getProduct($id)
    {
        $product = Products::find($id);
        $product->foto = url('/')."/storage/images/katalogas/s1_".$product->foto;

        $packages = DB::table('pakuotes')->where('preke',$product->id)->where('default',0)->orderBy('position','asc')->get();
        $packageLength = count($packages) ;
       /* dd($packageLength);
        if($packageLength>1)
        {
            $packageArray = [];
            foreach ($packages as $package)
            {
                if($package->default == 0)
                {
                    $packageArray[] = $package ;
                }
            }
            $packages = $packageArray ;


        }*/



        foreach ($packages as $package)
        {
            $package->attributes = DB::table('package_attributes')->where('package_id',$package->id)->get()->all();

        }



        $product->packages = $packages;


        $categories = DB::table('category_darbai')
                      ->leftJoin('gaminiu_kategorijos','category_darbai.category_id','=','gaminiu_kategorijos.id')
                      ->where('darbai_id',$id)->get() ;

        $finalCategories = [];
        foreach ($categories as $category)
        {
            $productCat['id'] = $category->id ;
            $productCat['label'] = $category->pavadinimas_lt ;

            $finalCategories[]= $productCat ;
        }


       $product->categories = $finalCategories ;


        $meta = DB::table('seo_nustatymai')->where('id',$product->id)->first();
        $product->meta = $meta;

        return response()->json($product);
    }

    public function editProduct(Request $request)
    {


        $product = Products::find($request->id);

        $request['file1'] = $request->file1 == "undefined" ? null : $request->file1 ;
        $request['file2'] = $request->file2 == "undefined" ? null : $request->file2 ;
        $request['file3'] = $request->file3 == "undefined" ? null : $request->file3 ;
        $request['file4'] = $request->file4 == "undefined" ? null : $request->file4 ;
        $request['packages'] = json_decode($request->packages);

        $rules = [

            'eshop'=>'required',
            'meta_description'=>'required',
            'meta_key'=>'required',
            'description'=>'required',
            'cat'=>'required',
            'gamintojas'=>'required',
            'pavadinimas_lt'=>'required',
            'full_text'=>'required',
            'remove1'=>'required',


        ];
        if($request->eshop==1)
        {
            $rules['havpacks']='required';
            $rules['popitem']='required';
            $rules['newitem']='required';
            if($request->havpacks==0)
            {
                $rules['price']='required';
                $rules['svoris']='required';
            }
            else{

               $rules['packages'] = 'required';
              /* $rules['packages.*.pavadinimas'] = 'required';
               $rules['packages.*.svoris'] = 'required|numeric';
               $rules['packages.*.kaina'] = 'required|numeric';*/

            }

        }

        if($request->remove1==1)
        {
            $rules['file1'] = 'required' ;
        }

        $messages = [
            'inproducts.required'=>'Please specify if the product should be shown in catalog or not',
            'eshop.required'=>'Please specify if the product is for sale or not',
            'meta_description.required'=>'You must specify meta data',
            'meta_key.required'=>'You must specify meta data',
            'description.required'=>'You must add some description',
            'cat.required'=>'You must specify category of the product ',
            'gamintojas.required'=>'You must specify the manufacturer of the product',
            'pavadinimas_lt.required'=>'Product cannot be without a title',
            'full_text.required'=>'You must write some text',
            'file1.required'=>'You must add a list photo',
            'havpacks.required'=>'You must specify whether the product has packages or not',
            'popitem.required'=>'You must specify if the product is a popular item or not',
            'newitem.required'=>'You must specify if the product is a new item or not',
            'price.required'=>'You must specify the price ',
            'svoris.required'=>'You must specify the weight ',
            'poldprice.required'=>'Please provide old price of the packages',
            'pprices.required'=>'Please provide new price of the packages',
            'pweights.required'=>'Please provide weight of the packages',
            'ptitle.required'=>'Please provide title of the packages',

        ];

        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }


        //list photo
        $path = public_path()."/storage/images/katalogas/";
        if($request->remove1 == 1)
        {
            File::delete($path."s1_".$product->foto);
            File::delete($path."s2_".$product->foto);
            File::delete($path."s3_".$product->foto);
            File::delete($path."s4_".$product->foto);
            File::delete($path."s5_".$product->foto);
            File::delete($path."s6_".$product->foto);

        }
         if(!empty($request->file('file1')))
         {
             $file = $request->file('file1');
             $fileName = $file->getClientOriginalName();
             $extention = $file->getClientOriginalExtension();
             $newFileName=time()."_".$this->encodeFileName($fileName,12,$extention);
             $slider1 = Image::make($file)->resize(320,220)->save($path."s1_".$newFileName);
             $slider2 = Image::make($file)->resize(600,600)->save($path."s2_".$newFileName);
             $slider3 = Image::make($file)->resize(128,128)->save($path."s3_".$newFileName);
             $slider4 = Image::make($file)->resize(208,180)->save($path."s4_".$newFileName);
             $slider5 = Image::make($file)->resize(320,320)->save($path."s5_".$newFileName);
             $slider6 = Image::make($file)->resize(86,96)->save($path."s6_".$newFileName);

         }
         else{
             $newFileName = $product->foto;
         }

        //quality awards


        if(!empty($request->file('file2')))
        {
            $path = public_path()."/storage/images/katalogas/";
            File::delete($path."s1_".$product->foto2);
            File::delete($path."s2_".$product->foto2);
            File::delete($path."s3_".$product->foto2);
            File::delete($path."s4_".$product->foto2);
            File::delete($path."s5_".$product->foto2);
            File::delete($path."s6_".$product->foto2);

            $file2 = $request->file('file2');
            $fileName2 = $file2->getClientOriginalName();
            $extention2 = $file2->getClientOriginalExtension();
            $newFileName2=time()."_".$this->encodeFileName($fileName2,12,$extention2);

            $slider1 = Image::make($file2)->resize(320,220)->save($path."s1_".$newFileName2);
            $slider2 = Image::make($file2)->resize(600,600)->save($path."s2_".$newFileName2);
            $slider3 = Image::make($file2)->resize(128,128)->save($path."s3_".$newFileName2);
            $slider4 = Image::make($file2)->resize(208,180)->save($path."s4_".$newFileName2);
            $slider5 = Image::make($file2)->resize(320,320)->save($path."s5_".$newFileName2);
            $slider6 = Image::make($file2)->resize(86,96)->save($path."s6_".$newFileName2);
        }
        else{
            $newFileName2 = $product->foto2;
        }

        // product description
        if(!empty($request->file('file3')))
        {

            File::delete($path.$product->prodfile);
            $file3 = $request->file('file3');

            $fileName3 = $file3->getClientOriginalName();
            $extention3 = $file3->getClientOriginalExtension();
            $newFileName3=time()."_".$this->encodeFileName($fileName3,12,$extention3);
            $path =public_path()."/storage/images/katalogas/aprasymai";
            $file3->move($path, $newFileName3);

        }
        else{
            $newFileName3 = $product->prodfile;
        }

        //Additional files
        if(!empty($request->file('file4')))
        {
            $file4 = $request->file('file4');
            $fileName4 = $file4->getClientOriginalName();
            $extention4 = $file4->getClientOriginalExtension();
            $size4 = $file4->getSize();
            $newFileName4=time()."_".$this->encodeFileName($fileName4,12,$extention4);
            $path = public_path()."/storage/images/katalogas/failai";
            $file4->move($path, $newFileName4);

        }
        else{
            $newFileName4 = "";
        }


        if($request->svoris == 'null' && $request->price == 'null')
        {
            $request->svoris = null ;
            $request->price = null ;
        }

        $data= array(
            'pavadinimas_lt'=>$request->pavadinimas_lt,
            'tekstas_lt'=>$request->full_text,
            'description'=>$request->description,
            'gamintojas'=>$request->gamintojas,
            'eshop'=>$request->eshop,
            'inproducts'=>1,
            'haspacks'=>$request->havpacks,
            'popitem'=>$request->popitem,
            'newitem'=>$request->newitem,
            'price'=>$request->price,
            'svoris'=>$request->svoris,
            'akcija'=>0,
            'old_price'=>0.00,
            'pozicija'=>0,
            'aktyvus'=>0,
            'foto'=>$newFileName,
            'foto2'=>$newFileName2,
            'prodfile'=>$newFileName3,
            'pavadinimas_en'=>""
        );
       DB::table('darbai')->where('id',$request->id)->update($data);

        DB::table('category_darbai')->where('darbai_id',$request->id)->delete() ;

        $categories = explode(',',$request->cat);
        foreach ($categories as $cat)
        {
            $category_darbai = DB::table('category_darbai')
                ->insert(['category_id'=>$cat,
                    'darbai_id'=>$request->id]);
        }

        if($request->havpacks==1)
        {

            $packages = $request->packages ;

            $packageIds = [];

            foreach ($packages as $package)
            {
                if ($package->id != '')
                    $packageIds[] = $package->id;
            }

            if(!empty($packages) && !empty($packageIds))
            {
                $existingPackages = DB::table('pakuotes')->where('preke',$request->id)->get();
                foreach ($existingPackages as $existingPackage)
                {
                    if(!in_array($existingPackage->id,$packageIds))
                    {
                        DB::table('package_attributes')->where('package_id',$existingPackage->id)->delete();
                        DB::table('pakuotes')->where('id',$existingPackage->id)->delete();
                    }
                }
            }

            foreach ($packages as $package)
            {

               $packageExists = DB::table('pakuotes')->find($package->id);

               $attributeIds = [];
               if(!empty($packageExists))
               {

                   $package->color_id =  $package->color_id == null ? 0 : $package->color_id ;
                   $package->size_id =  $package->size_id == null ? 0 : $package->size_id ;
                   DB::table('pakuotes')->where('id',$package->id)->update([
                       'pavadinimas' => $package->pavadinimas ,
                       'svoris' => $package->svoris,
                       'kaina' => $package->kaina,
                       'color_id'=>$package->color_id,
                       'size_id' => $package->size_id,
                       'default' => $package->default,
                       'position' => $package->position
                   ]);

                   foreach ($package->attributes as $attribute)
                   {
                       if ($attribute->id != '') {
                           $attributeIds[] = $attribute->id;
                       }
                   }

                   $existingPackageAttributes = DB::table('package_attributes')->where('package_id',$package->id)->get();
                   foreach ($existingPackageAttributes as $existingPackageAttribute)
                   {
                       if(!in_array($existingPackageAttribute->id , $attributeIds))
                       {
                           DB::table('package_attributes')->where('id',$existingPackageAttribute->id)->delete();
                       }
                   }

                   foreach ($package->attributes as $attribute)
                   {
                       if($attribute->id != '')
                       {
                           DB::table('package_attributes')->where('id',$attribute->id)->update(['value'=>$attribute->value,'unit'=>$attribute->unit]);
                       }
                       else{

                          $result = DB::table('package_attributes')->insert([
                              'value'=>$attribute->value,'unit'=>$attribute->unit,'package_id'=>$package->id,'attribute_id'=>$attribute->attribute_id]);

                       }
                   }
               }
               else{

                  $package->color_id =  $package->color_id == null ? 0 : $package->color_id ;
                  $package->size_id =  $package->size_id == null ? 0 : $package->size_id ;


                $newPackageId =    DB::table('pakuotes')->insertGetId( ['pavadinimas' => $package->pavadinimas ,
                       'svoris' => $package->svoris,
                       'kaina' => $package->kaina,
                       'color_id'=>$package->color_id,
                       'size_id' => $package->size_id ,
                       'sena_kaina'=>0,
                       'preke'=>$request->id,
                       'akcija'=>1,
                       'default' => $package->default,
                       'position' => $package->position ,
                    ] );

                   foreach ($package->attributes as $attribute)
                   {
                       DB::table('package_attributes')->insert(
                           ['value'=>$attribute->value,'unit'=>$attribute->unit,'package_id'=>$newPackageId,'attribute_id'=>$attribute->attribute_id]);
                   }



               }
            }


        }
        elseif($request->havpacks==0)
        {
            $packages = DB::table('pakuotes')->where('preke',$request->id)->get();
            foreach ($packages as $package)
            {
                DB::table('package_attributes')->where('package_id',$package->id)->delete();
            }

            DB::table('pakuotes')->where('preke',$request->id)->delete() ;

            $packageData['sena_kaina'] = 0.00;
            //$packageData['pprom'] = $request->pprom;
            $packageData['kaina'] = $request->price;
            $packageData['svoris'] = $request->svoris;
            $packageData['pavadinimas'] = 'default';
            $packageData['akcija'] = 1;
            $packageData['preke'] = $product->id;
            $packageData['default'] = 1 ;
            $packageData['position'] = 0 ;
            $package = DB::table('pakuotes')->insert($packageData);

        }
        if($newFileName4!="")
        {

            $additionalFiles['title']= $newFileName4;
            $additionalFiles['skiltis'] = $request->id;
            $additionalFiles['file'] = $newFileName4;
            $additionalFiles['size'] = $size4;
            $additionalFiles['ext'] = $extention4;


            DB::table('darbai_files')->insertGetId($additionalFiles);

        }
        if(!empty($request->meta_key) && !empty($request->meta_description))
        {
            DB::table('seo_nustatymai')->where('id',$product->id)->delete();
            $function = new Functions();
            $function->saveSeo('darbai',$product->id,'lt',$request->meta_key,$request->meta_description);

        }


    }


    public function getColors()
    {
        $colors = DB::table('package_colors')->get();

        return response()->json($colors);
    }
    public function getSizes()
    {
        $sizes = DB::table('package_sizes')->get();

        return response()->json($sizes) ;
    }


    public function getProductGallery($id)
    {
       $gallery = DB::table('darbai_nuotraukos')
                  ->where('straipsnis',$id)
                  ->orderBy('pozicija','asc')->paginate(5);

       foreach ($gallery as $image)
       {
           $image->img = url('/')."/storage/images/katalogas/s3_".$image->img;
       }
        $baseDir = asset('storage/images').'/katalogas/';
       return response()->json(["base_dir"=>$baseDir, "galleries"=>$gallery]);


    }

    public function deleteProductPhoto($id)
    {
        $photo = DB::table('darbai_nuotraukos')->where('id',$id)->first();
        File::delete(public_path()."/storage/images/katalogas/s1_".$photo->img);
        File::delete(public_path()."/storage/images/katalogas/s2_".$photo->img);
        File::delete(public_path()."/storage/images/katalogas/s3_".$photo->img);
        File::delete(public_path()."/storage/images/katalogas/s4_".$photo->img);
       DB::table('darbai_nuotraukos')->where('id',$id)->delete();

    }

    public function upProductPhoto($id)
    {
        $photo = DB::table('darbai_nuotraukos')->where('id',$id)->first();
        $oldPosition = $photo->pozicija;
        $photoBelow = DB::table('darbai_nuotraukos')->where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $photoBelow->pozicija;
        DB::table('darbai_nuotraukos')->where('id',$id)->update(['pozicija'=>$newPosition])  ;
        DB::table('darbai_nuotraukos')->where('id',$photoBelow->id)->update(['pozicija'=>$oldPosition]);


    }

    public function downProductPhoto($id)
    {
        $photo = DB::table('darbai_nuotraukos')->where('id',$id)->first();
        $oldPosition = $photo->pozicija;
        $photoAbove = DB::table('darbai_nuotraukos')->where('pozicija', '>', $oldPosition)->orderBy('pozicija', 'asc')->first();
        $newPosition = $photoAbove->pozicija;
        DB::table('darbai_nuotraukos')->where('id',$id)->update(['pozicija'=>$newPosition])  ;
        DB::table('darbai_nuotraukos')->where('id',$photoAbove->id)->update(['pozicija'=>$oldPosition]);
    }
    public function addProductPhoto(Request $request)
    {
        $request['img'] = $request->img=="undefined" ? null : $request->img ;
        $rules = [
            'straipsnis'=>'required',
            'pavadinimas'=>'required',
            'fotopack'=>'required',
            'img' =>'required'
        ];

        $messages =[
            'straipsnis.required'=>'Product must be specified',
            'pavadinimas.required'=>'You must add a title',
            'fotopack.required'=>'You must specify package',
            'img.required'=>'You must add a photo',

        ];

        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        if($request->hasFile('img'))
        {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $extention = $file->getClientOriginalExtension();
            $newFileName=time()."_".$this->encodeFileName($fileName,12,$extention);
            $path = public_path()."/storage/images/katalogas/";

            $slider1 = Image::make($file)->resize(210,210)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
            $slider3 = Image::make($file)->resize(128,128)->save($path."s3_".$newFileName);
            $slider4 = Image::make($file)->resize(208,180)->save($path."s4_".$newFileName);
            $slider5 = Image::make($file)->resize(320,320)->save($path."s5_".$newFileName);
            $slider6 = Image::make($file)->resize(86,96)->save($path."s6_".$newFileName);
        }


        $max_poz = DB::table('darbai_nuotraukos')->where('straipsnis',$request->straipsnis)->max('pozicija');
        if(empty($max_poz))
            $max_poz = 0 ;

        DB::table('darbai_nuotraukos')->insert(['img'=>$newFileName,'straipsnis'=>$request->straipsnis,
                                                     'pavadinimas'=>$request->pavadinimas,'video'=>$request->video,
                                                      'fotopack'=>$request->fotopack, 'pozicija'=>$max_poz+1]);
    }

    public function getProductPackages($id)
    {
        $packages = DB::table('pakuotes')->where('preke',$id)
                            ->whereNotNull('pavadinimas')
                            ->where('kaina','<>',0.00)->get();
        return response()->json($packages);
    }

    public function getphotoInfo($id)
    {
        $photo = DB::table('darbai_nuotraukos')->find($id);
        $productId = $photo->straipsnis ;

        $photo->packages = $this->getProductPackages($productId)->original ;

        $photo->img = url('/')."/storage/images/katalogas/s4_".$photo->img;

        return response()->json($photo);
    }

    public function editProductPhoto(Request $request)
    {

        $request['img'] = $request->img=="undefined" ? null : $request->img ;

        $rules = [
            'pavadinimas'=>'required',
            'fotopack'=>'required',
            'removeIt'=>'required'
        ];
        if($request->removeIt ==1)
        {
            $rules['img'] = 'required' ;
        }
        $messages =[
            'pavadinimas.required'=>'You must provide a title',
            'fotopack.required'=>'Select a package',
            'img.required'=>'You must add a photo'
        ];
        $validation = Validator::make($request->all(), $rules,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $photo = DB::table('darbai_nuotraukos')->find($request->id);

        if($request->removeIt==1)
        {
            File::delete(public_path()."/storage/images/katalogas/s1_".$photo->img);
            File::delete(public_path()."/storage/images/katalogas/s2_".$photo->img);
            File::delete(public_path()."/storage/images/katalogas/s3_".$photo->img);
            File::delete(public_path()."/storage/images/katalogas/s4_".$photo->img);
            File::delete(public_path()."/storage/images/katalogas/s5_".$photo->img);
            File::delete(public_path()."/storage/images/katalogas/s6_".$photo->img);
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $extention = $file->getClientOriginalExtension();
            $newFileName=time()."_".$this->encodeFileName($fileName,12,$extention);
            $path = public_path()."/storage/images/katalogas/";

            $slider1 = Image::make($file)->resize(210,210)->save($path."s1_".$newFileName);
            $slider2 = Image::make($file)->resize(800,600)->save($path."s2_".$newFileName);
            $slider3 = Image::make($file)->resize(128,128)->save($path."s3_".$newFileName);
            $slider4 = Image::make($file)->resize(208,180)->save($path."s4_".$newFileName);
            $slider5 = Image::make($file)->resize(320,320)->save($path."s5_".$newFileName);
            $slider6 = Image::make($file)->resize(86,96)->save($path."s6_".$newFileName);
        }
        else{
            $newFileName = $photo->img;
        }



        DB::table('darbai_nuotraukos')->where('id',$request->id)
            ->update(['img'=>$newFileName, 'pavadinimas'=>$request->pavadinimas,'video'=>$request->video]);
    }

    public function getReviews($id)
    {
        $reviews = DB::table('atsiliepimai')->where('skiltis',$id)->orderBy('pozicija','asc')->get();
        return response()->json($reviews);

    }

    public function moveReviewDown($id)
    {
        $review = DB::table('atsiliepimai')->where('id',$id)->first();
        $oldPosition = $review->pozicija;
        $reviewAbove = DB::table('atsiliepimai')->where('pozicija', '>', $oldPosition)->orderBy('pozicija', 'asc')->first();
        $newPosition = $reviewAbove->pozicija;
        DB::table('atsiliepimai')->where('id',$id)->update(['pozicija'=>$newPosition])  ;
        DB::table('atsiliepimai')->where('id',$reviewAbove->id)->update(['pozicija'=>$oldPosition]);
    }

    public function moveReviewUp($id)
    {
        $review = DB::table('atsiliepimai')->where('id',$id)->first();
        $oldPosition = $review->pozicija;
        $reviewBelow = DB::table('atsiliepimai')->where('pozicija', '<', $oldPosition)->orderBy('pozicija', 'desc')->first();
        $newPosition = $reviewBelow->pozicija;
        DB::table('atsiliepimai')->where('id',$id)->update(['pozicija'=>$newPosition])  ;
        DB::table('atsiliepimai')->where('id',$reviewBelow->id)->update(['pozicija'=>$oldPosition]);
    }

    public function deleteReview($id)
    {
        DB::table('atsiliepimai')->where('id',$id)->delete();
    }

    public function saveReview(Request $request)
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

        $maxPoz = DB::table('atsiliepimai')->where('skiltis',$request->skiltis)->max('pozicija');
        if(empty($maxPoz))
            $maxPoz = 0;
        $request['pozicija'] = $maxPoz+1;
        DB::table('atsiliepimai')->insert($request->all());

    }

    public function getReviewInfo($id)
    {
        $review = DB::table('atsiliepimai')->where('id',$id)->first();
        return response()->json($review);
    }

    public function editReview(Request $request)
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

        $review = DB::table('atsiliepimai')->where('id',$request->id)->update($request->all());
        return response()->json($review);
    }


}
