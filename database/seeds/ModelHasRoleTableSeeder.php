<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Administrator;


class ModelHasRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrators=Administrator::all();
        foreach ($administrators as $admin)
        {
            $role=Role::where('name',$admin->user)->first();
            if(!empty($role))
            {
                $alreadyExists=DB::table('model_has_roles')
                    ->where('role_id',$role->id)
                    ->where('model_id',$admin->id)->first();
                if(empty($alreadyExists))
                {
                    $admin->assignRole($role);
                }

            }

        }



    }

}
