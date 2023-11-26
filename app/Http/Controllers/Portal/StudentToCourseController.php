<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\StudentsToCourse;

class StudentToCourseController extends Controller
{
    public function store(Request $request)
    {  
        $credentials = $request->only(['participant', 'course_id']);
        $validator = Validator::make($credentials['participant'], [
            'application_id' => 'required',
            'paid' => 'required',
            'discount'=>'numeric|lte:' . $request->participant['total']
        ],
        [
            'lte'=>'Discount must be smaller than ' .$request->participant['total']    
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $userId = Auth::id();
        $participant = $credentials['participant'];
        $studentToCourse = new StudentsToCourse;
        $studentToCourse->course_id = $credentials['course_id'];
        $studentToCourse->application_id = $participant['application_id'];
        $studentToCourse->paid = $participant['paid'];
        $studentToCourse->total = ($participant['discount'] ? $participant['total'] -$participant['discount']:$participant['total']) ;
        $studentToCourse->paid_1 = $participant['paid_1']?: 0;
        $studentToCourse->paid_2 = $participant['paid_2']?: 0;
        $studentToCourse->paid_3 = $participant['paid_3']?: 0;
        $studentToCourse->paid_4 = $participant['paid_4']?: 0;
        $studentToCourse->paid_5 = $participant['paid_5']?: 0;
        // $studentToCourse->invoice = $participant['invoice'] ?: '';
        $studentToCourse->discount = $participant['discount'] ?: '';
        $studentToCourse->get_books = $participant['get_books'] ?: 0;
        $studentToCourse->user_id = $userId;
        
        if ($studentToCourse->save()) {
            $studentToCourse = StudentsToCourse::with(['application' => function ($q) {
                $q->with(['student' => function ($q) {
                    $q->with(['s2p' => function ($q) {
                        $q->with('father');
                    }]);
                }]);
            }])->find($studentToCourse->id);
            return response()->json([
                'status' => 1,
                'message' => 'Participant had been added successfully',
                'data' => $studentToCourse->toArray()
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function update(Request $request)
    {
        $credentials = $request->only(['participant', 'participant_id']);

        $validator = Validator::make($credentials['participant'], [
            'paid' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $userId = Auth::id();

        $participant = $credentials['participant'];

        $studentToCourse = StudentsToCourse::find($credentials['participant_id']);
        // $studentToCourse->paid = $participant['paid'];
        $studentToCourse->paid_1 = isset($participant['paid_1']) ? $participant['paid_1'] : 0;
        $studentToCourse->paid_2 = isset($participant['paid_2']) ? $participant['paid_2'] : 0;
        $studentToCourse->paid_3 = isset($participant['paid_3']) ? $participant['paid_3'] : 0;
        $studentToCourse->paid_4 = isset($participant['paid_4']) ? $participant['paid_4'] : 0;
        $studentToCourse->paid_5 = isset($participant['paid_5']) ? $participant['paid_5'] : 0;
        $studentToCourse->invoice = $participant['invoice'];
        $studentToCourse->discount = $participant['discount'];
        $studentToCourse->get_books = $participant['get_books'];

        if ($studentToCourse->save()) {
            $studentToCourse = StudentsToCourse::with(['application' => function ($q) {
                $q->with(['student' => function ($q) {
                    $q->with(['s2p' => function ($q) {
                        $q->with('father');
                    }]);
                }]);
            }])->find($studentToCourse->id);
            return response()->json([
                'status' => 1,
                'message' => 'Participant had been added successfully',
                'data' => $studentToCourse->toArray()
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

        $student = StudentsToCourse::find($credentials['id']);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Participant is not defined'
            ]);
        }

        if ($student->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Participant had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }
}
