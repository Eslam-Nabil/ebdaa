<?php
namespace App\Http\Controllers\Chat;

use DB;
use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Course;
use App\Models\CoachesToCourse;
use App\Models\StudentsToCourse;
use App\Models\StudentToParent;
use Carbon\Carbon;
use App\User;
use Chat;

class ChatController extends Controller
{

    // Coversation Functions
    public function createConversation(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'userId' => 'required',
            'parentId' => 'required'
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = User::find($credentials['userId']);
        $parent = ParentModel::find($credentials['parentId']);

        $conversation = Chat::createConversation([$user, $parent]);
        
        return response()->json($conversation);
    }    

    public function getConversation(Request $request)
    {
        $credentials = $request->all();
        
        $rules = [
            'Id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } 

        return response()->json(Chat::conversations()->getById($credentials['Id']));
    }

    public function getUserConversations(Request $request)
    {
        $credentials = $request->all();
        
        $rules = [
            'limit' => 'required',
            'page' => 'required',
        ];
        
        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } 

        $conversations = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
                                            ->setParticipant(Auth::user())
                                            ->limit($credentials['limit'])
                                            ->page($credentials['page'])
                                            ->get();

        return response()->json($conversations);
    }

    public function joinConversation(Request $request)
    {
        $credentials = $request->all();
        $rules = [
            'Id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        Chat::conversations()
                ->getById($credentials['Id'])
                ->addParticipants([Auth::user()]);
        return response()->json('1');
    }

    // Users and Parents
    public function login(Request $request)
    {
        $user = Auth::user();
        $user->available = true;
        $user->save();

        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->available = false;
        $user->save();

        return response()->json($user);
    }

    public function getUsers(Request $request)
    {
        $users = User::where('available', true)->get()->all();
        return $users;
    }

    public function getParents(Request $request)
    {
        $parents = ParentModel::where('available', true)->get()->all();
        return $parents;
    }

    // Message Functions
    public function getMessage(Request $request)
    {
        $credentials = $request->all();
        
        $rules = [
            'Id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } 

        return response()->json(Chat::messages()->getById($credentials['Id']));
    }

    public function sendMessage(Request $request)
    {
        $credentials = $request->all();
        
        $rules = [
            'conversationId' => 'required',
            'message' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } 

        $conversation = Chat::conversations()->getById($credentials['conversationId']);
        $from = Auth::user();
        $message = Chat::message($credentials['message'])
                        ->from($from)
                        ->to($conversation)
                        ->send();

        return response()->json($message);
    }

    public function markRead(Request $request)
    {
        $credentials = $request->all();
        
        $rules = [
            'Id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } 

        $participant = Auth::user();
        Chat::messages()->getById($credentials['Id'])
                        ->setParticipant($participant)
                        ->markRead();
        return response()->json('1');
    }

    public function unreadCount(Request $request)
    {
        $credentials = $request->all();
        
        $rules = [
            'Id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } 

        $participant = Auth::user();
        $count = Chat::conversations()
                        ->getById($credentials['Id'])
                        ->setParticipant($participant)
                        ->unreadCount();
        return response()->json($count);
    }

    public function getConversationMessages(Request $request)
    {
        $credentials = $request->all();
        
        $rules = [
            'Id' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } 

        $conversation = Chat::conversations()->getById($credentials['Id']);
        $messages = Chat::conversation($conversation)
                            ->setParticipant(Auth::user())
                            ->getMessages();
        return response()->json($messages);
    }

    // Helper APIs
    public function getCourses(Request $request)
    {
        $user = Auth::user();
        if (get_class($user) == 'App\\User')
        {
            if($user->group_id == 1)
            {
                $courses = Course::with(['participants.application.student.parents', 'title', 'coaches.coach'])->where('end_date', '>=', Carbon::today())->get();
            }
            else
            {
                $courses = Course::with(['participants.application.student.parents', 'title', 'coaches'])
                    ->where('end_date', '>=', Carbon::today())
                    ->whereHas('course.coaches', function ($q) use ($user) {
                        $q->where('coach_id', '=', $$user->id);
                    })->get();
            }
        }
        else
        {
            $courses = Course::with(['participants', 'title', 'coaches.coach'])
                    ->where('end_date', '>=', Carbon::today())
                    ->whereHas('participants.application.student.parents', function ($q) use ($user) {
                        $q->where('parent_id', '=', $user->id);
                    })->get();
        }

        return response()->json($courses);
    }
}