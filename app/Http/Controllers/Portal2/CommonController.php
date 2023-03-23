<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class CommonController extends Controller
{
    public function notifications(Request $request)
    {
        return response()->json([
            [''],[''],
            [''],[''],
        ]);
    }

    public function index()
    {
        echo 'Hello!';
    }
}
