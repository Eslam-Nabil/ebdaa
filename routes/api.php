<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Portal\BusController;
use Musonza\Chat;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('Chat')->prefix('chat')->middleware('auth:api,parent')->group(function () {
    Route::post("conversation/create", "ChatController@createConversation");
    Route::get("conversation/id", "ChatController@getConversation");
    Route::get("conversation/user", "ChatController@getUserConversations");
    Route::post("conversation/join", "ChatController@joinConversation");

    Route::post("login", "ChatController@login");
    Route::post("logout", "ChatController@logout");
    Route::get("user/all", "ChatController@getUsers");
    Route::get("parent/all", "ChatController@getParents");

    Route::get("message/id", "ChatController@getMessage");
    Route::post("message/send", "ChatController@sendMessage");
    Route::post("message/markRead", "ChatController@markRead");
    Route::get("message/count", "ChatController@unreadCount");
    Route::get("message/conversation", "ChatController@getConversationMessages");

    Route::get("course/all", "ChatController@getCourses");
});

Route::namespace('App')->prefix('app')->middleware('auth:api,parent')->group(function () {
    Route::get("schedule", "ScheduleController@GetMonthSchedule");
    Route::get("map", "MapController@GetMap");
    Route::get("map/bus", "MapController@GetCurrentJourneys");
    Route::post("map/home", "MapController@SetHomeLocation");
});

Route::prefix('driver')->middleware('auth:api,driver')->group(function () {
    Route::get("", "Portal\BusController@GetDriverBus");
    Route::get("journey/start", "Portal\BusController@StartJourney");
    Route::get("journey/stop", "Portal\BusController@StopJourney");
    Route::get("journey/update", "Portal\BusController@UpdateJourney");
    Route::get("journey/student/add", "Portal\BusController@AddStudentToJourney");
    Route::get("journey/student/remove", "Portal\BusController@RemoveStudentFromJourney");
});
