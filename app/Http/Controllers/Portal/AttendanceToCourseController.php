<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\AttendanceToCourse;
use App\Models\StudentsToAttendance;

class AttendanceToCourseController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->only(['attendance', 'course_id', 'time_to_course_id']);
        
        $validator = Validator::make($credentials['attendance'], [
            'attendance_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $userId = Auth::id();

        $attendance = $credentials['attendance'];

        $attendanceToCourse = new AttendanceToCourse;
        $attendanceToCourse->course_id = $credentials['course_id'];
        $attendanceToCourse->time_to_course_id = $credentials['time_to_course_id'];
        $attendanceToCourse->attendance_date = $attendance['attendance_date'] ?: '';
        $attendanceToCourse->attendance_time = date(
            "Y-m-d H:i:s",
            strtotime($attendance['attendance_date'])
        );
        $attendanceToCourse->notes = $attendance['notes'] ?: '';
        $attendanceToCourse->user_id = $userId;

        if ($attendanceToCourse->save()) {

            $attendanceId = $attendanceToCourse->id;

            if (
                isset($attendance['participants']) && is_array($attendance['participants']) &&
                count($attendance['participants']) > 0
            ) {
                foreach ($attendance['participants'] as $participant) {
                    $studentsToAttendance = new StudentsToAttendance;
                    $studentsToAttendance->attendance_id = $attendanceId;
                    $studentsToAttendance->application_id = $participant;
                    $studentsToAttendance->save();
                }
            }

            $attendanceToCourse = AttendanceToCourse::/*with(['application' => function ($q) {
                $q->with(['student' => function ($q) {
                    $q->with(['s2p' => function ($q) {
                        $q->with('father');
                    }]);
                }]);
            }])->*/find($attendanceId);
            return response()->json([
                'status' => 1,
                'message' => 'Participant had been added successfully',
                'data' => $attendanceToCourse->toArray()
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function update(Request $request)
    {
        $credentials = $request->only(['attendance', 'attendance_id']);

        $userId = Auth::id();

        $attendance = $credentials['attendance'];

        $attendanceToCourse = AttendanceToCourse::find($credentials['attendance_id']);
        $attendanceToCourse->notes = $attendance['notes'] ?: '';

        if ($attendanceToCourse->save()) {
            if (
                isset($attendance['participants']) && is_array($attendance['participants']) &&
                count($attendance['participants']) > 0
            ) {

                $delete = StudentsToAttendance::where('attendance_id', $credentials['attendance_id'])
                            ->delete();

                foreach ($attendance['participants'] as $participant) {
                    $studentsToAttendance = new StudentsToAttendance;
                    $studentsToAttendance->attendance_id = $credentials['attendance_id'];
                    $studentsToAttendance->application_id = $participant;

                    $studentsToAttendance->save();
                }
            } else {
                $delete = StudentsToAttendance::where('attendance_id', $credentials['attendance_id'])
                            ->delete();
            }


            return response()->json([
                'status' => 1,
                'message' => 'Participant had been added successfully',
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

        $student = AttendanceToCourse::find($credentials['id']);

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

    public function StudentNote(Request $request)
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

        $credentials = $request->all();
        $studentToAttendance = StudentsToAttendance::find($credentials['id']);

        if (isset($credentials['note']))
        {
            $studentToAttendance->notes = $credentials['note'];
            $studentToAttendance->save();
        }

        return response()->json($studentToAttendance);
    }
}
