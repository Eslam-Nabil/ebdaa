<?php

namespace App\Http\Controllers\App;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseTitle;
use App\Models\Application;
use App\Models\TimesToCourse;
use App\Models\CoachesToCourse;
use App\Models\StudentsToCourse;

use App\Models\AttendanceToCourse;
use App\Models\StudentsToAttendance;
use Musonza\Chat\Models\Participation;

class ScheduleController extends Controller
{
    public function GetMonthSchedule(Request $request)
    {
        $children = Auth::user()
            ->children()->whereHas('application.courses', function($course){
                $current = Carbon::now();
                $course->whereDate('end_date', '>=', $current->toDateString())
                    ->orWhereNull('end_date');                
            })->with(
                [
                    'application.courses' => function($course) {
                        $current = Carbon::now();
                        $course->whereDate('end_date', '>=', $current->toDateString())
                            ->orWhereNull('end_date');                
                    }, 
                    'application.courses.coaches.coach',
                    'application.courses.times',
                    'application.courses.title',
                    'application.courses.attendanceToCourse',
                    'application.courses.attendanceToCourse.participants'
                ])->get();

        $resources = [];
        $appointments = [];        

        foreach($children as $child)
        {
            $resources[] = [
                'name' => $child->name,
                'id' => $child->id,
            ];

            foreach($child->application->courses as $course)
            {
                $startTime = $course->start_date;
                $endTime = $course->end_date;
                $subject = $course->title->title;
                $resourceId = $child->id;
                $recurrence = [];
                $notes = [];
                foreach($course->coaches as $coach)
                {
                    $subject .= " (".$coach->coach->name.")";
                }

                foreach($course->times as $time)
                {
                    $recurrence[] = [
                        'day' => $time->day,
                        'start' => $time->start_time,
                        'end' => $time->end_time,
                        'id' => $time->id
                    ];
                }

                foreach($course->attendanceToCourse as $attendance)
                {
                    $childNote = "";
                    foreach($attendance->participants as $participant)
                    {
                        if($participant->application_id == $child->application->id)
                        {
                            $childNote = $participant->notes;
                        }
                    }

                    $courseNote = $attendance->notes;
                    $notes[] = [
                        'student' => $childNote,
                        'course' => $courseNote,
                        'time' => $attendance->attendance_time,
                        'timeid' => $attendance->time_to_course_id
                    ];
                }

                $appointments[] = [
                    'start' => $startTime,
                    'end' => $endTime,
                    'subject' => $subject,
                    'resourceId' => $resourceId,
                    'recurrence' => $recurrence,
                    'notes' => $notes,
                ];
            }
        }
        
        return response()->json(['resources' => $resources, 'appointments' => $appointments]);
    }
}