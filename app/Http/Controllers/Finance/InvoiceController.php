<?php

namespace App\Http\Controllers\Finance;

use App\Models\Course;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
  public function __construct()
  {

  }

  public function index()
  {
    $invoice = Invoice::all();
    return view('portal/invoices/index', compact('invoice'));
  }

  public function create()
  {
    $incomes = Income::all();
    $students = Student::all();
    $courses = Course::all();
    return view('portal/invoices/create', compact('incomes', 'students','courses'));
  }

  public function store(Request $request)
  {
    try {
      DB::beginTransaction();
      Invoice::create($request->all());
      DB::commit();
      return redirect()->route('portal.invoice.index');
    } catch (\Exception $e) {
      dd($e);
    }
  }

  public function accept()
  {
  }

  public function print()
  {
  }
}