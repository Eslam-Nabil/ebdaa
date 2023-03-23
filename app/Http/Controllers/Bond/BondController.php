<?php

namespace App\Http\Controllers\Bond;

use App\Http\Controllers\Controller;
use App\Models\Bond;
use Illuminate\Http\Request;

class BondController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
    $bonds=Bond::all();
      //$bonds='a';
      return view('portal/bonds/index',compact('bonds'));
    }
    
    public function create()
    {
        return view('portal/bonds/create',compact('bonds'));
    }

    public function store()
    {
      
    }

    public function accept()
    {
      
    }

    public function print()
    {
      
    }
}
