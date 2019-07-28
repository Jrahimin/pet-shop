<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;
use App\User;
class UserTablePasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=User::all();
        $data=array();
        foreach ($users as $user)
        {

            if(empty($user->password))
            {
                $password=str_random(6);
                $eachUser=array($user->email,$password);
                $user->password=bcrypt($password);
                $user->save();
                array_push($data,$eachUser);

               /* $content = "your password has been changed to ".$password." . Please use this next time you try to log in .";
                $subject="Password has been updated";
                $recipent=$user->email;

                Mail::send('admin.mail.demo', ['content' => $content], function ($message) use($subject,$recipent)
                {
                    $message->subject($subject);
                    $message->to($recipent);

                });*/


            }
        }


        if(!empty($data))
        {
            $fp = fopen( public_path()."/"."users_yzipet".time().".csv", 'w');
            foreach ( $data as $line ) {
                fputcsv($fp, $line);
            }
            fclose($fp);
        }


    }
}
