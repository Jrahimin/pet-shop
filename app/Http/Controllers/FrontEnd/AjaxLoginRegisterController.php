<?php

namespace App\Http\Controllers\FrontEnd;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AjaxLoginRegisterController extends Controller
{
    public function login(Request $request)
    {
        if( Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
        {
            return response()->json(['message'=>'logged in','user'=>Auth::user()->id]);
        }

        else{
            return response()->json(['message'=>[__('login.errorMessage')]]);
        }
    }
    public  function register(Request $request)
    {


        $rules = [
            'name'=>'required',
            'surname'=>'required',
            'phone'=>'required',
            'city'=>'required',
            'zip_code'=>'required',
            'address'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'confirmPassword' =>'required|same:password',
            'iscompany'=>'required'
        ];
        if($request->iscompany==1)
        {
            $rules['company_title'] ='required';
            $rules['company_code'] ='required';
            $rules['company_vatcode'] ='required';

        }

        $messages = [
            'name.required'=>__('register.name.required'),
            'surname.required'=>__('register.surname.required'),
            'phone.required'=>__('register.phone.required'),
            'city.required'=>__('register.city.required'),
            'zip_code.required'=>__('register.zip_code.required'),
            'address.required'=>__('register.address.required'),
            'email.required'=>__('register.email.required'),
            'password.required'=>__('register.password.required'),
            'iscompany.required'=>__('register.iscompany.required'),
            'company_title.required' =>__('register.company_title.required'),
            'company_code.required' =>__('register.company_code.required'),
            'company_vatcode.required' =>__('register.company_vatcode.required'),
            'confirmPassword.required'=>__('register.confirmPass.required'),
            'confirmPassword.same' => __('register.confirmPass.same')
        ];


        $validation = Validator::make($request->all(), $rules ,$messages);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }
        $user =  User::create([
            'name' => $request['name'],
            'surname'=>$request['surname'],
            'phone'=>$request['phone'],
            'city'=>$request['city'],
            'zip_code'=>$request['zip_code'],
            'address'=>$request['address'],
            'regdate'=>time(),
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'iscompany'=>$request['iscompany'],
            'active' => 1
        ]);

        if($request->iscompany==1)
        {
            $user->update(['company_title'=>$request->company_title,
                           'company_code'=>$request->company_code,
                            'company_vatcode'=>$request->company_vatcode,
                           ]);
        }

        if(!empty($user))
        {
            if( Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
            {
                return response()->json(['message'=>'logged in','user'=>Auth::user()->id]);
            }

            else{
                return response()->json(['message'=>['Some error occurred']]);
            }
        }


    }
}
