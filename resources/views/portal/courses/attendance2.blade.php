@extends('portal.layout')

@section('headers')

<link rel="stylesheet" href="{{ asset('css/DataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />

<style>
    
.attendance > div > div:not(:last-child) {
    margin-bottom: 30px;
}

.student {
    border: 1px solid #ccc;
    margin: 5px 10px;
    padding: 5px 10px;
    border-radius: 2px;
    font-weight: bold;
    display: inline-block;
}

.student-in {
    color: #1466b0;
}

.student-out {
    color: tomato;
}

</style>

@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script>
    var links = {};
    links['newAttendance'] = "{{ route('portal.attendancetocourse.store') }}";
</script>

<script src="{{ asset('js/portal/course/attendance.js') }}"></script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-4" style="text-align: right;">
        <button type="button" 
                href="#newAttendance" data-toggle="modal"
                class="addTime btn btn-primary page-header">New Attendance</button>
        <a href="{{ route('portal.courses.grid') }}" class="page-header btn btn-primary">Grid View</a>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<div class="modal fade" id="newAttendance"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Attendance</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newAttendanceForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Day</label>
                        <select class="selectAttendanceDay form-control"
                            name="attendance[attendance_date]">
                            @foreach ($days as $key => $day)
                            <option value="{{ $day['day'] }}"
                                {{ $loop->first ? 'selected' : '' }}
                                data-id="{{ $day['key'] }}">
                                {{ $day['day'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Attendance</label>
                        <select name="attendance[participants][]" multiple="multiple"
                                class="selectParticipants form-control">
                            @foreach ($course->participants as $participant)
                            <option value="{{ $participant->application->id }}">
                                {{ $participant->application->student->name }}
                                {{ $participant->application->student->father[0]->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="attendance[notes]" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="course_id" value="{{ $courseId }}" />
                <input type="hidden" name="time_to_course_id" id="time_to_course_id"
                    value="{{ $days[0]['key'] }}" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addAttendance btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
    @foreach($course->attendanceToCourse as $attendance)
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ $attendance['attendance_date'] }}
            </div>
            <!-- /.panel-heading -->
            
            <div class="panel-body">
                <div class="attendance col-md-12">
                    <div class="col-md-8">
                        <div class="col-md-12">
                        <h4>In</h4>
                            @foreach($attendance['participants'] as $participant)
                            <div class='student student-in'>
                            {{ $participant->application->student->name }}
                            {{ $participant->application->student->father[0]->name }}
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-12">
                        <h4>Out</h4>
                            @php ($participantsApplications = array_column($attendance['participants']->toArray(), 'application_id'))
                            @foreach($course->participants as $key => $participant)
                            @if(in_array($participant->application_id, $participantsApplications) == false)
                            <div class='student student-out'>
                            {{ $participant->application->student->name }}
                            {{ $participant->application->student->father[0]->name }}
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
                    <h4>Notes</h4>
                        {{ $attendance['notes'] ?: '-' }}
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        @endforeach
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection
