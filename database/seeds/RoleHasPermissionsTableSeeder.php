<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleHasPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldRolesWithPermision=DB::table('adm_teises')->get();
        $roleAdmin=Role::where('name','admin')->first();
        $permissions=Permission::all();
        $alreadyExistsAdmin=DB::table('role_has_permissions')
            ->where('role_id',$roleAdmin->id)->get();
        if($alreadyExistsAdmin->isEmpty())
        {
            foreach ($permissions as $onePermission)
            {
                $roleAdmin->givePermissionTo($onePermission->name);
            }
        }
        foreach ($oldRolesWithPermision as $oldRolePermission)
        {
            $permission=Permission::where('name',$oldRolePermission->module)->first();
            $role=Role::where('name',$oldRolePermission->user)->first();

            if(!empty($permission) && !empty($role))
            {
                $alreadyExists=DB::table('role_has_permissions')
                    ->where('permission_id',$permission->id)
                    ->where('role_id',$role->id)->first();
                if(empty($alreadyExists))
                {
                    $role->givePermissionTo($permission->name);
                }

            }
        }


    }
}
