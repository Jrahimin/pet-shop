<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagePostionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = DB::table('darbai')->get();
        foreach ($products as $product)
        {
            $packages = DB::table('pakuotes')->where('preke',$product->id)->orderBy('id','asc')->get();
            $position = 0 ;
            foreach ($packages as $package)
            {
                $position++;
                DB::table('pakuotes')->where('id',$package->id)->update(['position'=>$position]);
            }
        }

    }
}
