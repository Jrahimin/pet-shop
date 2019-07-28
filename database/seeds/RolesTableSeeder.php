<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldRoles=DB::table('adm_teises')->distinct('user')->get();
        foreach ($oldRoles as $oldRole)
        {
            $alreadyExists=Role::where('name',$oldRole->user)->first();
            if(empty($alreadyExists))
            {
                $role = Role::create(['guard_name' => 'admin', 'name' => $oldRole->user]);

            }
        }
        $adminRole=Role::where('name','admin')->first();
        if(empty($adminRole))
        {
            Role::create(['guard_name' => 'admin', 'name' => 'admin']);
        }

    }
}
