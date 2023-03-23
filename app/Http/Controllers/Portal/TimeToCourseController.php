<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\TimesToCourse;

class TimeToCourseController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only(['time', 'course_id']);

        $validator = Validator::make($credentials['time'], [
            'day' => 'required|min:3',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $userId = Auth::id();

        $time = $credentials['time'];

        $timeToCourse = new TimesToCourse;
        $timeToCourse->course_id = $credentials['course_id'];
        $timeToCourse->day = $time['day'];
        $timeToCourse->start_time = ($time['start_time']);
        $timeToCourse->end_time = ($time['end_time']);
        $timeToCourse->user_id = $userId;

        if ($timeToCourse->save()) {
            $timeToCourse = TimesToCourse::find($timeToCourse->id);
            return response()->json([
                'status' => 1,
                'message' => 'Time had been added successfully',
                'data' => $timeToCourse->toArray()
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function update(Request $request)
    {
        $credentials = $request->only(['time', 'time_id']);

        $validator = Validator::make($credentials['time'], [
            'day' => 'required|min:3',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $userId = Auth::id();

        $time = $credentials['time'];

        $timeToCourse = TimesToCourse::find($credentials['time_id']);
        $timeToCourse->day = $time['day'];
        $timeToCourse->start_time = ($time['start_time']);
        $timeToCourse->end_time = ($time['end_time']);
        $timeToCourse->user_id = $userId;

        if ($timeToCourse->save()) {
            $timeToCourse = TimesToCourse::find($timeToCourse->id);
            return response()->json([
                'status' => 1,
                'message' => 'Time had been added successfully',
                'data' => $timeToCourse->toArray()
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

        $time = TimesToCourse::find($credentials['id']);

        if (!$time) {
            return response()->json([
                'status' => 'error',
                'message' => 'Time is not defined'
            ]);
        }

        if ($time->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Time had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }
}
