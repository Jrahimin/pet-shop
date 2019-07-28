<?php

namespace App\Http\Controllers\Admin;

use App\Libraries\OmnivaFunctions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('admin.purchase.index');
    }

    public function getOrders()
    {
        $orders=DB::table('orders')->where('hidden',0)->orderBy('id','desc')->paginate(20);
        foreach ($orders as $order)
        {
            $order->date=$order->date>0?date('Y-m-d h.i',$order->date):"-";
            $order->paid=$order->paid>0?"Yes":"No";
        }

        return response()->json($orders);
    }

    public function getOrderDetail($id)
    {
        $order=DB::table('orders')->where('id',$id)->first();
        $order->date=$order->date>0?date('Y-m-d h:i',$order->date):"-";
        $order->paid=$order->paid>0?"Yes":"No";
        if($order->delivery_type=="venipak")
        {
            $order->delivery="DPD Courier";

        }
        elseif ($order->delivery_type == "omniva")
        {
            $order->delivery = "OMNIVA Pickup Terminal" ;
            $omniva = new OmnivaFunctions();
            $order->terminalAddress = $omniva->get_terminal_address($order->terminal) ;
        }

        else
        {
            $order->delivery="Store Pickup ";
        }

        $order->paymentMethod =  $order->payondel >0 ? "Paid By Paysera" : "Cash On Delivery" ;

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
    public function deleteOrder($id)
    {
        $order=DB::table('orders')->where('id',$id)->update(['hidden'=>1]);
    }

    public function getYears()
    {
        $years=DB::table('orders')->select('date')->distinct()->get();
        $distinctYears=array();
        foreach ($years as $year)
        {
            $year->date=$year->date>0?date('Y',$year->date):"-";
            if(!in_array($year->date,$distinctYears))
            {
                array_push($distinctYears,$year->date);
            }
        }
        return response()->json($distinctYears);
    }

    public function filterOrders($month,$year,$client)
    {
        $query=DB::table('orders')->where('hidden',0);
        if($client!="null")
        {
            $query=$query->where(function ($query1) use ($client)
            {
                $query1 ->where('surname','like','%'.$client.'%')
                    ->orWhere('email','like','%'.$client.'%')
                    ->orWhere('name','like','%'.$client.'%');
            });
        }


        $orderResults=array();
        if($year!="null")
        {
            $query = $query->whereRaw('YEAR(CAST(FROM_UNIXTIME(date)AS DATE))=?',[$year]);

            /*foreach ($ordersData as $order)
            {
                if(strpos($order->date,$request->year)!== false)
                {
                    $orderResults[] = $order;
                }
            }*/
        }

        $orderResults2=array();
        if($month!="null")
        {
            /*if(empty($orderResults))
            {
                foreach ($ordersData as $order)
                {
                    $month=substr($order->date,5,2);
                    if($month==$request->month)
                    {
                        $orderResults2[] = $order;
                    }
                }
            }
            else{
                foreach ($orderResults as $order)
                {
                    $month=substr($order->date,5,2);
                    if($month==$request->month)
                    {
                        $orderResults2[] = $order;
                    }
                }
            }*/


            $query = $query->whereRaw('DATE_FORMAT(CAST(FROM_UNIXTIME(date) AS DATE), \'%m\')=?',[$month]);

        }
        $ordersData=$query->orderBy('id','desc')->paginate(20);
      //return response()->json($ordersData);

      foreach ($ordersData as $order)
      {
          $order->date=$order->date>0?date('Y-m-d  h.i',$order->date):"-";
          $order->paid=$order->paid>0?"Yes":"No";
      }
       /* else{
            $orderResults2=$orderResults;
        }*/


        /*if(empty($orderResults2))
        {
            $orders['data']=$ordersData;
        }
        else{
            $orders['data']=$orderResults2;
        }*/

        //$orders['data']=$ordersData;
        return response()->json($ordersData);
    }
    public function exportExcel($year,$month,$client)
    {
        $query=DB::table('orders')->where('hidden',0);

         if($client!=="null")
         {
             $query=$query->where(function ($query1) use ($client)
             {
                 $query1 ->where('surname','like','%'.$client.'%')
                     ->orWhere('email','like','%'.$client.'%')
                     ->orWhere('name','like','%'.$client.'%');
             });
         }
        $ordersData=$query->orderBy('id','desc')->get();
        //return response()->json($ordersData);

        foreach ($ordersData as $order)
        {
            $order->date=$order->date>0?date('Y-m-d  h.i',$order->date):"-";
            $order->paid=$order->paid>0?"Yes":"No";
        }


         $orderResults=array();
         if($year!=="null")
         {
             foreach ($ordersData as $order)
             {
                 if(strpos($order->date,$year)!== false)
                 {
                     $orderResults[] = $order;
                 }
             }
         }

         $orderResults2=array();
         if($month!=="null")
         {
             if(empty($orderResults))
             {

                 foreach ($ordersData as $order)
                 {
                     $month1=substr($order->date,5,2);
                     if($month1==$month)
                     {
                         $orderResults2[] = $order;
                     }
                 }

             }
             else{
                 foreach ($orderResults as $order)
                 {
                     $month=substr($order->date,5,2);
                     if($month==$month)
                     {
                         $orderResults2[] = $order;
                     }
                 }
             }

         }
         else{
             $orderResults2=$orderResults;
         }


         if(empty($orderResults2))
         {
             $orderResults2=$ordersData;
         }


        $data = array();
        foreach ($orderResults2 as $order)
        {
            $data[] = array(
                $order->date,
                $order->name." ".$order->surname,
                $order->email,
                $order->address,
                $order->zip_code,
                $order->city,
                $order->company_title,
                $order->company_code,
                $order->company_vatcode
            );
        }

        $currentDate = date("Y-m-d");
        Excel::create($currentDate, function($excel) use($data){
            $excel->sheet('raw_report', function($sheet) use($data){
                $sheet->fromArray($data);
                $sheet->row(1, array(
                    'Date',
                    'Name','Email','Address','Zip Code','City','Company Name','Company Code','Company VatCode'
                ));
            });
        })->download('csv');


    }


}
