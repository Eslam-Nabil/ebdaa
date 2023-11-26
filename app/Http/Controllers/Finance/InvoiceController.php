<?php

namespace App\Http\Controllers\Finance;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Course;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceDataResource;

class InvoiceController extends Controller
{
  public function __construct()
  {

  }

  public function index(Request $request)
  {
    $inputs = $request->all();
    return view('portal/invoices/index',compact('inputs'));
  }
  public function list(Request $request)
  {
    $invoices = new Invoice;
    $invoices = $invoices->when($request->start_date, function ($q) use ($request) {
      return $q->whereDate('created_at', '>=', Carbon::parse($request->start_date));
    })
      ->when($request->end_date, function ($q) use ($request) {
        return $q->whereDate('created_at', '<=', Carbon::parse($request->end_date));
      })
      ->when($request->not_finished, function ($q) use ($request) {
        return $q->where('remaining', '!=', "0");
      });
    $invoices = $invoices->get();
    $invoices = InvoiceDataResource::collection($invoices);
    return response()->json(['data' => $invoices]);
  }

  public function create()
  {
    $incomes = Income::where('id','!=',1)->get();
    $students = Student::all();
   
    return view('portal/invoices/create', compact('incomes', 'students'));
  }

  public function store(Request $request)
  {
    try {
      DB::beginTransaction();
      $invoiceData = $request->all();
      $invoiceData['remaining']=$request->total;
      Invoice::create($invoiceData);
      DB::commit();
      return redirect()->route('portal.invoice.index');
    } catch (\Exception $e) {
      dd($e);
    }
  }

  public function view($id)
  {
    $invoice = Invoice::find($id);
    return view('portal/invoices/view', compact('invoice'));
  }
  public function view_json($id)
  {
     return Invoice::find($id);

  }

  public function printInvoice($invoiceId)
  {
      $invoice = Invoice::findOrFail($invoiceId);
      // dd($invoice);
      $pdf = Pdf::loadView('portal/invoices/invoice-pdf', ['invoice'=>$invoice]);
      return $pdf->stream(); // Output the generated PDF to the browser
  }
} 