<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
    public function getUsers()
    {
     $users=User::orderBy('id','asc')->paginate(20);
     foreach ($users as $user)
     {
         $user->regdate=$user->regdate>0?date('Y-m-d',$user->regdate):"-";
     }

     return response()->json($users);
    }

    public function getUserSelectList()
    {
        $users=User::orderBy('id','asc')->get();
        $userList = [];
        foreach ($users as $user)
        {
            $userList[] = array(
                "id"=>$user->id,
                "label"=>$user->name,
            );
        }

        return response()->json($userList);
    }

    public function getAllProducts()
    {
        $products = DB::table('darbai')->orderBy('pavadinimas_lt')->get();
        return response()->json($products);
    }

    public function filterUsers($keywords)
    {
        $query=DB::table('users');
       // return response()->json($keywords);
        if(!empty($keywords))
        {
            $query=$query->where(function ($query1) use ($keywords)
            {
                $query1 ->where('surname','like','%'.$keywords.'%')
                    ->orWhere('email','like','%'.$keywords.'%')
                    ->orWhere('name','like','%'.$keywords.'%')
                    ->orWhere('city','like','%'.$keywords.'%')
                    ->orWhere('address','like','%'.$keywords.'%');
            });
        }
        $users=$query->orderBy('id','desc')->paginate(20);
        foreach ($users as $user)
        {
            $user->regdate=$user->regdate>0?date('Y-m-d',$user->regdate):"-";
        }

        return response()->json($users);
    }

    public function deleteUser($id)
    {
        $user=User::find($id);
        $user->delete();
    }
    public function deleteManyUsers(Request $request)
    {
        $users=User::whereIn('id',$request->userArray)->get();
        foreach ($users as $user)
        {
            $user->delete();
        }
    }

    public function getUserDetail($id)
    {
        $user=User::find($id);
        $user->regdate=$user->regdate>0?date('Y-m-d',$user->regdate):"-";
        $user->discount_from=$user->discount_from>0?date('Y-m-d',$user->discount_from):"-";
        $user->discount_to=$user->discount_to>0?date('Y-m-d',$user->discount_to):"-";
        $user->has_discount = $user->has_discount>0 ? 1 :0 ;
        if($user->discount_items==";;")
            $user->discount_items = array();
        else
            $user->discount_items = explode(';',$user->discount_items);

        $user->pets=DB::table('users_pets')->where('userid',$id)->orderBy('title','asc')->get();
        foreach ($user->pets as $pet)
        {
            $pet->birthday=$pet->birthday>0?date('Y-m-d',$pet->birthday):"-";
        }
        return response()->json($user);
    }

    public function editUser(Request $request)
    {
        $rules = [
            'name'=>'required',
            'surname'=>'required',
            'email'=>'required',
            'city'=>'required',
            'address'=>'required',
            'zip_code'=>'required',
            'has_discount'=>'required',
            'id'=>'required',


        ];

        if($request->change_password)
        {
            $rules['password'] ='required';
        }

        if($request->has_discount==1)
        {
            $rules['discount_from'] ='required|date';
            $rules['discount_to'] ='required|date';
            $rules['discount_percent'] ='required';
            $rules['discount_type'] ='required';
        }
        if( $request->discount_type == 2)
        {
            $rules['discount_cat'] ='required';
        }
        else if( $request->discount_type == 3)
        {
            $rules['discount_items'] ='required';
        }

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

       // return response()->json($request->all());
        $user=User::find($request->id);

        list($y, $m, $d) = explode("-", $request['regdate']);
        $regdate = mktime(0, 0, 0, $m, $d, $y);
        $request['regdate']=$regdate;
        if($request->has_discount==1)
        {
            list($y, $m, $d) = explode("-", $request['discount_from']);
            $discount_from = mktime(0, 0, 0, $m, $d, $y);
            $request['discount_from']=$discount_from;

            list($y, $m, $d) = explode("-", $request['discount_to']);
            $discount_to = mktime(0, 0, 0, $m, $d, $y);
            $request['discount_to']=$discount_to;

            if(!empty($request->discount_items))
                $request['discount_items'] = implode(';',$request->discount_items);
            else
                $request['discount_items'] = ";;" ;
        }
        else{
            $request['discount_from'] = null;
            $request['discount_to'] = null ;
            $request['discount_type'] = null;
            $request['discount_percent'] = null;
            $request['discount_items'] = null;
            $request['discount_cat'] =null ;
        }


        if($request->change_password)
        {
            $request['password'] = bcrypt($request->password);
        }
        else
        {
            unset($request['password']);
            unset($request['change_password']);
        }

        $user->update($request->all());
    }

}
