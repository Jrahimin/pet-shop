<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return view('frontend.product.index');
    }

    public function getAllProducts($id = null)
    {
        $id ? $parameter = true : $parameter = false;

        $products = DB::table('darbai')->orderBy('pavadinimas_lt')
            ->leftJoin('gamintojai', 'darbai.gamintojas', '=', 'gamintojai.id')
            ->select('darbai.*', 'gamintojai.img')->when($parameter, function ($query) use ($id) {
                return $query->where('cat', $id);
            })->get();

        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s1_".$product->foto;
            $product->manufactImage = url('/')."/storage/images/gamintojai/s3_".$product->img;
        }
        return response()->json($products);
    }

    public function getFilteredProducts($id)
    {
        $products = DB::table('darbai')->orderBy('pavadinimas_lt')
            ->leftJoin('gamintojai', 'darbai.gamintojas', '=', 'gamintojai.id')
            ->select('darbai.*', 'gamintojai.img')->where('cat', $id)->get();

        foreach ($products as $product)
        {
            $product->image = url('/')."/storage/images/katalogas/s1_".$product->foto;
            $product->manufactImage = url('/')."/storage/images/gamintojai/s3_".$product->img;
        }
        return response()->json($products);
    }

    public function getProduct($id)
    {
        $product = DB::table('darbai')->where('darbai.id', $id)
            ->join('gamintojai', 'darbai.gamintojas', '=', 'gamintojai.id')
            ->select('darbai.*', 'gamintojai.img')->first();

        $product->manufactImage = url('/')."/storage/images/gamintojai/s3_".$product->img;

        return response()->json($product);
    }
}
