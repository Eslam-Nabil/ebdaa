<?php

namespace App\Http\Controllers\Portal;

use DB;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Models\School;
use App\Models\Course;
use App\Models\Student;
use App\Models\Relative;
use App\Models\Membership;
use App\Models\ParentModel;
use App\Models\Application;
use App\Models\StudentToParent;
use App\Models\StudentsToCourse;
use App\Models\StudentToMembership;
use App\Models\CourseTitle;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $schools = School::get()->toArray();
        $courses = CourseTitle::get()->toArray();

        $months = array(
          1 => 'January', 
          2 => 'February', 
          3 => 'March', 
          4 => 'April', 
          5 => 'May', 
          6 => 'June', 
          7 => 'July', 
          8 => 'August', 
          9 => 'September', 
          10 => 'October', 
          11 => 'November', 
          12 => 'December'
        );

        return view('portal/applications/list', [
            'schools' => $schools,
            'months' => $months,
            'courses' => $courses
        ]);
    }

    public function list(Request $request)
    {
        $groupId = Auth::user()->group_id;
        $userId = Auth::id();
        
        if (in_array($groupId, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->all();

        $start = $credentials['start'] ?: 0;
        $limit = $credentials['length'] ?: 10;

        $searchFor = [];
        // $searchFor = $credentials['search']['value'];
        if (isset($credentials['filter'])) {
            parse_str($credentials['filter'], $searchFor);
            $searchFor = $searchFor['filter'];
        }

        $applications = Application::with([
            'student' => function ($query) {
                return $query->with([
                    'school',
                    'relatives',
                    's2p' => function ($q) {
                        $q->with(['father', 'mother']);
                    },
                    'memberships' => function ($q) {
                        $q->with(['membership']);
                    },
                ]);
            },
            'owner',
            's2c'
        ])->has('student');

        //if ($groupId == 3) {
        //    $applications->where('owner_id', $userId);
        //}

        if (isset($searchFor['classification']) && $searchFor['classification'] != 'all') {
            $applications->where('classification', $searchFor['classification']);
        }

        if (isset($searchFor['course_title_id']) && $searchFor['course_title_id'] != '0') {
            $applications->whereHas('s2c', function ($query) use($searchFor) {            
                $query->whereHas('course', function($subQuery) use($searchFor){
                    $subQuery->where('title_id', '=', $searchFor['course_title_id']);
                });
            });
        }

        if (isset($searchFor['not_course_title_id']) && $searchFor['not_course_title_id'] != '0') {
            $applications->whereDoesntHave('s2c', function ($query) use($searchFor) {            
                $query->whereHas('course', function($subQuery) use($searchFor){
                    $subQuery->where('title_id', '=', $searchFor['not_course_title_id']);
                });
            });
        }

        $applications->whereHas('student', function ($query) use($searchFor) {

            if (isset($searchFor['address_1'])) {
                $query->where('address_1', 'like', '%' . $searchFor['address_1'] . '%');
            }

            if (isset($searchFor['grade'])) {
                $query->where('grade', 'like', '%' . $searchFor['grade'] . '%');
            }            

            if (isset($searchFor['month']) && $searchFor['month'] > 0) {
                $query->whereMonth('dob', '=', $searchFor['month']);
            }

            if (isset($searchFor['yearFrom']) && strlen($searchFor['yearFrom']) > 0) {
                $query->whereYear('dob', '>=', $searchFor['yearFrom']);
            }

            if (isset($searchFor['yearTo']) && strlen($searchFor['yearTo']) > 0) {
                $query->whereYear('dob', '<=', $searchFor['yearTo']);
            }

            if (isset($searchFor['mother']) && strlen($searchFor['mother']) > 0) {
                $query->whereHas('mother', function ($qq) use($searchFor) {
                    $qq->where('name', 'like', '%'.$searchFor['mother'].'%');
                    /*$q->orWhere('job', 'like', '%'.$searchFor.'%');
                    $q->orWhere('phone_1', 'like', '%'.$searchFor.'%');
                    $q->orWhere('email', 'like', '%'.$searchFor.'%');*/
                });
            }

            if (isset($searchFor['phone']) && strlen($searchFor['phone']) > 0) {
                $query->whereHas('father', function ($qq) use($searchFor) {
                    $qq->where('phone_1', 'like', '%'.$searchFor['phone'].'%');
                    $qq->orWhere('phone_2', 'like', '%'.$searchFor['phone'].'%');
                });
                $query->orWhereHas('mother', function ($qq) use($searchFor) {
                    $qq->where('phone_1', 'like', '%'.$searchFor['phone'].'%');
                    $qq->orWhere('phone_2', 'like', '%'.$searchFor['phone'].'%');
                });
            }

            if (isset($searchFor['school_id']) && $searchFor['school_id'] > 0) {
                $query->where('school_id', '=', $searchFor['school_id']);
            }

            if (isset($searchFor['name']) && strlen($searchFor['name']) > 0) {
                $query->whereHas('father', function ($qq) use ($searchFor) {
                    $qq->where(
                        DB::raw("CONCAT(students.name, ' ', parents.name)"),
                        'like',
                        '%' . $searchFor['name'] . '%'
                    );
                });
            }
        });

        $count = $applications->count();

        if ($limit >= 0) {
            $applications = $applications->offset($start)->limit($limit);
        }

        $tmpSql = $applications->toSql();
        $applications = $applications->get();

        return response()->json([
            'data' => $applications,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $schools = School::get()->toArray();
        $memberships = Membership::get()->toArray();
        return view('portal/applications/create', [
            'schools' => $schools,
            'memberships' => $memberships,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->all();

        $rules = [
            'student.name' => 'required|min:3',
            'student.dob' => 'required|date',
            'student.address_1' => 'required|min:3',
        ];

        if (
            !isset($credentials['motherHiddenLookup']) ||
            $credentials['motherHiddenLookup'] < 1
        ) {
            $rules['mother.name'] = 'required';
            $rules['mother.phone_1'] = 'required';
            // $rules['email'] = [
            //     Rule::unique('parents')->where(function ($query) {
            //         $query->where('type', '=', 2);
            //     })
            // ];
        } else {
            $rules['mother'] = 'required';
        }

        if (
            !isset($credentials['fatherHiddenLookup']) ||
            $credentials['fatherHiddenLookup'] < 1
        ) {
            $rules['father.name'] = 'required';
            $rules['father.phone_1'] = 'required';
            // $rules['email'] = [
            //     Rule::unique('parents')->where(function ($query) {
            //         $query->where('type', '=', 1);
            //     })
            // ];
        } else {
            $rules['father'] = 'required';
        }

        if (count($credentials['relatives']) > 0) {

            $credentials['relatives'] = array_filter($credentials['relatives'], function ($v) {
                return $v['name'] != null;
            });

            foreach ($credentials['relatives'] as $key => $coache) {
                $rules['relatives.' . $key . '.name'] = 'required|min:3';
                $rules['relatives.' . $key . '.dob'] = 'required|date';
            }
        }

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $userId = Auth::id();

        $studentData = $credentials['student'];

        if (isset($studentData['photo']) && $studentData['photo'] != null) {
            $path = $request->file('student.photo')->store('avatars', 'public');
        } else {
            $path = 'no-img.gif';
        }

        $student = new Student;
        $student->name = $studentData['name'];
        $student->dob = $studentData['dob'];
        $student->school_id = $studentData['school_id'];
        $student->grade = $studentData['grade'];
        $student->phone = $studentData['phone'];
        $student->address_1 = $studentData['address_1'];
        $student->photo = $path;
        $student->user_id = $userId;

        $student->save();

        $studentId = $student->id;

        if (
            isset($credentials['fatherHiddenLookup']) &&
            $credentials['fatherHiddenLookup'] > 0
        ) {
            $fatherId = $credentials['fatherHiddenLookup'];
        } else {
            $fatherData = $credentials['father'];
            $father = new ParentModel;
            $father->name = $fatherData['name'];
            $father->job = $fatherData['job'];
            $father->phone_1 = $fatherData['phone_1'];
            $father->phone_2 = $fatherData['phone_2'];
            $father->email = $fatherData['email'];
            $father->type = 1;
            $father->user_id = $userId;

            $father->save();

            $fatherId = $father->id;
        }

        if (
            isset($credentials['motherHiddenLookup']) &&
            $credentials['motherHiddenLookup'] > 0
        ) {
            $motherId = $credentials['motherHiddenLookup'];
        } else {
            $motherData = $credentials['mother'];
            $mother = new ParentModel;
            $mother->name = $motherData['name'];
            $mother->job = $motherData['job'];
            $mother->phone_1 = $motherData['phone_1'];
            $mother->phone_2 = $motherData['phone_2'];
            $mother->email = $motherData['email'];
            $mother->type = 2;
            $mother->user_id = $userId;

            $mother->save();

            $motherId = $mother->id;
        }

        $s2p = new StudentToParent;
        $s2p->student_id = $studentId;
        $s2p->parent_id = $fatherId;
        $s2p->save();

        $s2p = new StudentToParent;
        $s2p->student_id = $studentId;
        $s2p->parent_id = $motherId;
        $s2p->save();

        $firstChar = strtoupper($studentData['name'][0]);
        $latestStudent = Application::where(
            'customId',
            'like',
            $firstChar . '%')
        ->orderBy('id', 'DESC')->first();

        $customId = 0;
        if (isset($latestStudent['customId'])) {
            $customId = trim($latestStudent['customId'], $firstChar);
        }
        $customId = $firstChar . ($customId + 1);

        $applicationData = $credentials['additional'];
        $application = new Application;
        $application->customId = $customId;
        $application->student_id = $studentId;
        $application->notes = $applicationData['notes'];
        $application->classification = $applicationData['classification'];
        $application->user_id = $userId;
        $application->owner_id = $userId;

        if (
            isset($credentials['relatives']) &&
            count($credentials['relatives']) > 0)
        {
            $relativesData = array_filter(
                $credentials['relatives'],
                function ($el) {
                    return isset($el['name']) &&
                        is_null($el['name']) === false;
                }
            );

            foreach ($relativesData as $relativeData) {
                $relative = new Relative;

                $relative->student_id = $studentId;
                $relative->name = $relativeData['name'];
                $relative->dob = $relativeData['dob'];
                $relative->school_id = $relativeData['school_id'];
                $relative->grade = $relativeData['grade'];
                $relative->user_id = $userId;

                $relative->save();
            }
        }

        if (
            isset($credentials['memberships']) &&
            count($credentials['memberships']) > 0)
        {
            $membershipsData = array_values(
                array_filter($credentials['memberships'])
            );

            foreach ($membershipsData as $membershipData) {
                $membership = new StudentToMembership;

                $membership->student_id = $studentId;
                $membership->membership_id = $membershipData;

                $membership->save();
            }
        }

        if ($application->save()) {
            return redirect()->route('applications.index');
        }

        /*$validator = Validator::make($credentials, [
            'email' => 'required|unique:users|max:255|email',
            'password' => 'required|min:1|regex:#^[a-z\!\$\#\%0-9\_\-]+$#',
            'name' => 'required|min:5'
        ], [
            'regex' => 'Password MUST contains characters, special character and numbers'
        ]);*/


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $application = Application::where('id', '=', $id)->first();
        $photo = storage_path('app/public') . Storage::url($application->student->photo);
        foreach ($application->student->s2p as $sp) {
            if (isset($sp->mother)) {
                $mother = $sp->mother;
            }
            if (isset($sp->father)) {
                $father = $sp->father;
            }
        }

        $courses = StudentsToCourse::with([
            'course' => function ($q) {
                $q->with(['title']);
            }
        ])
            ->where('application_id', $id)
            ->whereHas('course', function ($q) {
                $q->whereNull('deleted_at');
            })
        ->get();

        return view('portal/applications/show', [
            'application' => $application,
            'mother' => $mother,
            'father' => $father,
            'photo' => $photo,
            'courses' => $courses->toArray()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $schools = School::get()->toArray();
        $memberships = Membership::get()->toArray();
        $application = Application::with(['student' => function ($query) {
            $query->with([
                'school',
                'relatives',
                's2p' => function ($q) {
                    $q->with(['father', 'mother']);
                },
                'memberships' => function ($q) {
                    $q->with(['membership']);
                }
            ]);
        }])->find($id);

        // dd($application->toArray());

        $app = $application->toArray();

        if (count($app['student']['memberships']) > 0) {
            $appMemberships = $app['student']['memberships'];
        } else {
            $appMemberships = null;
        }

        $users = User::where('group_id', 3)->get()->toArray();

        return view('portal/applications/edit', [
            'id' => $id,
            'schools' => $schools,
            'memberships' => $memberships,
            'app' => $app,
            'student' => $app['student'],
            'appMemberships' => $appMemberships,
            's2p' => $app['student']['s2p'],
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->all();

        $ids = $credentials['ids'];

        $rules = [
            'student.name' => 'required|min:3',
            'student.dob' => 'required|date',
            'student.address_1' => 'required|min:3',
        ];

        $rules['mother.name'] = 'required';
        $rules['mother.phone_1'] = 'required';
        // $rules['email'] = [
        //     Rule::unique('parents')->where(function ($query) {
        //         $query->where('type', '=', 2);
        //     })
        // ];

        $rules['father.name'] = 'required';
        $rules['father.phone_1'] = 'required';
        // $rules['email'] = [
        //     Rule::unique('parents')->where(function ($query) {
        //         $query->where('type', '=', 1);
        //     })
        // ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // $userId = Auth::id();

        $studentData = $credentials['student'];

        if (isset($studentData['photo']) && $studentData['photo'] != null) {

            if (strpos($studentData['photo_tmp'], 'no-img.gif') === false) {
                Storage::disk('public')->delete($studentData['photo_tmp']);
            }

            $path = $request->file('student.photo')->store('avatars', 'public');
        } else if (isset($studentData['photo_tmp']) && $studentData['photo_tmp'] != null) {
            $path = $studentData['photo_tmp'];
        } else {
            $path = 'no-img.gif';
        }

        $student = Student::find($ids['student']);
        $student->name = $studentData['name'];
        $student->dob = $studentData['dob'];
        $student->school_id = $studentData['school_id'];
        $student->grade = $studentData['grade'];
        $student->phone = $studentData['phone'];
        $student->address_1 = $studentData['address_1'];
        $student->photo = $path;

        $student->save();
        $studentId = $ids['student'];

        $fatherData = $credentials['father'];
        $father = ParentModel::find($ids['father']);
        $father->name = $fatherData['name'];
        $father->job = $fatherData['job'];
        $father->phone_1 = $fatherData['phone_1'];
        $father->phone_2 = $fatherData['phone_2'];
        $father->email = $fatherData['email'];
        $father->type = 1;
        // $father->user_id = $userId;

        $father->save();
        $fatherId = $ids['father'];

        $motherData = $credentials['mother'];
        $mother = ParentModel::find($ids['mother']);
        $mother->name = $motherData['name'];
        $mother->job = $motherData['job'];
        $mother->phone_1 = $motherData['phone_1'];
        $mother->phone_2 = $motherData['phone_2'];
        $mother->email = $motherData['email'];
        $mother->type = 2;
        // $mother->user_id = $userId;

        $mother->save();
        $motherId = $ids['mother'];

        $applicationData = $credentials['additional'];
        $application = Application::find($id);
        $application->student_id = $studentId;
        $application->notes = $applicationData['notes'];
        $application->classification = $applicationData['classification'];
        if (isset($applicationData['owner_id'])) {
            $application->owner_id = $applicationData['owner_id'];
        }
        // $application->user_id = $userId;

        if ($application->save()) {
            return redirect()->route('applications.edit', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $application = Application::find($id);

        if (!$application) {
            return response()->json([
                'status' => 'error',
                'message' => 'application is not defined'
            ]);
        }

        if ($application->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Application had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }

    public function lookup(Request $request, Response $response)
    {
        $credentials = $request->all();

        $term = $credentials['keyword'];

        if (in_array($credentials['type'], ['mother', 'father'])) {
            if ($credentials['type'] === 'mother') {
                $parent = ParentModel::where('type', '=', 2);
            } else if ($credentials['type'] === 'father') {
                $parent = ParentModel::where('type', '=', 1);
            }
            $parent->where(function($query) use($term) {
                $query->where('name', 'like', "%{$term}%");
                $query->orWhere('phone_1', 'like', "%{$term}%");
                $query->orWhere('phone_2', 'like', "%{$term}%");
                $query->orWhere('job', 'like', "%{$term}%");
                $query->orWhere('email', 'like', "%{$term}%");
            });
            $data = $parent->get()->toArray();
        } else if ($credentials['type'] === 'student') {
            $student = new Student;
            $student->where('name', 'like', "%{$term}%");
            $student->orWhere('school', 'like', "%{$term}%");
            $student->orWhere('grade', 'like', "%{$term}%");
            $student->orWhere('phone', 'like', "%{$term}%");
            $student->orWhere('address_1', 'like', "%{$term}%");

            $data = $student->get()->toArray();
        } else {
            $data = null;
        }

        if (!$data) {
            $data = [
                ['name' => 'Not Found, Insert new record?', 'id' => (int)'0']
            ];
        }
        return response()->json($data);
    }

    public function createClone(Request $request, $appId, $relativeId)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $app = Application::find($appId);
        $student = Student::with([
            'relatives' => function ($q) use($relativeId) {
                $q->where('id', '!=', $relativeId);
            }
        ])->find($app->student_id)->toArray();
        // $tmp['relatives'] = $tmp['student']->relatives();
        $relative = Relative::find($relativeId)->toArray();

        $schools = School::get()->toArray();
        return view('portal/applications/clone', [
            'appId' => $appId,
            'relativeId' => $relativeId,
            'schools' => $schools,
            'relative' => $relative,
            'student' => $student,
            // 'memberships' => $memberships,
            // 'app' => $app,
            // 'student' => $app['student'],
            // 'appMemberships' => $appMemberships,
            // 's2p' => $app['student']['s2p']
        ]);
    }

    public function storeClone(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }
        
        $credentials = $request->all();

        $relativeId = $credentials['ids']['relativeId'];

        $tmp['app'] = Application::find($credentials['ids']['appId']);
        $tmp['student'] = Student::with([
            'relatives' => function ($q) use($relativeId) {
                $q->where('id', '!=', $relativeId);
            },
            'parents',
            's2p' => function ($q) {
                $q->with([
                    'mother','father'
                ]);
            }
        ])->find($tmp['app']->student_id)->toArray();

        $tmp['relative'] = Relative::find($credentials['ids']['relativeId'])
            ->toArray();

        $parents = array_column($tmp['student']['parents'], null, 'type');

        $relatives = [];

        $relatives[] = [
            'name' => $tmp['student']['name'],
            'dob' => $tmp['student']['dob'],
            'school_id' => $tmp['student']['school_id'],
            'grade' => $tmp['student']['grade'],
        ];

        $relatives = array_merge($relatives, $tmp['student']['relatives']);

        $userId = Auth::id();

        $studentData = $credentials['student'];

        $student = new Student;
        $student->name = $studentData['name'];
        $student->dob = $studentData['dob'];
        $student->school_id = $studentData['school_id'];
        $student->grade = $studentData['grade'];
        $student->phone = $studentData['phone'];
        $student->address_1 = $studentData['address_1'];
        $student->photo = 'no-img.gif';
        $student->user_id = $userId;

        $student->save();

        $studentId = $student->id;

        $s2p = new StudentToParent;
        $s2p->student_id = $studentId;
        $s2p->parent_id = $parents[1]['id'];
        $s2p->save();

        $s2p = new StudentToParent;
        $s2p->student_id = $studentId;
        $s2p->parent_id = $parents[2]['id'];
        $s2p->save();

        $firstChar = strtoupper($studentData['name'][0]);
        $latestStudent = Application::where(
            'customId',
            'like',
            $firstChar . '%')
        ->orderBy('id', 'DESC')->first();

        $customId = 0;
        if (isset($latestStudent['customId'])) {
            $customId = trim($latestStudent['customId'], $firstChar);
        }

        $customId = $firstChar . ($customId + 1);

        $applicationData = $credentials['additional'];
        $application = new Application;
        $application->customId = $customId;
        $application->student_id = $studentId;
        $application->referrer = $tmp['student']['id'];
        $application->relative_id = $tmp['relative']['id'];
        $application->notes = $applicationData['notes'];
        $application->classification = $applicationData['classification'];
        $application->user_id = $userId;

        if (
            count($relatives) > 0)
        {

            foreach ($relatives as $relativeData) {
                $relative = new Relative;

                $relative->student_id = $studentId;
                $relative->name = $relativeData['name'];
                $relative->dob = $relativeData['dob'];
                $relative->school_id = $relativeData['school_id'];
                $relative->grade = $relativeData['grade'];
                $relative->user_id = $userId;

                $relative->save();
            }
        }

        if ($application->save()) {
            return redirect()->route('applications.index');
        }
    }
}
