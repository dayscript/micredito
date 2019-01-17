<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Colfuturo\Beneficiary;

class DevelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
      
       $beneficiary = new Beneficiary;

       $beneficiary->setIdentification('38566386');
       
       $beneficiary->getAll();
       dd($beneficiary->getCuotaTotal());
       dd($beneficiary);
    }
}
 