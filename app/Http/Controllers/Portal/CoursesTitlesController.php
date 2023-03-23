<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\CourseTitle;

class CoursesTitlesController extends Controller
{
    public function index(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/courses_titles/list');
    }

    public function browse(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/courses_titles/list');
    }

    public function list()
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $users = CourseTitle::select(['id', 'title'])->get();
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->only(['course_title']);

        $validator = Validator::make($credentials, [
            'course_title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $courseTitle = new CourseTitle;

        $courseTitle->title = $credentials['course_title'];
        $courseTitle->user_id = Auth::id();

        if ($courseTitle->save()) {
            return response()->json([
                'status' => 1,
                'message' => 'Course Title had been registered successfully'
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function delete($id, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $courseTitle = CourseTitle::find($id);

        if (!$courseTitle) {
            return response()->json([
                'status' => 'error',
                'message' => 'title is not defined'
            ]);
        }

        if ($courseTitle->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Title had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }
}
