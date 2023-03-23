<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\StudentToMembership;

class MembershipToApplicationController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only([
            'membership_id',
            'student_id',
        ]);

        $validator = Validator::make($credentials, [
            'membership_id' => 'required',
            'student_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $userId = Auth::id();

        $membership = new StudentToMembership;

        $membership->student_id = $credentials['student_id'];
        $membership->membership_id = $credentials['membership_id'];

        $membership->save();

        if ($membership->save()) {
            $membership = StudentToMembership::with('membership')->find($membership->id);
            return response()->json([
                'status' => 1,
                'message' => 'Membership had been added successfully',
                'data' => $membership->toArray()
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

        $membership = StudentToMembership::find($credentials['id']);

        if (!$membership) {
            return response()->json([
                'status' => 'error',
                'message' => 'Membership is not defined'
            ]);
        }

        if ($membership->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Membership had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }
}
