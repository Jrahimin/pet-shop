<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagesDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $packages = DB::table('pakuotes')->get();
       foreach ($packages as $package)
       {
           if($package->default == null)
           {
               if($package->pavadinimas == 'default')
               {
                   DB::table('pakuotes')->where('id',$package->id)->update(['default'=>1]);
               }
               else{
                   DB::table('pakuotes')->where('id',$package->id)->update(['default'=>0]);
               }
           }

       }
    }
}
