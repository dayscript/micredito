<?php

namespace App\Pse;

use Illuminate\Database\Eloquent\Model;
use carbon\Carbon;

require_once  "/usr/share/nginx/html/micredito/pse/soa/soap_lib/Soap.php";

class Pse
{
  static $trm_end_point = "https://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService?WSDL";    
  static $trm_location  = "http://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService";
  static $arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
    "http" => array(
      'timeout' => 5,
    ),
  );  
    
  
  public function __construct(){

    $this->database_host = 'ns11.colfuturo.org';
    $this->database_user_name = 'micredito';
    $this->database_user_passwd = 'Cre2014Col';
    $this->database_name = 'colfuturo';

    
    $this->pse_end_point = "https://200.1.124.118/PSEHostingWebServices/PSEHostingWS.asmx?wsdl";    //"https://www.colfuturo.org/ach/storage/PSEHostingWS.asmx";
    
    
    $this->client = new \nusoap_client( $this->pse_end_point, true );
    
    // $arrContextOptions=array(
    //   "ssl"=>array(
    //       "verify_peer"=>false,
    //       "verify_peer_name"=>false,
    //   ),
    // );  
        
    // $options = array(
    //         'soap_version'=>SOAP_1_2,
    //         'exceptions'=>true,
    //         'trace'=>1,
    //         'cache_wsdl'=>WSDL_CACHE_NONE,
    //         'stream_context' => stream_context_create($arrContextOptions)
    // );

    // $this->client = new \SoapClient( 
    //   $this->pse_end_point,
    //   $options
    // );

    // $this->pse_entity_url = 'http://www.colfuturo.org/micredito2014/pse/soa/prueba.php';
      
    }
    
  /*
  *
  *
  */
  public static function trm(){
      $date = Carbon::now();
      try {
        $soap = new \SoapClient(
          self::$trm_end_point, 
          array(
            'soap_version'   => SOAP_1_1,
            'trace' => 1,
            "location" => self::$trm_location,
          )
        );
        $response = $soap->queryTCRM(array('tcrmQueryAssociatedDate' => $date->toDateString()));
        $response = $response->return;
    
        if($response->success){
            return number_format($response->value,2);
        }
      } catch(Exception $e){
        return 0;
      }
  }

  /*
  *
  *
  */

  public function createTransacction($beneficiary){

      $id_attempt = $beneficiary->createAttemptPay();
      $param = array (

        'ticketOfficeID'      => 7072,
        'serviceCode'         => 1001,
        'amount'              => $beneficiary->paymentCOP,
        'vatAmount'           => 0.0,
        'paymentID'           => $id_attempt,
        'paymentDescription'  => $beneficiary->type, 
        'referenceNumber1'    => $_SERVER['REMOTE_ADDR'],
        'referenceNumber2'    => 'CC',
        'referenceNumber3'    => $beneficiary->promo->PER_NUMERO_DOCUMENTO,
        'email'               => 'aacevedo@dayscript.com',//$beneficiary->personal->PER_CORREO_ELECTRONICO,
        'fields' => array(
          'PSEHostingField'=>array(
                       array('Name' =>  'id_cliente',          'Value' => $beneficiary->promo->PER_NUMERO_DOCUMENTO),
                       array('Name' =>  'codigo_beneficiario', 'Value' => $beneficiary->personal->PER_CODIGO),
                       array('Name' =>  'nombre_cliente',      'Value' => $beneficiary->personal->PER_NOMBRES),
                       array('Name' =>  'apellido_cliente',    'Value' => $beneficiary->personal->PER_APELLIDOS),
                       array('Name' =>  'telefono_cliente',    'Value' => $beneficiary->personal->RES_TELEFONO)
          )
        ),
        'entity_url' => 'http://www.colfuturo.org/ach/pse/beneficiary/'.$beneficiary->identification.'/callback/'.$id_attempt
      );

    try {
      $response = $this->client->call('createTransactionPaymentHosting',$param);  
    } catch (Exception $exc) {
      return $exc->getTraceAsString();
    }

    if( $response['createTransactionPaymentHostingResult']['ReturnCode']  && ( $response['createTransactionPaymentHostingResult']['ReturnCode'] == 'OK' ) ) {
      $beneficiary->initAttemptPay($response['createTransactionPaymentHostingResult']['PaymentIdentifier']);
    }
    return $response;

  }
  
   /*
  *
  *
  */
  public function getInformationTransaction($payment_id){
    $param = array(
          'ticketOfficeID'=> 7072,
          'password' => '123',
          'paymentID' => $payment_id,                 				
      );
    $response = $this->client->call('getTransactionInformationHosting', $param);
    
    return (object)$response['getTransactionInformationHostingResult'];
  }

     /*
  *
  *
  */
  public function serviceVerification(){
      $response = @file_get_contents( $this->pse_end_point, false, stream_context_create(self::$arrContextOptions));

      if(!$response){
        return FALSE;
      }else{
        return TRUE;
      }
  }



}
