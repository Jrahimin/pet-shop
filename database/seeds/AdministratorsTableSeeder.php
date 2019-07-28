<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Administrator;
class AdministratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldUsers=DB::table('administratoriai')->get();
        $data=array();
        foreach ($oldUsers as $oldUser)
        {
            $userExists=Administrator::where('user',$oldUser->user)
                ->where('name',$oldUser->vardas)
                ->where('surname',$oldUser->pavarde)
                ->where('email',$oldUser->email)->first();
            if(empty($userExists))
            {
                $password=str_random(6);
                $eachUser=array($oldUser->email,$password);

                Administrator::create(['user'=>$oldUser->user,
                    'name'=>$oldUser->vardas,
                    'surname'=>$oldUser->pavarde,
                    'email'=>$oldUser->email,
                    'password'=>bcrypt($password),
                    'telephone'=>$oldUser->telefonas,
                    'active'=>$oldUser->aktyvus,
                    'status'=>$oldUser->status
                ]);
                array_push($data,$eachUser);
            }
        }
        if(!empty($data))
        {
            $fp = fopen( public_path()."/"."administrators_yzipet".time().".csv", 'w');
            foreach ( $data as $line ) {
                fputcsv($fp, $line);
            }
            fclose($fp);
        }

    }
}
