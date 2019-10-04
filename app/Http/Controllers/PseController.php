<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Colfuturo\Beneficiary;
use App\Pse\Pse;
Use Redirect;


class PseController extends Controller
{
    
    /*
    *
    *
    */

    public function pay($identification, Request $request){
        
        $beneficiary = new Beneficiary;
        $beneficiary->setIdentification($identification);
        $beneficiary->getAll();

        $type = ( $request->input('opt_pay') == "COL" ? "CALCULADO" : "OTRO" );
        $typeText = "Cuota Normal";

        $trm = (float)str_replace(',','',Pse::trm());
        
        if( $type == 'CALCULADO' ){
            

            $cuota = $beneficiary->getCuotaTotal();
            $paymentCOL = round($cuota * $trm);
            $paymentUSD = $cuota;
        }else{
            $cuota = $beneficiary->getCuotaTotal();
            $paymentCOL = str_replace('$','',str_replace(',','',$request->input('input_cop')));
            $paymentUSD = str_replace('$','',str_replace(',','',$request->input('input_usd')));

            if( $paymentUSD > $cuota){

                if( $beneficiary->getMora() > 0 ){
                    $typeText = "";
                }
                $type = "OTRO_INFERIOR";

            }elseif($request->input('opt_pay_type') == 'CAP'){
                
                $type = "OTRO_CAPITAL";
                $typeText = "Abono a Capital";
                
            }else{

                $type = "OTRO_CUOTAS";
                $typeText = "Cuota Anticipada";
                
            }
        }

        if( $beneficiary->getMora() > 0 )	{
            $typeText = "Mora" . ( $typeText ? " y " . $typeText : "" );
            $type = "MORA_" . $type;
        }
        
        $beneficiary->setType($type);
        $beneficiary->setCuota($cuota);
        $beneficiary->setPaymentUSD($paymentUSD);
        $beneficiary->setPaymentCOP($paymentCOL);
        
        
        
        $pse = new Pse;
        
        if(!$pse->serviceVerification()){
            $e = '';
            return view('public.pse.beneficiary', compact('beneficiary', 'e'));
        }

        $response = $pse->createTransacction($beneficiary);
        
        if( $response['createTransactionPaymentHostingResult']['ReturnCode'] == 'OK'){
            $url = 'https://www.psepagos.co/PSEHostingUI/GetBankListWS.aspx?enc='.$response['createTransactionPaymentHostingResult']['PaymentIdentifier'];
            return Redirect::to($url);
        }        
            
    }



    public function callBack($identification, $attempt){
        $pse = new Pse;
        $status = $pse->getInformationTransaction($attempt);
        
        $beneficiary = new Beneficiary;
        $beneficiary->setIdentification($identification);
        $beneficiary->getAll();
        $beneficiary->updateAttemptState($status,$attempt);
       
        return view('public.pse.beneficiary', compact('beneficiary', 'status'));

    }


    public function transactionStatus($beneficiary, $attempt){
        $pse = new Pse;
        return $pse->getInformationTransaction($attempt);
    }


}
