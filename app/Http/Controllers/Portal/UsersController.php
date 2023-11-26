<?php
namespace App\Http\Controllers\Portal;

use App\Models\CourseTitle;
use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\UserGroup;

class UsersController extends Controller
{
    public function index()
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/users/list');
    }

    public function browse()
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/users/list');
    }

    public function list()
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $users = User::select(['id', 'email', 'code'])->get();
        return response()->json(['data' => $users]);
    }

    public function insert()
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $userGroups = UserGroup::all();
        $courses = CourseTitle::select(['id', 'title'])->get();

        return view('portal/users/insert', [
            'groups' => $userGroups,
            'courses' => $courses
        ]);
    }

    public function create(Request $request)
    {

        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->only(['email', 'password', 'name', 'group', 'course']);

        $validator = Validator::make($credentials, [
            'email' => 'required|unique:users|max:255|email',
            'password' => 'required|min:1|regex:#^[a-z\!\$\#\%0-9\_\-]+$#',
            'name' => 'required|min:5'
        ], [
                'regex' => 'Password MUST contains characters, special character and numbers'
            ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = new User;

        $user->email = $credentials['email'];
        $user->password = bcrypt($credentials['password']);
        $user->name = $credentials['name'];
        $user->group_id = $credentials['group'];
        if ($user->save()) {
            if ($user->group_id == 4)
                $user->courses()->sync($credentials['course']);
            return redirect()->route('portal.users.edit', $user->id);
        }

        return back()->withError([
            'errors' => 'unexcpected error'
        ]);
    }

    public function edit($userId, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $user = User::find($userId);

        $userGroups = UserGroup::all();
        $courses = CourseTitle::select(['id', 'title'])->get();
        //        $selectedCourses    = $user->courses->toArray();
        $selectedCourses = array_column(
            $user->courses->toArray(),
            'id'
        );

        return view('portal/users/edit', [
            'userData' => $user,
            'groups' => $userGroups,
            'courses' => $courses,
            'selectedCourses' => $selectedCourses,
            'crumbs' => [
                ['text' => 'Admin', 'href' => 'portal.home'],
                ['text' => 'Users', 'href' => 'portal.users.browse']
            ]
        ]);
    }

    public function update($userId, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $credentials = $request->only(['email', 'password', 'name', 'group', 'course']);

        $rules = [
            'name' => 'required|min:5',
        ];

        if ($credentials['password']) {
            $rules['password'] = 'required|min:1|regex:#^[a-z\!\$\#\%0-9\_\-]+$#';
        }

        $user = User::find($userId);

        if ($user->email != $credentials['email']) {
            $rules['email'] = 'required|unique:users|max:255|email';
        } else {
            $rules['email'] = 'required|max:255|email';
        }

        $validator = Validator::make($credentials, $rules, [
            'regex' => 'Password MUST contains characters, special character and numbers'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user->email = $credentials['email'];
        if ($credentials['password']) {
            $user->password = bcrypt($credentials['password']);
        }
        $user->name = $credentials['name'];
        $user->group_id = $credentials['group'];
        //        $user->course_id = $credentials['course'];
        if ($user->save()) {
            if ($user->group_id == 4)
                $user->courses()->sync($credentials['course']);

            return redirect()->route('portal.users.edit', $userId);
        }

        return back()->withError([
            'errors' => 'unexcpected error'
        ]);
    }

    public function delete($userId, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.users.browse');
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'user is not defined'
            ]);
        }

        if ($user->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'user had been deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'unexpected error'
        ]);
    }

    public function generateToken(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2, 3]) == false) {
            response()->json(['error' => 'Unauthenticated'], 401);
        }

        $credentials = $request->all();
        $rules = [
            'id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $id = $credentials['id'];
        User::find($id)->generateToken();
        return back()->withInput();
    }
    public function profile()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $total_in = $wallet->where('type', 'in')->sum('amount');
        $total_out = $wallet->where('type', 'out')->sum('amount');
        return view('portal/users/view', compact('total_in', 'total_out', 'wallet'));
    }
}