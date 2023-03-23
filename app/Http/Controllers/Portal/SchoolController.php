<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\School;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/schools/list');
    }

    public function browse(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/schools/list');
    }

    public function list()
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $users = School::select(['id', 'name'])->get();
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->only(['name', 'address']);

        $validator = Validator::make($credentials, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $school = new School;

        $school->name = $credentials['name'];
        $school->address = isset($credentials['address']) ? $credentials['address'] : '';
        $school->user_id = Auth::id();

        if ($school->save()) {
            return response()->json([
                'status' => 1,
                'message' => 'School had been registered successfully'
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function view($userId, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $user = School::where('id', $userId);

        return view('portal/schools/view', [
            'user' => $user->first()->toArray(),
            'crumbs' => [
                ['text' => 'Portal', 'href' => 'portal.home'],
                ['text' => 'Customers', 'href' => 'portal.schools.browse']
            ]
        ]);
    }

    public function delete($userId, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $school = School::find($userId);

        if (!$school) {
            return response()->json([
                'status' => 'error',
                'message' => 'school is not defined'
            ]);
        }

        if ($school->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'School had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }
}
