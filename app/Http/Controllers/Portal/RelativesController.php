<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Relative;
use App\Models\Student;

class RelativesController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only([
            'relative_name',
            'relative_dob',
            'relative_school_id',
            'relative_grade',
            'student_id',
        ]);

        $validator = Validator::make($credentials, [
            'relative_name' => 'required|min:3',
            'relative_dob' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $userId = Auth::id();

        $relative = new Relative;

        $relative->student_id = $credentials['student_id'];
        $relative->name = $credentials['relative_name'];
        $relative->dob = $credentials['relative_dob'];
        $relative->school_id = $credentials['relative_school_id'];
        $relative->grade = $credentials['relative_grade'] ?: '';
        $relative->user_id = $userId;

        if ($relative->save()) {
            $relative = Relative::with('school')->find($relative->id);
            return response()->json([
                'status' => 1,
                'message' => 'Relative had been added successfully',
                'data' => $relative->toArray()
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function delete(Request $request)
    {
        $credentials = $request->only([
            'id',
        ]);

        $validator = Validator::make($credentials, [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $relative = Relative::find($credentials['id']);

        if (!$relative) {
            return response()->json([
                'status' => 'error',
                'message' => 'Relative is not defined'
            ]);
        }

        if ($relative->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Relative had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }

    public function GetRelatives(Request $request)
    {
        $credentials = $request->only([
            'id',
        ]);

        $validator = Validator::make($credentials, [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }
    }
}
