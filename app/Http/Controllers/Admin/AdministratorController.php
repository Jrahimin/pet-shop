<?php

namespace App\Http\Controllers\Admin;

use App\Administrator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AdministratorController extends Controller
{
  public function index()
  {
      return view('admin.administrators.index');
  }

  public function getAdmin()
  {
     $admins=Administrator::orderBy('user','asc')->get();
     foreach ($admins as $admin)
     {
         $admin->active=$admin->active>0?"Yes":"No";
         $users = $admin->getRoleNames();
         foreach ($users as $user)
         {
             $admin->user = $user;
         }
     }
     return response()->json($admins);
  }

  public function deletetAdmin($id)
  {
      $admin=Administrator::find($id);

      $roles=$admin->getRoleNames();
      foreach ($roles as $role)
      {
          $admin->removeRole($role);
      }
      $admin->delete();
  }

  public function getPermissions()
  {
     $permissions=Permission::all();
     return response()->json($permissions);
  }
  public function getRoles()
  {
      $roles=Role::all();
      return response()->json($roles);
  }
  public function saveAdmin(Request $request)
  {
      $rules = [
          'name'=>'required',
          'surname'=>'required',
          'email'=>'required',
          'telephone'=>'required',
          'active'=>'required',
          'password'=>'required',
          'role'=>'required',

      ];
      $validation = Validator::make($request->all(), $rules);
      if ($validation->fails()) {
          return response()->json(["success" => false,
              "message" => $validation->errors()->all()], 200);
      }
      $request->password=bcrypt($request->password);
      $request['user']=strtolower($request->name);
      $admin=Administrator::create($request->all());
      $admin->assignRole($request->role);
      return response()->json($admin);
  }
  public function getPermissionsForRole($role)
  {
      $role = Role::where('name',$role)->first();
      $rolesWithPermissions=DB::table('role_has_permissions')->where('role_id',$role->id)->get();
      //return response()->json($rolesWithPermissions);
      $permissionsIDArray = array();
      foreach ($rolesWithPermissions as $rolesWithPermission)
      {
          $permissionsIDArray[]=$rolesWithPermission->permission_id;
      }
      $permissions=Permission::whereIn('id',$permissionsIDArray)->get();
      return response()->json($permissions);
  }

    public function getAdminByID($id)
    {
        $admin = Administrator::find($id);
        $role = DB::table('model_has_roles')->where('model_id',$admin->id)->first();
        if(!empty($role))
        {
          $role = Role::where('id',$role->role_id)->first();

          $admin->role = $role->name;

        }

        return response()->json($admin);
     }

     public function editAdmin(Request $request)
     {

         $rules = [
             'id'=>'required',
             'name'=>'required',
             'surname'=>'required',
             'email'=>'required',
             'active'=>'required',
             'change_password'=>'required',
             'role'=>'required',

         ];

         if($request->change_password)
         {
             $rules['password'] = 'required';
         }
         $validation = Validator::make($request->all(), $rules);
         if ($validation->fails()) {
             return response()->json(["success" => false,
                 "message" => $validation->errors()->all()], 200);
         }

         $admin = Administrator::find($request->id);

         //return response()->json($request->password);
         if(!empty($request->password))
         {
             $request['password'] = bcrypt($request->password);
         }
         $admin->update($request->all());



         $presentRole = $admin->getRoleNames()->first();
         if($presentRole != $request->role)
         {
             $admin->removeRole($presentRole);
             $admin->assignRole($request->role);
         }

     }
     public function saveRole(Request $request)
     {
         $rules = [

             'name'=>'required',
             'permissions'=>'required',

         ];

         $validation = Validator::make($request->all(), $rules);
         if ($validation->fails()) {
             return response()->json(["success" => false,
                 "message" => $validation->errors()->all()], 200);
         }

        $role = Role::create(['guard_name' => 'admin', 'name' => $request->name]);
         foreach ($request->permissions as $permission)
         {
             $role->givePermissionTo($permission);
         }

     }
     public function deletetRole($id)
     {

         $role=Role::find($id);
         $admins=Administrator::role($role->name)->get();
         foreach ($admins as $admin)
         {
             $admin->removeRole($role);
         }
         $rolesWithPermissions=DB::table('role_has_permissions')->where('role_id',$role->id)->get();
         //return response()->json($rolesWithPermissions);
         $permissionsIDArray = array();
         foreach ($rolesWithPermissions as $rolesWithPermission)
         {
             $permissionsIDArray[]=$rolesWithPermission->permission_id;
         }
         $permissions=Permission::whereIn('id',$permissionsIDArray)->get();
         foreach ($permissions as $permission)
         {
             $role->revokePermissionTo($permission->name);
         }

         $role->delete();
     }

     public function getRole($id)
     {
         $role=Role::find($id);
         $rolesWithPermissions=DB::table('role_has_permissions')->where('role_id',$role->id)->get();
         //return response()->json($rolesWithPermissions);
         $permissionsIDArray = array();
         foreach ($rolesWithPermissions as $rolesWithPermission)
         {
             $permissionsIDArray[]=$rolesWithPermission->permission_id;
         }
         $permissions=Permission::whereIn('id',$permissionsIDArray)->get();
         $permissionNames=array();
         foreach ($permissions as $permission)
         {
             $permissionNames[] = $permission->name;
         }
         $role->permissions= $permissionNames;
         return response()->json($role);
     }

     public function editRole(Request $request)
     {
         $rules = [
             'id'=>'required',
             'name'=>'required',
             'permissions'=>'required',

         ];

         $validation = Validator::make($request->all(), $rules);
         if ($validation->fails()) {
             return response()->json(["success" => false,
                 "message" => $validation->errors()->all()], 200);
         }
         $role = Role::find($request->id);
         $role->name = $request->name;
         $role->save();
         $rolesWithPermissions=DB::table('role_has_permissions')->where('role_id',$role->id)->get();
         //return response()->json($rolesWithPermissions);
         $permissionsIDArray = array();
         foreach ($rolesWithPermissions as $rolesWithPermission)
         {
             $permissionsIDArray[]=$rolesWithPermission->permission_id;
         }
         $permissions=Permission::whereIn('id',$permissionsIDArray)->get();
         foreach ($permissions as $permission)
         {
             $role->revokePermissionTo($permission->name);
         }
         foreach ($request->permissions as $permission)
         {
             $role->givePermissionTo($permission);
         }
     }

}
