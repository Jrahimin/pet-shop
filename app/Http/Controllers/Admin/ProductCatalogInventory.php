<?php

namespace App\Http\Controllers\Admin;

use App\Enumerations\ProductLogType;
use App\Model\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductCatalogInventory extends Controller
{
    public function index()
    {
        return view('admin.product_catalog.inventory_index') ;
    }

    public function getInventoryInfo()
    {
       $products = DB::table('pakuotes')
            ->selectRaw('pakuotes.id, pakuotes.pavadinimas as packageTitle,
            darbai.pavadinimas_lt as title, gamintojai.title as manufacturer,
             darbai.id as product_id ,stocks.quantity')
            ->leftJoin('darbai','pakuotes.preke','=','darbai.id')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
           ->leftJoin('stocks','pakuotes.id','=','stocks.package_id')
            ->whereNotNull('darbai.id')
           ->orderBy('title','asc')
           ->paginate(20);



        return response()->json($products);
    }

    public function filterProducts($searchKey)
    {
        $query = DB::table('pakuotes')
            ->selectRaw('pakuotes.id, pakuotes.pavadinimas as packageTitle,
            darbai.pavadinimas_lt as title, gamintojai.title as manufacturer,
             darbai.id as product_id ,stocks.quantity')
            ->leftJoin('darbai','pakuotes.preke','=','darbai.id')
            ->leftJoin('gamintojai','darbai.gamintojas','=','gamintojai.id')
            ->leftJoin('stocks','pakuotes.id','=','stocks.package_id')
            ->whereNotNull('darbai.id');

        $query= $query->where('darbai.pavadinimas_lt','like','%'.$searchKey.'%');
        $products = $query ->orderBy('title','asc')->paginate(20);

        return response()->json($products);
    }

    public function addStock(Request $request)
    {
       $stock = Stock::where('package_id',$request->id)->where('product_id',$request->product)->first() ;

        if(!empty($stock))
        {
            $quantity =  $stock->quantity + $request->quantity ;
            $stock->quantity = $quantity ;
            $stock->save() ;

        }
        else
        {
           $stock = Stock::create(['package_id'=>$request->id,
                                   'product_id'=>$request->product,
                                    'quantity'=>$request->quantity]);
        }

        DB::table('product_log')
            ->insert(['product_id'=>$request->product,
                      'package_id' =>$request->id,
                      'stock_id' =>$stock->id,
                      'action'=>ProductLogType::$INVENTORYADD,
                      'note'=>"added $request->quantity items to stock "
                          ]);
    }


    public function deleteStock(Request $request)
    {
        $stock = Stock::where('package_id',$request->id)->where('product_id',$request->product)->first() ;

        if(!empty($stock))
        {
            $quantity =  $stock->quantity - $request->quantity ;
            $stock->quantity = $quantity ;
            $stock->save() ;

        }


        DB::table('product_log')
            ->insert(['product_id'=>$request->product,
                'stock_id'=>$stock->id,
                'package_id'=>$request->id,
                'action'=>ProductLogType::$INVENTORYDELETE,
                'note'=>"deleted $request->quantity items from stock "
            ]);
    }

    public function getInventoryLog()
    {
        $productLog = DB::table('product_log')
                         ->selectRaw('darbai.pavadinimas_lt as title,
                          pakuotes.pavadinimas as packageTitle , product_log.note ,
                           product_log.action')
                         ->leftJoin('darbai','product_log.product_id','=' , 'darbai.id')
                         ->leftJoin('pakuotes','product_log.package_id','=','pakuotes.id')
                         ->paginate(20);

        foreach ($productLog as $log)
        {
            if($log->action == ProductLogType::$INVENTORYADD)
                $log->actionText  = "Products were added" ;
            elseif($log->action == ProductLogType::$INVENTORYDELETE)
                $log->actionText  = "Products were removed" ;
            elseif ($log->action == ProductLogType::$SELL)
                $log->actionText = "Products were sold" ;

        }
        return response()->json($productLog);
    }
}
