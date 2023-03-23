<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        return view('portal/customers/list');
    }

    public function browse(Request $request)
    {
        return view('portal/customers/list');
    }

    public function list()
    {
        $users = User::select(['id', 'email'])->get();
        return response()->json(['data' => $users]);
    }

    public function create(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make($credentials, [
            'email' => 'required|unique:users|max:255|email',
            'password' => 'required|min:8|regex:#^[a-z\!\$\#\%0-9\_\-]+$#'
        ], [
            'regex' => 'Password MUST contains characters, special character and numbers'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $user = new User;

        $user->email = $credentials['email'];
        $user->password = bcrypt($credentials['password']);

        if ($user->save()) {
            return response()->json([
                'status' => 1,
                'message' => 'User had been registered successfully'
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function view($userId, Request $request)
    {
        $user = User::where('id', $userId);

        return view('portal/customers/view', [
            'user' => $user->first()->toArray(),
            'crumbs' => [
                ['text' => 'Portal', 'href' => 'portal.home'],
                ['text' => 'Customers', 'href' => 'portal.customers.browse']
            ]
        ]);
    }
}
