<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Membership;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/memberships/list');
    }

    public function browse(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        return view('portal/memberships/list');
    }

    public function list()
    {
        if (in_array(Auth::user()->group_id, [1, 2 , 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $users = Membership::select(['id', 'name'])->get();
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }
        $credentials = $request->only(['membership_name']);

        $validator = Validator::make($credentials, [
            'membership_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $membership = new Membership;

        $membership->name = $credentials['membership_name'];
        $membership->user_id = Auth::id();

        if ($membership->save()) {
            return response()->json([
                'status' => 1,
                'message' => 'Membership had been registered successfully'
            ]);
        }

        return response()->json([
            'status' => 0,
            'errors' => ['general' => ['unexcpected error']]
        ]);
    }

    public function view($userId, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $user = Membership::where('id', $userId);

        return view('portal/memberships/view', [
            'user' => $user->first()->toArray(),
            'crumbs' => [
                ['text' => 'Portal', 'href' => 'portal.home'],
                ['text' => 'Customers', 'href' => 'portal.memberships.browse']
            ]
        ]);
    }

    public function delete($userId, Request $request)
    {
        if (in_array(Auth::user()->group_id, [1]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $membership = Membership::find($userId);

        if (!$membership) {
            return response()->json([
                'status' => 'error',
                'message' => 'school is not defined'
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
