<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldModules=DB::table('adm_teises')->distinct('module')->get();
        foreach ($oldModules as $oldModule)
        {
            $alreadyExists=Permission::where('name',$oldModule->module)->first();
            if(empty($alreadyExists))
            {
                $modulePermision = Permission::create(['guard_name' => 'admin', 'name' => $oldModule->module]);

            }
        }
    }
}
