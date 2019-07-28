<?php

namespace App\Http\Controllers\Admin;

use App\Libraries\DPDFunctions;
use App\Libraries\OmnivaFunctions;
use App\Model\Contact;
use App\Model\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShipmentController extends Controller
{
    public function createShipment(Request $request)
    {
        if($request->shipment_type == "venipak")
        {
            $request['username']= env('DPD_USER') ;
            $request['password']= env('DPD_PASSWORD');

            $order = Order::find($request->order_id);
            $request['name1'] = $order->name;
            $request['street'] = $order->delivery_address;
            $request['city'] = $order->delivery_city ;
            $request['country'] = 'LT' ;

            preg_match_all('!\d+!', $order->delivery_zip_code, $zip);
            $request['pcode'] = $zip[0][0];
            $request['phone'] = $order->phone;
            $request['weight'] = $order->totalweight;
            if($order->payondel ==1)
            {
                  $request['parcel_type'] = 'D-B2C-COD';
                  $request['cod_amount']  = $order->final_sum;
            }
            else{
                $request['parcel_type'] = 'D-B2C';
            }


            $dpd = new DPDFunctions();
            $result = $dpd->createShipment($request);

            if($result['errlog']=='')
            {
                if(gettype($result['pl_number'])== 'array')
                {
                    $order->parcel_ids = implode(",",$result['pl_number']);
                    $order->shipped = true ;
                    $order->save();
                    return ;
                }
                else{

                    $order->parcel_ids =$result['pl_number'] ;
                    $order->shipped = true ;
                    $order->save();
                    return ;
                }

            }
            else{


                    return response()->json(['message'=>$result['errlog']]);

                }

            }


        else{

             $order = Order::find($request->order_id);

             $request['terminal_id'] = $order->terminal ;
             $request['pickupMethod'] = $order->pickup_method ;
             $terminal = $this->getTerminalAddress($order->terminal);

             $request['shipping_country'] = $terminal['A0_NAME'];
             $request['shipping_postcode'] = $order->terminal;
             $request['shipping_city'] = $terminal['A1_NAME'] ;
             $request['shipping_address_1'] = $terminal['A2_NAME'].','.$terminal['A3_NAME'].','.$terminal['A4_NAME'].','.$terminal['A5_NAME'].','.$terminal['A6_NAME'].','.$terminal['A7_NAME'].','.$terminal['A8_NAME'] ;
             $request['shipping_first_name'] = $order->name;
             $request['shipping_last_name'] = $order->surname ;
             $request['billing_phone'] = $order->phone ;

             $contact = Contact::where('active',1)->first();

             $request['shop_countrycode'] = 'LT' ;
             $request['shop_name'] = $contact->title ;
             $request['shop_phone'] = $contact->telefonas ;
             $request['shop_address'] = $contact->adresas;
             //must be added from database
             $request['shop_city'] = 'Vilnius';
             $request['shop_postcode'] ='03117' ;
             $omniva =new OmnivaFunctions();
            //  return response()->json($omniva->get_tracking_number($request)) ;


            $response1 = $omniva->get_tracking_number($request);

            if($response1['status']===true)
            {
                $result = $omniva->getShipmentLabels($response1['barcodes'],$request->order_id);
                $order->shipped = true ;
                $order->save();
                return ;
            }
            else{
                return response()->json(['message'=>$response1['msg']]);
            }






        }

    }


    public function showParcelLabel($id, Request $request)
    {
        $request['username']= env('DPD_USER') ;
        $request['password']= env('DPD_PASSWORD');
        $order = Order::find($id);
        if($order->delivery_type =='venipak')
        {

            if(strpos($order->parcel_ids,',')!==false)
            {
                $parcels = str_replace(',','|',$order->parcel_ids);
                $request['parcels'] = $parcels ;
            }
            else
            {
                $request['parcels'] = $order->parcel_ids;
            }
            $request['printType'] = 'pdf';
            $request['printFormat'] = 'A4';


            $dpd = new DPDFunctions();
            $result = $dpd->createParcelLabel($request);

            header('Content-type: application/pdf');
            //header('Content-Disposition: inline; filename="' . $result . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            //echo file_get_contents(public_path('/').'/'.$result);
            echo $result;
        }

        elseif ($order->delivery_type =='omniva')
        {
            $filename = $order->id.".pdf";
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            echo file_get_contents(public_path('/').'/'.$filename);
        }



    }

    public function closingManifestIndex()
    {
        return view('admin.closing_manifest');
    }
    public function closeManifest($date,Request $request)
    {
        $request['username']= env('DPD_USER') ;
        $request['password']= env('DPD_PASSWORD');
        $request['date'] = $date ;
        $dpd = new DPDFunctions();
        $result = $dpd->createClosingManifest($request);
        if(base64_encode(base64_decode($result)) === $result)
        {

            $pdf_decoded = base64_decode($result);
            $filename = date('Y-m-d').'_'.time().'.pdf';
            //Write data back to pdf file
            $pdf = fopen ($filename,'w');
            fwrite ($pdf,$pdf_decoded);
            //close output file
            fclose ($pdf);
        }
        else{
            return $result;
       }


        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
      echo file_get_contents(public_path('/').'/'.$filename);
        //echo $pdf_decoded;
    }

    public function test(Request $request)
    {

            $omniva =new OmnivaFunctions();
           //  return response()->json($omniva->get_tracking_number($request)) ;

           $response1 = $omniva->get_tracking_number($request);
            dd($omniva->getShipmentLabels($response1['barcodes']));

         /*return response()->json($omniva->getTerminals());*/

    }

    public function getShipmentType($id)
    {
        $order = Order::find($id);
        $shipmentType = $order->delivery_type ;
        return response()->json($shipmentType);
    }

    private function  getTerminalAddress($terminal_id)
    {
        $terminals_json_file_dir = public_path() .'/'. "locations.json";
        $terminals_file = fopen($terminals_json_file_dir, "r");
        $allTerminals = fread($terminals_file, filesize($terminals_json_file_dir) + 10);
        fclose($terminals_file);
        $allTerminals = json_decode($allTerminals, true);
        $terminals = array();
        foreach ($allTerminals as $terminal)
        {
            if($terminal['ZIP']==$terminal_id)
            {
               return $terminal;
            }
        }

    }


}
