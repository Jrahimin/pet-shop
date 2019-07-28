<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user-info';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required',
            'city' =>'required' ,
            'zip_code' => 'required',
            'address' =>'required',

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
         $user =  User::create([
            'name' => $data['name'],
            'surname'=>$data['surname'],
            'phone'=>$data['phone'],
            'city'=>$data['city'],
            'zip_code'=>$data['zip_code'],
            'address'=>$data['address'],
            'regdate'=>time(),
            'email' => $data['email'],
            'active' => 1,
            'password' => Hash::make($data['password']),
        ]);

         if(array_key_exists('petName',$data))
         {
             $totalPets = count($data['petName']);
             $petNames = $data['petName'];
             $breeds = $data['breed'];
             $birthdays = $data['birthday'];

             for($i=0; $i<$totalPets; $i++)
             {
                 list($y, $m, $d) = explode("-", $birthdays[$i]);
                 $birthdays[$i] = mktime(0, 0, 0, $m, $d, $y);

                 DB::table('users_pets')->insert(['title'=>$petNames[$i],
                     'species'=>$breeds[$i],
                     'birthday'=>$birthdays[$i],
                     'userid'=>$user->id]);
             }
         }

         return $user ;


           //return redirect()->route('user_info_index');


    }
}
