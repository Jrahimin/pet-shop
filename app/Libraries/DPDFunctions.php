<?php
namespace App\Libraries;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mockery\Exception;
use Psr\Log\InvalidArgumentException;

class DPDFunctions{
public function createShipment(Request $request)
{
    $client = new Client(['verify' =>false]); //GuzzleHttp\Client
    //$client->setDefaultOption('verify', false);

    $data = [
        'form_params' => [
            'username' => $request->username,
            'password' =>$request->password,
            'name1'=>$request->name1,
            'street'=>$request->street,
            'city' => $request->city,
            'country'=>$request->country,
            'pcode'=>$request->pcode,
            'phone'=>$request->phone,
            'weight'=>$request->weight,
            'num_of_parcel'=>$request->num_of_parcel,
            'parcel_type'=>$request->parcel_type,

        ]];


    if($request->has('cod_amount'))
    {
        $data['form_params']['cod_amount'] =$request->cod_amount ;
    }


    $result = $client->post('https://lt.integration.dpd.eo.pl/ws-mapper-rest/createShipment_', $data
    );

    $response = $result->getBody();

    $responseArray = \GuzzleHttp\json_decode($response,true);

   return $responseArray ;
}

  public function createParcelLabel(Request $request)
  {
      $client = new Client(['verify' => false]); //GuzzleHttp\Client
      //$client->setDefaultOption('verify', false);
      $result = $client->post('https://lt.integration.dpd.eo.pl/ws-mapper-rest/parcelPrint_', [
          'form_params' => [
              'username' => $request->username,
              'password' => $request->password,
              'parcels' => $request->parcels,
              'printType' => $request->printType,
              'printFormat' => $request->printFormat,
          ]
      ]);
      $response = $result->getBody();
      //return $response;
      try {
          $responseArray = \GuzzleHttp\json_decode($response, true);
          return $responseArray['errlog'];
      } catch (\Exception $e) {
          return $response;

      }


  }

  public function createClosingManifest(Request $request)
  {
      $client = new Client(['verify' => false]); //GuzzleHttp\Client
      //$client->setDefaultOption('verify', false);
      $result = $client->post('https://lt.integration.dpd.eo.pl/ws-mapper-rest/parcelManifestPrint_', [
          'form_params' => [
              'username' => $request->username,
              'password' => $request->password,
              'date' => $request->date
          ]
      ]);
      $response = $result->getBody();
     // return $response;

          $responseArray = \GuzzleHttp\json_decode($response, true);
          if($responseArray['errlog']=='')
          {
              return $responseArray['pdf'];
          }

        else   return $responseArray['errlog'];

  }
}


