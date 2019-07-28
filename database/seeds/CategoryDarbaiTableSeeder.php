<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryDarbaiTableSeeder extends Seeder
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
            $alreadyExists = DB::table('category_darbai')
                            ->where('category_id',$product->cat)
                            ->where('darbai_id',$product->id)->first();
            if(empty($alreadyExists) && $product->cat != null)
            {
                DB::table('category_darbai')
                    ->insert(['category_id'=>$product->cat,
                        'darbai_id'=>$product->id]);
            }


        }
    }
}
