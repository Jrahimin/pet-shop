<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $redirectTo = '/admin';
    protected $guard = 'admin';

    public function loginPage()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
       if( Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password]))
       {
           return redirect()->route('home');
       }
       elseif (Auth::guard('admin')->attempt(['name'=>$request->email,'password'=>$request->password]))
       {
           return redirect()->route('home');
       }
       else{
           return redirect()->route('admin_login_index')->with(['message'=>'Authentication Failed']);
       }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login_index');
    }
}
