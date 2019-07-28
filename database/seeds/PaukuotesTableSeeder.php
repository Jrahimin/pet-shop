<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PaukuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = DB::table('darbai')->where('haspacks',0)
                                             ->where('price','<>',0.00)
                                             ->where('svoris','<>',0.00)
                                             ->get() ;
        foreach ($products as $product)
        {
            $alreadyExists = DB::table('pakuotes')
                            ->where('preke',$product->id)
                            ->where('pavadinimas','default')
                            ->first();

            if(empty($alreadyExists))
            {
                DB::table('pakuotes')->insert(['preke'=>$product->id,
                    'pavadinimas'=>'default',
                    'kaina'=>$product->price,
                    'svoris'=>$product->svoris,
                    'akcija'=>1,
                    'sena_kaina'=>0.00]);
            }

        }
    }
}
