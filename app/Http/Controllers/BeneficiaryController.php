<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Colfuturo\Beneficiary;

use Carbon\Carbon;

class BeneficiaryController extends Controller
{
    
    /*
    *
    *
    */
    public function index(){
        return view('public.pse.beneficiary');
    }

    
    /*
    *
    *
    */
    public function load($identification){
        
        
        $beneficiary = new Beneficiary;
        $beneficiary->setIdentification($identification);
        $beneficiary->getAll();
        
               

        return view('public.pse.beneficiary', compact('beneficiary'));

    }



} 
