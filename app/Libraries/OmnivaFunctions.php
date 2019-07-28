<?php
namespace App\Libraries ;

use Illuminate\Http\Request;

class OmnivaFunctions{

    public function get_tracking_number(Request $request)
    {
        $order = $request->order_id;

        $send_method = $request->sendMethod ;
        $pickup_method = $request->pickupMethod;
        $service = "";

        switch ($pickup_method . ' ' . $send_method) {
            case 'c pt':
                $service = "PU";
                break;

            case 'c c':
                $service = "QH";
                break;

            case 'pt c':
                $service = "PK";
                break;

            case 'pt pt':
                $service = "PA";
                break;

            default:
                $service = "";
                break;
        }

       /* $is_cod = false;
        if ($request->paymentMethod== "cod")
            $is_cod = true;*/
        $parcel_terminal = "";
        if ($send_method == "pt") $parcel_terminal = 'offloadPostcode="' . $request->terminal_id . '" ';
        $additionalService = '';
        if ($service == "PA" || $service == "PU") $additionalService.= '<option code="ST" />';
        //if ($is_cod) $additionalService.= '<option code="BP" />';
        if (!empty($additionalService)) {
            $additionalService = '<add_service>' . $additionalService . '</add_service>';
        }

        $client_address = '<address postcode="' . $request->shipping_postcode. '" ' . $parcel_terminal . ' deliverypoint="' . $request->shipping_city. '" country="' . $request->shipping_country . '" street="' . $request->shipping_address_1 . '" />';
        $phones = '';
        $mobile = $request->billing_phone;
        $phones.= '<mobile>' . $mobile . '</mobile>';
        $pickStart = /*$request->pick_up_start ? $request->pick_up_start:*/ '8:00';
        $pickFinish = /*$request->pick_up_end ? $request->pick_up_end : */'17:00';
        $pickDay = date('Y-m-d');
        if (time() > strtotime($pickDay . ' ' . $pickFinish)) $pickDay = date('Y-m-d', strtotime($pickDay . "+1 days"));
        $shop_country_iso = $request->shop_countrycode;
        $xmlRequest = '
          <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://service.core.epmx.application.eestipost.ee/xsd">
             <soapenv:Header/>
             <soapenv:Body>
                <xsd:businessToClientMsgRequest>
                   <partner>' . env('OMNIVA_USER'). '</partner>
                   <interchange msg_type="info11">
                      <header file_id="' . Date('YmdHms') . '" sender_cd="' . env('OMNIVA_USER') . '" >                
                      </header>
                      <item_list>
                        ';
        // for ($i = 0; $i < $orderInfo['packs']; $i++):
        $xmlRequest.= '
                         <item service="' . $service . '" >
                            ' . $additionalService . '
                            <measures weight="' . $request->cart_weight . '" />
                           
                            <receiverAddressee >
                               <person_name>' . $request->shipping_first_name . ' ' . $request->shipping_last_name . '</person_name>
                              ' . $phones . '
                              ' . $client_address . '
                            </receiverAddressee>
                            <!--Optional:-->
                            <returnAddressee>
                              <person_name>' . $request->shop_name. '</person_name>
                              <!--Optional:-->
                              <phone>' . $request->shop_phone. '</phone>
                               <address postcode="' . $request->shop_postcode . '" deliverypoint="' . $request->shop_city . '" country="' . $shop_country_iso . '" street="' . $request->shop_address . '" />
                            
                            </returnAddressee>
                         </item>';
        //endfor;
        $xmlRequest.= '
                      </item_list>
                   </interchange>
                </xsd:businessToClientMsgRequest>
             </soapenv:Body>
          </soapenv:Envelope>';

        return $this->api_request($xmlRequest);
    }

    private function cod(Request $request)
    {
        $company = $request->company;
        $bank_account = $request->bank_account;
        if ($request->paymentMethod== "cod") {
            return '<monetary_values>
              <cod_receiver>'. $company .'</cod_receiver>
              <values code="item_value" amount="'. $request->totalAmount .'"/>
            </monetary_values>
            <account>'. $bank_account .'</account>
            <reference_number>'. $this->getReferenceNumber($request->id) .'</reference_number>';
        } else {
            return '';
        }
    }

    public function getReferenceNumber($order_number)
    {
        $order_number = (string)$order_number;
        $kaal = array(7,3,1);
        $sl = $st = strlen($order_number);
        $total = 0;
        while($sl > 0 and substr($order_number, --$sl, 1) >='0'){
            $total += substr($order_number, ($st-1)-$sl, 1)*$kaal[($sl%3)];
        }
        $kontrollnr = ((ceil(($total/10))*10)-$total);
        return $order_number.$kontrollnr;
    }

    private function api_request($request)
    {
        $barcodes = array();;
        $errors = array();
        $url = env('OMNIVA_URL'). '/epmx/services/messagesService.wsdl';
        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-length: " . strlen($request) ,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERPWD, env('OMNIVA_USER') . ":" . env('OMNIVA_PASS'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $xmlResponse = curl_exec($ch);

       // return $xmlResponse;

        if ($xmlResponse === false) {
            $errors[] = curl_error($ch);
        }
        else {
            $errorTitle = '';
            if (strlen(trim($xmlResponse)) > 0) {
                $xmlResponse = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $xmlResponse);
                $xml = simplexml_load_string($xmlResponse);
                if (!is_object($xml)) {
                    $errors[] = __('Response is in the wrong format','omnivalt');
                }

                if (is_object($xml) && is_object($xml->Body->businessToClientMsgResponse->faultyPacketInfo->barcodeInfo)) {
                    foreach($xml->Body->businessToClientMsgResponse->faultyPacketInfo->barcodeInfo as $data) {
                        $errors[] = $data->clientItemId . ' - ' . $data->barcode . ' - ' . $data->message;
                    }
                }

                if (empty($errors)) {
                    if (is_object($xml) && is_object($xml->Body->businessToClientMsgResponse->savedPacketInfo->barcodeInfo)) {
                        foreach($xml->Body->businessToClientMsgResponse->savedPacketInfo->barcodeInfo as $data) {
                            $barcodes[] = (string)$data->barcode;
                        }
                    }
                }
            }
        }

        // }

        if (!empty($errors)) {
            return array(
                'status' => false,
                'msg' => implode('. ', $errors)
            );
        }
        else {
            if (!empty($barcodes)) return array(
                'status' => true,
                'barcodes' => $barcodes
            );
            $errors[] = __('No saved barcodes received','omnivalt');
            return array(
                'status' => false,
                'msg' => implode('. ', $errors)
            );
        }
    }

    function get_terminal_address($terminal_id)
    {
        $terminals_json_file_dir = public_path() .'/'. "locations.json";
        $terminals_file = fopen($terminals_json_file_dir, "r");
        $terminals = fread($terminals_file, filesize($terminals_json_file_dir) + 10);
        fclose($terminals_file);
        $terminals = json_decode($terminals, true);
        $parcel_terminals = '';
        if (is_array($terminals) && $terminal_id) {
            foreach($terminals as $terminal) {
                if ($terminal['ZIP'] == $terminal_id){
                    return $terminal['NAME'].', '.$terminal['A2_NAME'].', '.$terminal['A0_NAME'];
                }
            }
        }
        return '';
    }

    public function getShipmentLabels($barcodes, $order_id = 0)
    {
        $errors = array();
        $barcodeXML = '';
        foreach($barcodes as $barcode) {
            $barcodeXML.= '<barcode>' . $barcode . '</barcode>';
        }

        $xmlRequest = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://service.core.epmx.application.eestipost.ee/xsd">
           <soapenv:Header/>
           <soapenv:Body>
              <xsd:addrcardMsgRequest>
                 <partner>' . env('OMNIVA_USER') . '</partner>
                 <sendAddressCardTo>response</sendAddressCardTo>
                 <barcodes>
                    ' . $barcodeXML . '
                 </barcodes>
              </xsd:addrcardMsgRequest>
           </soapenv:Body>
        </soapenv:Envelope>';

        // echo $xmlRequest;

        try {
            $url = env('OMNIVA_URL') . '/epmx/services/messagesService.wsdl';
            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "Content-length: " . strlen($xmlRequest) ,
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERPWD, env('OMNIVA_USER') . ":" . env('OMNIVA_PASS'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $xmlResponse = curl_exec($ch);
            $debugData['result'] = $xmlResponse;
        }

        catch(Exception $e) {
            $errors[] = $e->getMessage() . ' ' . $e->getCode();
            $xmlResponse = '';
        }

        $xmlResponse = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $xmlResponse);
        $xml = simplexml_load_string($xmlResponse);
        if (!is_object($xml)) {
            $errors[] = __('Response is in the wrong format', 'omnivalt');
        }

        if (is_object($xml) && is_object($xml->Body->addrcardMsgResponse->successAddressCards->addressCardData->barcode)) {
            $shippingLabelContent = (string)$xml->Body->addrcardMsgResponse->successAddressCards->addressCardData->fileData;
            file_put_contents(public_path() . "/" . $order_id . '.pdf', base64_decode($shippingLabelContent));
        }
        else {
            $errors[] = 'No label received from webservice';
        }

        if (!empty($errors)) {
            return array(
                'status' => false,
                'msg' => implode('. ', $errors)
            );
        }
        else {
            if (!empty($barcodes)) return array(
                'status' => true
            );
            $errors[] = __('No saved barcodes received', 'omnivalt');
            return array(
                'status' => false,
                'msg' => implode('. ', $errors)
            );
        }
    }

    function do_daily_update()
    {
        $url = 'https://www.omniva.ee/locations.json';
        $fp = fopen(public_path().'/' . "locations.json", "w");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        fclose($fp);
    }



}