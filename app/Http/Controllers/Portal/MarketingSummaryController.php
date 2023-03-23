<?php
namespace App\Http\Controllers\Portal;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Course;
use App\Models\StudentsToCourse;

class MarketingSummaryController extends Controller
{
    public function monthly(Request $request, $startDate)
    {
        $groupId = Auth::user()->group_id;
        $userId = Auth::id();

        if (in_array($groupId, [1, 3]) == false) {
            return redirect()->route('portal.courses.grid');
        }

        $startDate = $startDate ?: date('Y-m', time());

        $endMonths = Course::selectRaw('DATE_FORMAT(end_date, "%Y-%m") as endDate')
            ->groupBy('endDate');

        $months = Course::selectRaw('DATE_FORMAT(start_date, "%Y-%m") as startDate')
            ->groupBy('startDate')->union($endMonths)->get();

        $currentMonth = date('Y-m', time());

        $months = array_column($months->toArray(), 'startDate', 'startDate');

        $months[$currentMonth] = $currentMonth;

        // dd($months->toArray());

        /*$studentsToCoursesSummary = StudentsToCourse::with([
            'application' => function ($q) {
                $q->with([
                    'owner',
                    'student' => function ($q) {
                        $q->with('father');
                    }
                ]);
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
                    'student' => function ($q) {
                        $q->with('father');
                    }
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

            $marketingSummaries[$marketerId]['id'] = $marketerId;
            $marketingSummaries[$marketerId]['name'] = $marketerName;
            $marketingSummaries[$marketerId]['applications'][] = [
                'student' => implode(' ', [
                    $student2Course['application']['student']['name'],
                    $student2Course['application']['student']['father'][0]['name'],
                ]),
                'paid' => $student2Course['paid'],
                'course_id' => $student2Course['course_id'],
            ];
            $marketingSummaries[$marketerId]['paid'][] = $student2Course['paid'];

        }

        $marketingSummary = [];

        foreach ($marketingSummaries as $id => &$mms) {
            $mms = [
                'id' => $mms['id'],
                'name' => $mms['name'],
                'applications' => $mms['applications'],
                'paid' => array_sum($mms['paid']),
                'students' => count($mms['paid'])
            ];
        }

        // dd($marketingSummaries);

        return view('portal/marketing/monthly', [
            'startDate' => $startDate,
            'months' => $months,
            'summaries' => $marketingSummaries,
        ]);
    }
}
