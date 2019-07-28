<?php

namespace App\Http\Controllers\FrontEnd;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return view('frontend.user.index');
    }

    public function orderHistoryIndex()
    {
        return view('frontend.user.order_history');
    }

    public function getUser($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    public function getUserOrderDetail($id)
    {
        $order=DB::table('orders')->where('id',$id)->first();
        $order->discounts = 0 ;
        if($order->items_sum> $order->final_sum)
        {
            $order->discounts = $order->items_sum - $order->final_sum ;
            $order->discounts = number_format($order->discounts,2,'.','');
        }
        
        $orderItems=DB::table('order_items')->where('order_id',$order->id)->get();
        $orderItemIds=array();
        foreach ($orderItems as $orderItem)
        {
            array_push($orderItemIds,$orderItem->item_id);
        }

        $productsDetail=DB::table('darbai')->whereIn('id',$orderItemIds)->get();
        foreach ($orderItems as $orderItem)
        {
            foreach ($productsDetail as $productDetail)
                if($orderItem->item_id==$productDetail->id)
                {
                    $orderItem->detail=$productDetail;
                }
        }
        $order->orderItems=$orderItems;
        return response()->json($order);
    }

    public function getUserOrders()
    {
        $user = Auth::user();
        $total = 0;
        $orders = DB::table('orders')->where('userid',$user->id)->get() ;
        foreach ($orders as $order)
        {
            $order->date=$order->date>0?date('Y-m-d h.i',$order->date):"-";
            $total += $order->final_sum ;

        }
        $total = number_format($total,2,'.','');
        return response()->json(['orders'=>$orders,'total'=>$total]);
    }

    public function editUser(Request $request)
    {
        $rules = [
            'name'=>'required',
            'surname'=>'required',
            'phone'=>'required',
            'city'=>'required',
            'zip_code'=>'required',
            'address'=>'required',
        ];

        if($request->change_pass)
            $rules['password'] = 'required|confirmed';

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $user = User::find($request->id);

        if($request->password)
            $request['password'] = bcrypt($request->password);

        $user->update($request->all());

        return response()->json("user updated");
    }


    public function petIndex()
    {
        return view('frontend.user.pet');
    }

    public function getPetInfo($id)
    {
       $pets = DB::table('users_pets')->where('userid',$id)->get();
       foreach ($pets as $pet)
       {
           $pet->birthday = $pet->birthday>0 ?  date('Y-m-d',$pet->birthday):"-";
       }
       return response()->json($pets);
    }
    public function savePets(Request $request)
    {
        $pets = $request->pets;
        $petIds = array();

        // add updated pets
        foreach ($pets as $pet)
        {
            if(empty($pet['title']))
                return response()->json(["success" => false,
                    "message" => ["title field is needed"]], 200);

            if(empty($pet['species']))
                return response()->json(["success" => false,
                    "message" => ["species field is needed"]], 200);

            if(empty($pet['birthday']))
                return response()->json(["success" => false,
                    "message" => ["birthday field is needed"]], 200);

            $pet['userid'] = $request->userId;
            $petExists = DB::table('users_pets')->where('id',$pet['id'])
                                                     ->first();
            if(!empty($petExists))
            {
                list($y, $m, $d) = explode("-", $pet['birthday']);
                $pet['birthday'] = mktime(0, 0, 0, $m, $d, $y);

                DB::table('users_pets')->where('id',$pet['id'])->update($pet);

                $petIds[] = $pet['id'];
            }
            else{
                list($y, $m, $d) = explode("-", $pet['birthday']);
                $pet['birthday'] = mktime(0, 0, 0, $m, $d, $y);
                $insertedId = DB::table('users_pets')->insertGetId($pet);
                $petIds[] = $insertedId;
            }

        }


        // delete pets not in pet list
        if(!empty($pets))
        {
            $userPets = DB::table('users_pets')->where('userid', $request->userId)->get();
            foreach ($userPets as $userPet)
            {
                if(!in_array($userPet->id, $petIds)){
                    DB::table('users_pets')->where('id', $userPet->id)->delete();
                }
            }
        }
        else
        {
            DB::table('users_pets')->where('userid', $request->userId)->delete();
        }

        return response()->json("user pets updated");
    }
}
