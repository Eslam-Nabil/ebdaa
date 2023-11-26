<?php

namespace App\Http\Controllers\Finance;

use App\User;
use Carbon\Carbon;
use App\Models\Bond;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Expenses;
use Illuminate\Http\Request;
use App\Models\ExpenseRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExpenseRequestResource;

class ExpenseRequestController extends Controller
{
  public function __construct()
  {
  }
  public function index(Request $request)
  {
    $inputs = $request->all();
    return view('portal/ExpensesRequests/index', compact('inputs'));
  }


  public function list(Request $request)
  {
    $user = Auth::user();
    $expense_request = new ExpenseRequest;

    $expense_request = $expense_request->when($request->start_date, function ($q) use ($request) {
      return $q->whereDate('created_at', '>=', Carbon::parse($request->start_date));
    })
      ->when($request->end_date, function ($q) use ($request) {
        return $q->whereDate('created_at', '<=', Carbon::parse($request->end_date));
      });

    $expense_request = $expense_request->get();
    $expense_request = ExpenseRequestResource::collection($expense_request);
    return response()->json(['data' => $expense_request]);
  }

  public function create()
  {
    $expenses = Expenses::all();
    return view('portal/ExpensesRequests/create', compact('expenses'));
  }

  public function store(Request $request)
  {
    $user = Auth::user();
    $balance = $user->wallet->where('type', 'in')->sum('amount') - $user->wallet->where('type', 'out')->sum('amount');
    $validatedData = $request->validate(
      [
        'amount' => 'required|lte:' . $balance,
      ],
      [
        'lte' => 'Your wallet balance is  ' . $balance . ' amount must be smaller than your balance'
      ]
    );

    try {
      DB::beginTransaction();
      $inputs = $request->except('_token');
      if ($request->amount <= 1000) {
        $expense = Expenses::find($request->expenses_id);
        if ($expense->isSupply != 1) { // not supply 
          $accepted_by = User::where('group_id', 1)->first();
          $inputs['acceptedBy'] = $accepted_by->id;
          $expense_wallet = User::where('group_id', 6)->first();
          $expense_wallet->wallet()->create([
            'type' => 'in',
            'amount' => $request->amount,
            'expenses_id'=>$request->expenses_id
          ]);

          $user->wallet()->create([
            'type' => 'out',
            'amount' => $request->amount
          ]);
        }
        //  else {
        //   $created_by = Auth::user();
        //   if ($created_by->group_id != '1') {
        //     $accepted_by = User::where('group_id', 1)->first();
        //     $inputs['acceptedBy'] = $accepted_by->id;

        //     $accepted_by->wallet()->create([
        //       'type' => 'in',
        //       'amount' => $request->amount
        //     ]);

        //     $user->wallet()->create([
        //       'type' => 'out',
        //       'amount' => $request->amount
        //     ]);
        //   }
        // }
      }
      $inputs['createdBy'] = $user->id;
      ExpenseRequest::create($inputs);
      DB::commit();
      return redirect()->route('portal.request.index')->with(['success', 'added successfully']);
    } catch (\Exception $e) {
      dd($e);
    }
  }

  public function accept($id)
  {
    try {
      $request = ExpenseRequest::find($id);
      $expense = Expenses::find($request->expenses_id);
      $user = Auth::user();
      $creator_user = User::find($request->createdBy);

      if ($expense->isSupply != 1) { // not supply 
        $expense_wallet = User::where('group_id', 6)->first();
        $expense_wallet->wallet()->create([
          'type' => 'in',
          'amount' => $request->amount,
          'expenses_id'=>$request->expenses_id
        ]);

        $creator_user->wallet()->create([
          'type' => 'out',
          'amount' => $request->amount
        ]);
      } else {
        $user->wallet()->create([
          'type' => 'in',
          'amount' => $request->amount
        ]);

        $creator_user->wallet()->create([
          'type' => 'out',
          'amount' => $request->amount
        ]);
      }
      $request->acceptedBy = $user->id;
      $request->save();

      return redirect()->route('portal.request.index')->with(['success', 'added successfully']);
    } catch (\Exception $e) {
      dd($e);
    }
  }

  public function print()
  {

  }
}