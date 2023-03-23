<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', 'portal/applications');

Route::group(['prefix' => 'portal', 'middleware' => ['auth']], function () {
    Route::permanentRedirect('/', 'portal/applications')->name('portal.home');
    // Route::get('/', 'Portal\CommonController@index')->name('portal.home');
    Route::get('users/browse', 'Portal\UsersController@browse')->name('portal.users.browse');
    Route::get('users', 'Portal\UsersController@index');
    Route::get('users/token', 'Portal\UsersController@generateToken')->name('portal.users.token');

    Route::get(
        'users/insert', 'Portal\UsersController@insert'
    )->name('portal.users.insert');

    Route::post(
        'users/create', 'Portal\UsersController@create'
    )->name('portal.users.create');

    Route::get(
        'users/browse', 'Portal\UsersController@browse'
    )->name('portal.users.browse');

    Route::get(
        'users/list', 'Portal\UsersController@list')
    ->name('portal.users.list');

    Route::get(
        'user/{userId}', 'Portal\UsersController@edit')
    ->name('portal.users.edit');

    Route::get(
        'user/{userId}/delete', 'Portal\UsersController@delete')
    ->name('portal.users.delete');

    Route::post(
        'user/{userId}', 'Portal\UsersController@update')
    ->name('portal.users.update');

    Route::resource(
        'applications', 'Portal\ApplicationController'
    )->except(['destroy']);
    Route::post(
        'application/lookup',
        'Portal\ApplicationController@lookup'
    )->name('portal.applications.lookup');

    Route::get(
        'application/clone/{appId}/{relativeId}',
        'Portal\ApplicationController@createClone'
    )->name('portal.applications.clone.create');

    Route::get(
        'applications/{appId}/destroy',
        'Portal\ApplicationController@destroy'
    )->name('portal.applications.destroy');

    Route::post(
        'application/clone/',
        'Portal\ApplicationController@storeClone'
    )->name('portal.applications.clone.store');

    Route::post(
        'applications/list',
        'Portal\ApplicationController@list'
    )->name('portal.applications.list');

    Route::group(['prefix' => 'schools'], function () {
        Route::get('/', 'Portal\SchoolController@index')
            ->name('portal.schools.index');
        Route::get('list', 'Portal\SchoolController@list')
            ->name('portal.schools.list');
        Route::get('create', 'Portal\SchoolController@create')
            ->name('portal.schools.create');
        Route::post('/', 'Portal\SchoolController@store')
            ->name('portal.schools.store');
        Route::get('/{id}', 'Portal\SchoolController@show')
            ->name('portal.schools.show');
        // Route::get('/{id}/edit', 'Portal\SchoolController@edit')
        //     ->name('portal.schools.edit');
        // Route::post('/{id}', 'Portal\SchoolController@update')
        //     ->name('portal.schools.update');
        Route::get('/{id}/delete', 'Portal\SchoolController@delete')
            ->name('portal.schools.delete');
    });

    Route::group(['prefix' => 'memberships'], function () {
        Route::get('/', 'Portal\MembershipController@index')
            ->name('portal.memberships.index');
        Route::get('list', 'Portal\MembershipController@list')
            ->name('portal.memberships.list');
        Route::get('create', 'Portal\MembershipController@create')
            ->name('portal.memberships.create');
        Route::post('/', 'Portal\MembershipController@store')
            ->name('portal.memberships.store');
        Route::get('/{id}', 'Portal\MembershipController@show')
            ->name('portal.memberships.show');
        // Route::get('/{id}/edit', 'Portal\MembershipController@edit')
        //     ->name('portal.memberships.edit');
        // Route::post('/{id}', 'Portal\MembershipController@update')
        //     ->name('portal.memberships.update');
        Route::get('/{id}/delete', 'Portal\MembershipController@delete')
            ->name('portal.memberships.delete');
    });

    Route::group(['prefix' => 'relatives'], function () {
        Route::post('/store', 'Portal\RelativesController@store')
            ->name('portal.relatives.store');
        Route::post('/delete', 'Portal\RelativesController@delete')
            ->name('portal.relatives.delete');
    });

    Route::group(['prefix' => 'membership/to/application'], function () {
        Route::post('/store', 'Portal\MembershipToApplicationController@store')
            ->name('portal.membershiptoapplication.store');
        Route::post('/delete', 'Portal\MembershipToApplicationController@delete')
            ->name('portal.membershiptoapplication.delete');
    });

    Route::group(['prefix' => 'time/to/course'], function () {
        Route::post('/store', 'Portal\TimeToCourseController@store')
            ->name('portal.timetocourse.store');
        Route::post('/update', 'Portal\TimeToCourseController@update')
            ->name('portal.timetocourse.update');
        Route::post('/delete', 'Portal\TimeToCourseController@delete')
            ->name('portal.timetocourse.delete');
    });

    Route::group(['prefix' => 'student/to/course'], function () {
        Route::post('/store', 'Portal\StudentToCourseController@store')
            ->name('portal.studenttocourse.store');
        Route::post('/update', 'Portal\StudentToCourseController@update')
            ->name('portal.studenttocourse.update');
        Route::post('/delete', 'Portal\StudentToCourseController@delete')
            ->name('portal.studenttocourse.delete');
    });

    Route::group(['prefix' => 'attendance/to/course'], function () {
        Route::post('/store', 'Portal\AttendanceToCourseController@store')
            ->name('portal.attendancetocourse.store');
        Route::post('/update', 'Portal\AttendanceToCourseController@update')
            ->name('portal.attendancetocourse.update');
        Route::post('/delete', 'Portal\AttendanceToCourseController@delete')
            ->name('portal.attendancetocourse.delete');
        Route::post('/note', 'Portal\AttendanceToCourseController@StudentNote')
            ->name('portal.attendancetocourse.note');
    });

    Route::group(['prefix' => 'courseTitles'], function () {
        Route::get('/', 'Portal\CoursesTitlesController@index')
            ->name('portal.courseTitle.index');
        Route::get('list', 'Portal\CoursesTitlesController@list')
            ->name('portal.courseTitle.list');
        Route::get('create', 'Portal\CoursesTitlesController@create')
            ->name('portal.courseTitle.create');
        Route::post('/', 'Portal\CoursesTitlesController@store')
            ->name('portal.courseTitle.store');
        Route::get('/{id}/delete', 'Portal\CoursesTitlesController@delete')
            ->name('portal.courseTitle.delete');
    });

    Route::group(['prefix' => 'marketing/summary'], function () {
        Route::get('/{startDate?}', 'Portal\MarketingSummaryController@monthly')
            ->name('portal.marketingSummary.monthly');
    });

    Route::group(['prefix' => 'courses'], function () {
        Route::get('/', 'Portal\CoursesController@index')
            ->name('portal.courses.index');
        
        Route::get('/grid/{startDate?}', 'Portal\CoursesController@grid')
            ->name('portal.courses.grid');

        Route::get('/student/', 'Portal\CoursesController@student')
            ->name('portal.courses.student');

        Route::get('list', 'Portal\CoursesController@list')
            ->name('portal.courses.list');
        
        Route::get('create', 'Portal\CoursesController@create')
            ->name('portal.courses.create');
        
        Route::post('/', 'Portal\CoursesController@store')
            ->name('portal.courses.store');
        
        Route::get('/{id}', 'Portal\CoursesController@show')
            ->name('portal.courses.show');
        
        Route::get('/{id}/edit', 'Portal\CoursesController@edit')
            ->name('portal.courses.edit');
        
        Route::post('/{id}', 'Portal\CoursesController@update')
            ->name('portal.courses.update');
        
        Route::get('/{id}/delete', 'Portal\CoursesController@delete')
            ->name('portal.courses.delete');

        Route::get('/{id}/attendance', 'Portal\CoursesController@attendance')
            ->name('portal.courses.attendance');

        Route::get('/{id}/copy', 'Portal\CoursesController@createFrom')
            ->name('portal.courses.copy');

        Route::get(
            '/{appId}/destroy',
            'Portal\CoursesController@destroy'
        )->name('portal.courses.destroy');
    });

    Route::get(
        'common/notifications', 'Portal\CommonController@notifications')
    ->name('portal.notifications');

    Route::get('parents/createtoken', 'Portal\ParentsController@createTokenByStudent')->name('portal.parents.createtoken');

    Route::group(['prefix' => 'bus'], function () {
        Route::get('/', 'Portal\BusController@index')
            ->name('portal.bus.index');

        Route::post('driver/create', 'Portal\BusController@addDriver')
            ->name('portal.driver.create');

        Route::post('driver/edit', 'Portal\BusController@updateDriver')
            ->name('portal.driver.edit');

        Route::post('driver/delete', 'Portal\BusController@RemoveDriver')
            ->name('portal.driver.delete');

        Route::post('create', 'Portal\BusController@addBus')
            ->name('portal.bus.create');

        Route::post('edit', 'Portal\BusController@updateBus')
            ->name('portal.bus.edit');

        Route::post('delete', 'Portal\BusController@RemoveBus')
            ->name('portal.bus.delete');

        Route::get('student', 'Portal\BusController@getBusStudents')
            ->name('portal.bus.student');

        Route::post('addstudent', 'Portal\BusController@AddStudentToBus')
            ->name('portal.bus.addstudent');
        
        Route::post('removestudent', 'Portal\BusController@RemoveStudent')
            ->name('portal.bus.removestudent');

        Route::get('journey', 'Portal\BusController@getJourneys')
            ->name('portal.bus.journey');
        
        Route::get('journeys', 'Portal\BusController@Journeys')
            ->name('portal.bus.journeys');
    });
});

Route::group(['prefix' => 'finance'], function () {
    Route::get('/', 'Portal\FinanceController@index')
        ->name('portal.finance.index');
    Route::get('list', 'Portal\FinanceController@list')
        ->name('portal.finance.list');
    Route::get('create', 'Portal\FinanceController@create')
        ->name('portal.finance.create');
    Route::post('/', 'Portal\FinanceController@store')
        ->name('portal.finance.store');
    Route::get('/{id}/delete', 'Portal\FinanceController@delete')
        ->name('portal.finance.delete');
});
    
Route::group(['prefix' => 'Bond'], function () {
    Route::get('/', 'Bond\BondController@index')
        ->name('portal.bond.index');

    Route::get('{id}/view', 'Bond\BondController@view')
        ->name('portal.bond.view');

    // Route::get('list', 'Bond\BondController@list')
    //     ->name('portal.bond.list');

    Route::get('create', 'Bond\BondController@create')
        ->name('portal.bond.create');
    // Route::post('/', 'Bond\BondController@store')
    //     ->name('portal.bond.store');
    // Route::get('/{id}/delete', 'Bond\BondController@delete')
    //     ->name('portal.bond.delete');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/getCoachCourses/{id}', 'Portal\CoursesController@getCoachCourses');