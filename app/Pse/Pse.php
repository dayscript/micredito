<?php

namespace App\Pse;

use Illuminate\Database\Eloquent\Model;
use carbon\Carbon;
class Pse
{
    //



    public static function trm(){
        $date = Carbon::now();
        // $filename = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "trm.log";
        // print_r($filename);
        // $jsonLast = json_decode(file_get_contents($filename));
      
        try {
          $soap = new \SoapClient("https://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService?WSDL", array(
            'soap_version'   => SOAP_1_1,
            'trace' => 1,
            "location" => "http://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService",
          ));
          $response = $soap->queryTCRM(array('tcrmQueryAssociatedDate' => $date->toDateString()));
          $response = $response->return;
      
          if($response->success){
              return number_format($response->value,2);
            // $jsonLast->{$date} = $response->value;
            // file_put_contents( $filename, json_encode($jsonLast));
            // echo $date.": TRM Actualizada Correctamente Valor:  ".$response->value."\n";
          }
        } catch(Exception $e){
          return 0;
        }
    }
}
