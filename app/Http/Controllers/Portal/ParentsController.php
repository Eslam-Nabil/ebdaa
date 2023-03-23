<?php
namespace App\Http\Controllers\Portal;

use DB;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Student;

class ParentsController extends Controller
{
    public function createTokenByStudent(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            response()->json(['error' => 'Unauthenticated'], 401);
        }

        $credentials = $request->all();
        $rules = [
            'id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $id = $credentials['id'];
        $student = Student::find($id);
        
        if ($student->mother)
        {
            $student->mother[0]->generateToken();
        }

        if ($student->father)
        {
            $student->father[0]->generateToken();
        }

        return back()->withInput();
    }
}