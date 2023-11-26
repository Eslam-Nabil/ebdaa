<?php

namespace App\Http\Controllers\Finance;

use App\User;
use Carbon\Carbon;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExpenseWalletResource;

class WalletController extends Controller
{
    function expense_wallet(Request $request)
    {
        $inputs = $request->all();
        return view('portal.ExpensesRequests.expenses-wallet',compact('inputs'));
    }
    public function expense_wallet_list(Request $request)
    {
        $expense_user = User::where('group_id', 6)->first();
        $wallet = new Wallet;
        $wallet = $wallet->when($request->start_date, function ($q) use ($request) {
            return $q->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        })
        ->when($request->end_date, function ($q) use ($request) {
            return $q->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        });
        $wallet = $wallet->where('user_id', $expense_user->id)->get();

        $wallet = ExpenseWalletResource::collection($wallet);
        return response()->json(['data' => $wallet]);
    }
}