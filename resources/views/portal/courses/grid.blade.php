@extends('portal.layout')

@section('headers')

<link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />

<style>

.course {
    border: 1px solid #fff;
    padding: 5px;
    background: #fff;
    display: block;
    margin-bottom: 10px;
    cursor: pointer;
    border-bottom: 1px solid #ccc;
}

.styledCourse {
    border: 1px solid #0b50ff;
    padding: 5px;
    border-radius: 5px;
    background: #f4fac7;
}

.customizedSmall {
    font-size: 80% !important;
}

.buttons .btn {
    padding-top: 0px;
    padding-bottom: 0px;
}

</style>

@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('js/portal/courses.js') }}"></script>

<script>

$('.months').select2({
    minimumResultsForSearch: 5,
    width: '60%'
});

$('.months').on('select2:select', function (event) {
    var data = event.params.data;

    window.location = '{{ route("portal.courses.grid") }}/' + data.text;
});

$('#courseTimes').on('show.bs.modal', function (e) {

    var $course = JSON.parse($(e.relatedTarget).attr('data-course'));

    $(e.currentTarget).find('.modal-start-date').html($course['start_date']);
    $(e.currentTarget).find('.modal-end-date').html($course['end_date']);

    var $times = '';

    for (i in $course['times']) {
        var $data = $course['times'][i];
        $times += '<tr>';
        $times += '<td>' + $data['day'] + '</td>';
        $times += '<td>' + $data['start_time'] + '</td>';
        $times += '<td>' + $data['end_time'] + '</td>';
        $times += '</tr>';
    }

    $(e.currentTarget).find('.modal-course-times').html($times);
});

$('.course').on('click', function(){
    $('.course').removeClass('styledCourse');

    var $courseId = $(this).attr('data-course-id');

    $('.course-' + $courseId).addClass('styledCourse');
});

</script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-4" style="text-align: right;">
    @if ($user['group_id'] != 4)
        <a href="{{ route('portal.courses.index') }}" class="page-header btn btn-sm btn-primary">List View</a>
        <a href="{{ route('portal.courses.create') }}" class="page-header btn btn-sm btn-primary">Add Course</a>
    @endif
    @if ($user['group_id'] == 1 || $user['group_id'] == 3)
        <a data-toggle="modal" data-target="#marketingSummary" class="page-header btn btn-sm btn-warning">Marketing</a>
    @endif
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<div class="modal fade" id="courseTimes"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Course Times</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newAttendanceForm">
                <div class="modal-body">
                    <div class="form-group col-md-6">
                        <label>Start date</label>
                        <span class="modal-start-date"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label>End date</label>
                        <span class="modal-end-date"></span>
                    </div>

                    <div class="form-group">
                        <label>Times</label>
                        <table class="modal-course-times table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('portal/courses/modals/marketing')


<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading col-md-12">
            <div class="col-md-7">
                List Courses
            </div>

            <div class="heading-nav col-md-5" style="text-align: right;">
                <label class="label-control">Select Month</label>
                <select class="months">
                    @foreach ($months as $month)
                        <option {{ $startDate == $month ? 'selected' : '' }}
                            value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="panel-body">
        <div class="clearfix"></div>
        <h3>Week 1</h3>
            <table class="table table-striped table-bordered" style="margin-top: 10px;">
            <thead>
                <tr>
                    @for ($i = 0; $i <= 7; $i++)
                    <th width="20%">{{ $i == 0 ? '' : 'Lab ' . $i }}</th>
                    @endfor
                </tr>
            </thead>
            @php($dayInWeek = 1)
            @php($weekNum = 1)
            @foreach($timesArray as $key => $_timeArray)

            @if (count($_timeArray) > 0)
            <tr>
                <td>{{ date('D m-d', strtotime($key)) }}</td>
                @for ($i = 1; $i <= 7; $i++)
                    <td width="20%">
                        @if(isset($_timeArray[$i]) and is_array($_timeArray[$i]))
                        @php($__timeArray = $_timeArray[$i])
                            @foreach($__timeArray as $timeArray)
                            <div class="course course-{{$timeArray['course']['id']}}"
                                data-course-id="{{$timeArray['course']['id']}}"
                                style="padding: 0px;">
                                <div class="col-md-9"
                                    href="#courseTimes" data-toggle="modal"
                                    data-course='{!! json_encode($timeArray["course"]) !!}'>
                                    <div style="font-weight: bold; color: tomato;">
                                        {{ $timeArray['course']['title']['title'] }}
                                    </div>
                                    <div class="customizedSmall">
                                        {{ $timeArray['start_time'] }} - {{ $timeArray['end_time'] }}
                                    </div>
                                    <div class="customizedSmall">
                                        Coaches:
                                        @foreach ($timeArray['course']['coaches'] as $coach)
                                        <span style="color: #1466b0; font-weight: bold;">
                                        {{ $coach['coach']['name'] }}
                                        </span>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-3 buttons">
                                @if ($user['group_id'] != 4)
                                    <a class="btn fa fa-eye" title="View"
                                    href="{{ route('portal.courses.show', $timeArray['course']['id']) }}">
                                    </a>
                                    <a class="btn fa fa-edit" title="Edit"
                                    href="{{ route('portal.courses.edit', $timeArray['course']['id']) }}">
                                    </a>
                                @endif
                                <a class="btn fa fa-users" title="Attendance"
                                    href="{{ route('portal.courses.attendance', $timeArray['course']['id']) }}">
                                </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                @endfor
            </tr>
            @else
            <tr>
                <td>{{ date('D m-d', strtotime($key)) }}</td>
                @for ($i = 1; $i <= 4; $i++)
                    <td width="20%">
                        -
                    </td>
                @endfor
            </tr>
            @endif

            @if ($dayInWeek % 7 == 0)
            @php ($weekNum = $weekNum + 1)
            </table>
            <h3>Week {{ $weekNum }}</h3>
            <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    @for ($i = 0; $i <= 4; $i++)
                    <th width="20%">{{ $i == 0 ? '' : 'Lab ' . $i }}</th>
                    @endfor
                </tr>
            </thead>
            @endif

            @php($dayInWeek = $dayInWeek + 1)
            @endforeach
        </div>
    </div>
</div>
@endsection
