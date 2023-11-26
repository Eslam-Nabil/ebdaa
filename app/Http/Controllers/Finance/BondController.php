<?php

namespace App\Http\Controllers\Finance;

use App\Models\Bond;
use App\Models\Income;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StudentsToCourse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BondDataResource;
use Illuminate\Support\Facades\Validator;

class BondController extends Controller
{
  public function __construct()
  {

  }

  public function index(Request $request)
  {
    $inputs = $request->all();
    return view('portal/bonds/index', compact('inputs'));
  }
  
  public function list(Request $request)
  {
    $user = Auth::user();
    $bonds = new Bond;
    $bonds = $bonds->when($request->start_date, function ($q) use ($request) {
      return $q->whereDate('created_at', '>=', Carbon::parse($request->start_date));
    })
      ->when($request->end_date, function ($q) use ($request) {
        return $q->whereDate('created_at', '<=', Carbon::parse($request->end_date));
      });
    $bonds = $bonds->get();

    $bonds = BondDataResource::collection($bonds);
    return response()->json(['data' => $bonds]);
  }

  public function create()
  {
    $invoices = Invoice::all();
    return view('portal/bonds/create', compact('invoices'));
  }

  public function store(Request $request)
  {
    $invoice = Invoice::find($request->invoice_id);
    $validatedData = $request->validate(
      [
        'amount' => 'required|lte:' . $invoice->remaining,
      ],
      [
        'lte' => 'Amount must be smaller than ' . $invoice->remaining
      ]
    );
    try {
      DB::beginTransaction();
      $user = Auth::user();
      $inputs = $request->except('_token');
      $inputs['createdBy'] = $user->id;
      Bond::create($inputs);
      $invoice->remaining = $invoice->remaining - $request->amount;
      $invoice->save();
      if ($request->student_course_bond == 1) {
        $student_to_course = StudentsToCourse::find($request->student_course_id);
        $student_to_course->paid = $invoice->total - $invoice->remaining;
        $student_to_course->save();
      }
      DB::commit();
      return redirect()->route('portal.bond.index')->with(['success', 'added successfully']);

    } catch (\Exception $e) {
      dd($e);
    }

  }

  public function accept($id)
  {
    try {
      $bond = Bond::find($id);
      $user = Auth::user();
      $user->wallet()->create([
        'type' => 'in',
        'amount' => $bond->amount
      ]);
      $bond->acceptedBy = $user->id;
      $bond->save();
      return redirect()->route('portal.bond.index')->with(['success', 'added successfully']);
    } catch (\Exception $e) {
      dd($e);
    }
  }

  public function printBond($bondId)
  {
    $bond = Bond::findOrFail($bondId);
    $pdf = Pdf::loadView('portal/bonds/bond-pdf', ['bond' => $bond]);
    return $pdf->stream(); // Output the generated PDF to the browser
  }
}