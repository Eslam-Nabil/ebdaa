<?php

namespace App\Http\Controllers\Portal;

use DB;
use Auth;
use Validator;
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

class CoursesController extends Controller
{
    public function index()
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3, 4]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/courses/list');
    }

    public function grid(Request $request, $startDate = null)
    {

        $groupId = Auth::user()->group_id;
        $userId = Auth::id();

        if (in_array($groupId, [1, 2, 3, 4]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $endMonths = Course::selectRaw('DATE_FORMAT(end_date, "%Y-%m") as endDate')
            ->groupBy('endDate');

        $months = Course::selectRaw('DATE_FORMAT(start_date, "%Y-%m") as startDate')
            ->groupBy('startDate')->union($endMonths)->get();

        $currentMonth = date('Y-m', time());

        $months = array_column($months->toArray(), 'startDate', 'startDate');

        $months[$currentMonth] = $currentMonth;

        $startDate = $startDate ?: date('Y-m', time());

        $calDate = explode('-', $startDate);

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $calDate[1], $calDate[0]);

        $courses = Course::with(['times'])->where('start_date', '>=', $startDate . '-01')->get();

        $courseTimes = TimesToCourse::with([
            'course' => function ($q) use ($startDate, $daysInMonth, $groupId, $userId) {
                return $q->with([
                    'title',
                    'times',
                    'coaches' => function ($q) use ($groupId, $userId) {
                        $q->with(['coach']);
                    }
                ]);/*->whereBetween('start_date', [
                    $startDate . '-01',
                    $startDate . '-' . $daysInMonth
                ])->orWhere('end_date', '<=', $startDate . '-' . $daysInMonth);*/
            }
        ])->whereHas('course.coaches', function ($q) use ($userId, $groupId) {
            if ($groupId == 4) {
                $q->where('coach_id', '=', $userId);
            }
        })->WhereHas('course', function ($qq) use ($startDate, $daysInMonth) {
            $qq->orWhere(function ($q) use ($startDate, $daysInMonth) {
                $q->whereBetween('start_date', [
                    $startDate . '-01',
                    $startDate . '-' . $daysInMonth
                ])->orWhere('end_date', '<=', $startDate . '-' . $daysInMonth);
            });
        })/*->orderByRaw('STR_TO_DATE(start_time, "%h:%i %p") ASC')*/->get()->toArray();

        /*dd($courseTimes);

        $courseTimes = array_filter($courseTimes, function ($v) {
            return $v['course'] != null;
        });*/

        // echo '<pre>';print_r($courseTimes);exit;

        $timesArray = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $times = [];
            foreach ($courseTimes as $key => $courseTime) {
                $date = $startDate . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $end_date = $startDate . '-' . $daysInMonth;
                $day = date("l", strtotime($date));
                if (
                    strtotime($courseTime['course']['start_date']) <= strtotime($date)
                ) {
                    if (
                        !isset($courseTime['course']['end_date']) ||
                        strtotime($courseTime['course']['end_date']) >= strtotime($date)
                    ) {
                        if ($courseTime['day'] == strtolower($day)) {
                            $times[$courseTime['course']['lab']][$key] = $courseTime;
                        } else {
                            if (!isset($times[$courseTime['course']['lab']])) {
                                $times[$courseTime['course']['lab']] = null;
                            }
                        }
                    }
                }/* else {
                    $times[$courseTime['course']['lab']] = null;
                }*/

		if (isset($times[$courseTime['course']['lab']])) {
                    usort($times[$courseTime['course']['lab']], function ($a, $b) {
                        return strtotime($a['start_time']) > strtotime($b['start_time']);
                    });
                }
            }

            if (isset($date)) {

                $timesArray[$date] = array_filter($times);
            }

        }

        // dd($timesArray);

        // dd($courseTimes, $daysInMonth);

        /*$studentsToCoursesSummary = StudentsToCourse::with([
            'application' => function ($q) {
                $q->with(['owner']);
            },
            'owner'
            ])
            ->whereHas('application', function ($q) use ($startDate, $groupId, $userId) {
                $date = explode('-', $startDate);
                $q->whereYear('created_at', $date[0])
                ->whereMonth('created_at', $date[1])
                ->whereHas('owner', function ($qq) use ($groupId, $userId) {
                    $qq->where('group_id', 3);
                    if ($groupId == 3) {
                        $qq->where('id', $userId);
                    }
                });
            })
        ->get();*/
        $studentsToCoursesSummary = StudentsToCourse::with([
            'application' => function ($q) {
                $q->with([
                    'owner',
                ]);
            },
            'owner',
            'course'
            ])->whereHas('course', function($course){
                $course->where('tournament', 0);
            })
            ->where(function ($q) use ($startDate) {
                $date = explode('-', $startDate);
                $q->whereYear('created_at', $date[0])
                ->whereMonth('created_at', $date[1]);                
            })
            ->whereHas('owner', function ($qq) use ($groupId, $userId) {
                $qq->where('group_id', 3);
                if ($groupId == 3) {
                    $qq->where('id', $userId);
                }
            })            
        ->get();

        // dd($studentsToCoursesSummary->toArray());

        $marketingSummaries = [];

        foreach ($studentsToCoursesSummary->toArray() as $key => &$student2Course) {
            $marketerId = $student2Course['application']['owner']['id'];
            $marketerName = $student2Course['application']['owner']['name'];
            $marketingSummaries[$student2Course['application']['owner']['id']]['id'] = $marketerId;
            $marketingSummaries[$student2Course['application']['owner']['id']]['name'] = $marketerName;
            $marketingSummaries[$student2Course['application']['owner']['id']]['paid'][] = $student2Course['paid'];

        }

        $marketingSummary = [];

        foreach ($marketingSummaries as $id => $mms) {
            $marketingSummary[$id] = [
                'name' => $mms['name'],
                'paid' => array_sum($mms['paid']),
                'students' => count($mms['paid'])
            ];
        }
 
        // dd($marketingSummary);

        return view('portal/courses/grid', [
            'months' => $months,
            'startDate' => $startDate,
            'daysInMonth' => $daysInMonth,
            'calDate' => $calDate,
            'courses' => $courses,
            'courseTimes' => $courseTimes,
            'timesArray' => $timesArray,
            'marketingSummary' => $marketingSummary
        ]);
    }

    public function attendance($id)
    {
        $startDate = null;
        $groupId = Auth::user()->group_id;
        $userId = Auth::id();

        $startDate = date('Y-m', time());
        $today = date('Y-m-d', time());

        $calDate = explode('-', $startDate);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $calDate[1], $calDate[0]);

        $courseObj = Course::with([
            'times',
            'title',
            'participants',
            'coaches' => function ($q) {
                $q->with('coach');
            },
            'attendanceToCourse' => function ($q) {
                $q->with('participants');
            },
        ])->find($id);

        $coaches = array_column($courseObj->toArray()['coaches'], 'coach');

        $course = $courseObj->toArray();

        $timesToCourse = array_column($course['times'], 'day', 'id');

        $courseStart = new \DateTime($course['start_date']);

        $endDate = $today;
        if (is_null($course['end_date']) == false) {
            if (1==1 || strtotime($today) > strtotime($course['end_date'])) {
                $endDate = $course['end_date'];
            }
        }

        $courseEnd = new \DateTime($endDate);
        $courseEnd->modify('+1 day');

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($courseStart, $interval, $courseEnd);

        $attendances = AttendanceToCourse::with(['participants'])->where('course_id', $id)->get();
        $attendancesDates = [];

        foreach ($attendances as $attendance) {
            $attendancesDates[] = strtotime($attendance['attendance_date']);
        }

        if (count($attendancesDates) > 0) {
            $maxAttendanceDate = max($attendancesDates);
        } else {
            $maxAttendanceDate = $course['start_date'];
        }

        $days = $filteredDays = [];

        foreach ($period as $dt) {
            if (in_array(strtolower($dt->format('l')), $timesToCourse)) {
                $key = array_search(strtolower($dt->format('l')), $timesToCourse);
                $days[] = [
                    'key' => $key,
                    'date' => $dt->format("l Y-m-d"),
                    'dayName' => $dt->format('l'),
                    'year' => $dt->format('Y'),
                    'month' => $dt->format('m'),
                    'day' => $dt->format('d'),
                    'timestamp' => $dt->getTimestamp(),
                ];

                if (in_array($dt->getTimestamp(), $attendancesDates) == false) {
                    $filteredDays[] = [
                        'key' => $key,
                        'date' => $dt->format("l Y-m-d"),
                        'dayName' => $dt->format('l'),
                        'year' => $dt->format('Y'),
                        'month' => $dt->format('m'),
                        'day' => $dt->format('d'),
                        'timestamp' => $dt->getTimestamp(),
                    ];
                }
            }
        }

        // dd($days, $filteredDays);

        $attendancesObj = StudentsToAttendance::with([
            'attendance'
        ])->whereHas('attendance', function ($q) use ($id) {
            $q->where('course_id', $id);
        })->get();

        $attendances = $notes = [];

        // dd($attendancesObj->toArray());
        $attendancesToCource = AttendanceToCourse::with(['participants'])->where('course_id', $id)->get();

        // dd($attendancesToCource->toArray());

        foreach ($attendancesToCource->toArray() as $attendanceToCource) {
            $attendanceTime = strtotime($attendanceToCource['attendance_date']);
            $notes[$attendanceTime]['note'] = $attendanceToCource['notes'];
            $notes[$attendanceTime]['attendance_id'] = $attendanceToCource['id'];
            $notes[$attendanceTime]['attendances'] = array_column(
                $attendanceToCource['participants'], 'application_id'
            );
        }

        foreach ($attendancesObj->toArray() as $attendance) {
            $appId = $attendance['application_id'];
            
            $attendanceTime = strtotime($attendance['attendance']['attendance_date']);
            $attendances[$attendance['application_id']][$attendanceTime] = $attendance;

            /*$notes[$attendanceTime] = [
                'note' =>$attendance['attendance']['notes'],
                'attendance_id' => $attendance['attendance']['id'],
                'attendances' => $attendance['attendance']['id'],
            ];*/

            /*$notes[$attendanceTime]['note'] = $attendance['attendance']['notes'];
            $notes[$attendanceTime]['attendance_id'] = $attendance['attendance']['id'];
            $notes[$attendanceTime]['attendances'][] = $attendance['application_id'];*/
        }

        // dd($notes, $attendances);

        // dd($attendances);

        // dd($attendancesObj->toArray());

        // dd($course, $days);

        return view('portal/courses/attendance', [
            'days' => $days,
            'attendances' => $attendances,
            'course' => $courseObj,
            'courseId' => $id,
            'todayTS' => strtotime($today),
            'maxAttendanceDate' => $maxAttendanceDate,
            'filteredDays' => $filteredDays,
            'notes' => $notes,
            'coaches' => implode(', ', array_column($coaches, 'name'))
        ]);
    }

    public function list()
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $courses = DB::table('courses')
            ->select('courses.*', 'courses_titles.title')
            ->join(
                'courses_titles', 'courses.title_id', '=', 'courses_titles.id'
                )
            ->whereNull('deleted_at')->get();
        return response()->json(['data' => $courses]);
    }

    public function create()
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $times = [];
        $start = new \DateTime('09AM');
        $end = new \DateTime('09:01PM');
        $interval = new \DateInterval('PT30M');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $times[] = $dt->format("h:i A");
        }

        $coaches = User::where('group_id', '=', 4)->get();

        $titles = CourseTitle::all();
        return view('portal/courses/create', [
            'coaches' => $coaches,
            'times' => $times,
            'titles' => $titles,
            'participants' => null
        ]);
    }

    public function createFrom($id)
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $course =  Course::with('participants', 'times')->find($id);

        $coaches = User::where('group_id', '=', 4)->get();

        $titles = CourseTitle::all();

        $participants = $course->participants;
        
        $times = [];
        $start = new \DateTime('09AM');
        $end = new \DateTime('09:01PM');
        $interval = new \DateInterval('PT30M');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $times[] = $dt->format("h:i A");
        }
        return view('portal/courses/create', [
            'coaches' => $coaches,
            'times' => $times,
            'titles' => $titles,
            'participants' => $participants
        ]);
    }

    public function store(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->all();

        $rules = [
            'course.cost' => 'required|numeric',
            'course.start_date' => 'required|date',
            'coaches' => 'required|array|min:1',
            // 'participants' => 'required|array|min:1',
            'times' => 'required|array|min:1',
        ];

        /*foreach ($credentials['participants'] as $key => $coache) {
            $rules['participants.' . $key . '.application_id'] = 'required';
        }*/

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $userId = Auth::id();

        $courseData = $credentials['course'];
        $course = new Course;
        $course->title_id = $courseData['title_id'];
        $course->lab = $courseData['lab'];
        $course->cost = $courseData['cost'];
        $course->start_date = $courseData['start_date'];
        $course->end_date = $courseData['end_date'];
        $course->tournament = !empty($courseData['tournament']);
        if (!empty($courseData['tournament']))
        {
            $course->cost_1 = $courseData['cost_1'];
            $course->cost_2 = $courseData['cost_2'];
            $course->cost_3 = $courseData['cost_3'];
            $course->cost_4 = $courseData['cost_4'];
            $course->cost_5 = $courseData['cost_5'];
        } 
        else
        {
            $course->cost_1 = 0;
            $course->cost_2 = 0;
            $course->cost_3 = 0;
            $course->cost_4 = 0;
            $course->cost_5 = 0;
        }         
        $course->user_id = $userId;

        $course->save();
        $courseId = $course->id;

        foreach ($credentials['coaches'] as $ctcData) {
            $coachesToCourse = new CoachesToCourse;
            $coachesToCourse->course_id = $courseId;
            $coachesToCourse->coach_id = $ctcData;
            $coachesToCourse->user_id = $userId;

            $coachesToCourse->save();
        }

        foreach ($credentials['times'] as $ttcData) {
            $timesToCourse = new TimesToCourse;
            $timesToCourse->course_id = $courseId;
            $timesToCourse->day = $ttcData['day'];
            $timesToCourse->start_time = ($ttcData['start_time']);
            $timesToCourse->end_time = ($ttcData['end_time']);
            $timesToCourse->user_id = $userId;

            $timesToCourse->save();
        }

        foreach ($credentials['participants'] as $stcData) {
            if (isset($stcData['application_id'])) {
                $studentToCourse = new StudentsToCourse;
                $studentToCourse->course_id = $courseId;
                $studentToCourse->application_id = $stcData['application_id'];
                $studentToCourse->paid = $stcData['paid'];
                $studentToCourse->paid_1 = $stcData['paid_1'];
                $studentToCourse->paid_2 = $stcData['paid_2'];
                $studentToCourse->paid_3 = $stcData['paid_3'];
                $studentToCourse->paid_4 = $stcData['paid_4'];
                $studentToCourse->paid_5 = $stcData['paid_5'];
                $studentToCourse->invoice = $stcData['invoice'];
                $studentToCourse->discount = $stcData['discount'];
                $studentToCourse->user_id = $userId;

                $studentToCourse->save();
            }
        }

        return redirect()->route('portal.courses.show', $courseId);
    }

    public function show($id)
    {
        $groupId = Auth::user()->group_id;
        $userId = Auth::id();

        if (in_array($groupId, [1, 2, 3, 4]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        if ($groupId == 4) {

            $coaches = CoachesToCourse::where('coach_id', $userId)->where('course_id', $id)->get();
            if (count($coaches) < 1 || isset($coaches) == false) {
                return redirect()->route('portal.courses.grid');
            }
        }

        $course = Course::with('coaches')->find($id);

        return view('portal/courses/show', [
            'course' => $course
        ]);
    }

    public function edit($id)
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $times = [];
        $start = new \DateTime('09AM');
        $end = new \DateTime('09:01PM');
        $interval = new \DateInterval('PT30M');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $times[] = $dt->format("h:i A");
        }

        $coaches = User::where('group_id', '=', 4)->get();

        $course = Course::find($id);

        $selectedCoaches = array_column(
            $course->coaches->toArray(),
            'coach_id'
        );

        $titles = CourseTitle::all();

        return view('portal/courses/edit', [
            'course' => $course,
            'coaches' => $coaches,
            'times' => $times,
            'titles' => $titles,
            'id' => $id,
            'selectedCoaches' => $selectedCoaches,
        ]);
    }

    public function update($id, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->all();
        // $ids = $credentials['ids'];

        $rules = [
            'course.cost' => 'required|numeric',
            'course.start_date' => 'required|date',
            'coaches' => 'required|array|min:1',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $userId = Auth::id();

        $courseData = $credentials['course'];
        $course = Course::find($id);
        $course->title_id = $courseData['title_id'];
        $course->lab = $courseData['lab'];
        $course->cost = $courseData['cost'];
        $course->start_date = $courseData['start_date'];
        $course->end_date = $courseData['end_date'];
        $course->tournament = !empty($courseData['tournament']);
        if (!empty($courseData['tournament']))
        {
            $course->cost_1 = $courseData['cost_1'];
            $course->cost_2 = $courseData['cost_2'];
            $course->cost_3 = $courseData['cost_3'];
            $course->cost_4 = $courseData['cost_4'];
            $course->cost_5 = $courseData['cost_5'];
        } 
        else
        {
            $course->cost_1 = 0;
            $course->cost_2 = 0;
            $course->cost_3 = 0;
            $course->cost_4 = 0;
            $course->cost_5 = 0;
        }    
        $course->user_id = $userId;

        $course->save();
        $courseId = $course->id;

        CoachesToCourse::where('course_id', $id)->delete();

        foreach ($credentials['coaches'] as $ctcData) {
            $coachesToCourse = new CoachesToCourse;
            $coachesToCourse->course_id = $courseId;
            $coachesToCourse->coach_id = $ctcData;
            $coachesToCourse->user_id = $userId;

            $coachesToCourse->save();
        }

        return redirect()->route('portal.courses.show', $id);
    }

    public function delete($id)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $course = Course::find($id);

        if (!$course) {
            return redirect()->route('portal.courses.grid');
        }

        if ($course->delete()) {
            return redirect()->route('portal.courses.grid');
        }

        return redirect()->route('portal.courses.grid');
    }


    public function destroy($id)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course is not defined'
            ]);
        }

        if ($course->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Course had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }

    public function student(Request $request)
    {
        $groupId = Auth::user()->group_id;
        $userId = Auth::id();

        $term = $request->only(['term']);

        if (isset($term['term'])) {
            $students = Application::with([
                'student' => function ($q) use($term) {
                    $q->with('father');
                }
            ]);

            if ($groupId == 3) {
                $students->where('owner_id', $userId);
            }

            $students->whereHas('student', function ($q) use($term) {
                $q->where(
                    'name',
                    'like',
                    '%' . $term['term'] . '%'
                )->orWhereHas('father', function ($qq) use($term) {
                    $qq->where(
                        DB::raw("CONCAT(students.name, ' ', parents.name)"),
                        'like',
                        '%' . $term['term'] . '%'
                    );
                });
            });

            $students = $students->get();
        } else {

            $students = Application::with([
                'student' => function ($q) {
                    $q->with('father');
                }
            ]);

            if ($groupId == 3) {
                $students->where('owner_id', $userId);
            }

            $students = $students->limit(20)->get();
        }

        $output = [];

        foreach ($students as $key => $student) {
            $output[$student->id] = $student->student->toArray();

            if (isset($student->student->father[0])) {
                $fullName = $student->student->name . ' ' . $student->student->father[0]->name;
            }
            $output[$student->id]['fullName'] = $fullName;
            $output[$student->id]['application'] = $student->toArray();
        }

        return response()->json(['students' => $output]);
    }

    public function getCoachCourses($id){
        $data =  CourseTitle::whereId($id)->with('coaches')->first();

        $output = '';
        foreach($data->coaches as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->name.'</option>';
        }
        echo $output;

    }
}
