<?php

namespace App\Http\Controllers\Bond;

use App\Http\Controllers\Controller;
use App\Models\Bond;
use App\Models\Income;
use App\Models\Invoice;
use Illuminate\Http\Request;

class BondController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
      $bonds=Bond::all();
      return view('portal/bonds/index',compact('bonds'));
    }
    
    public function create()
    {
      $incomes = Income::all();
      $invoices = Invoice::all();
        return view('portal/bonds/create',compact('incomes','invoices'));
    }

    public function store(Request $request )
    {
      dd($request->all());
      if($request->invoice  )
    }

    public function accept()
    {
      
    }

    public function print()
    {
      
    }
}
