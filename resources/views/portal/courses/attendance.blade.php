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
    links['editAttendance'] = "{{ route('portal.attendancetocourse.update') }}";

    function ShowStudentNote(id, note)
    {        
        $("#StudentNoteForm .attendance_note").val(note);
        $("#StudentNoteForm input[name='attendance_id']").val(id);
        $("#StudentNote").modal('toggle');
    }

    $(".StudentNoteBtn").click( () => {
        $.post(
            "{{ route('portal.attendancetocourse.note') }}",
            {
                id: $("#StudentNoteForm input[name='attendance_id']").val(),
                note: $("#StudentNoteForm .attendance_note").val(),
            },
            function(response)
            {
                $("#StudentNote").modal('hide');

                if (response.id != null)
                {                    
                    $(`a[data-id=${response.id}]`).attr("href", `javascript:ShowStudentNote(${response.id}, '${response.notes}');`)
                }
            }
        )
    })
</script>

<script src="{{ asset('js/portal/course/attendance.js') }}"></script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="page-header">
            <h1>{{ $course->title->title }}</h1>
            <div>Coaches: <span style="color: #1466b0; font-weight: bold;">{{ $coaches }}</span></div>
        </div>
    </div>
    <div class="col-lg-4" style="text-align: right;">
        @if(isset($days[0]))
        <button type="button" 
                href="#newAttendance" data-toggle="modal"
                class="btn btn-xs btn-info page-header">New Attendance</button>
        @endif
        <a href="{{ route('portal.courses.grid') }}" 
            class="page-header btn btn-xs btn-primary">Grid View</a>
        <a href="{{ route('portal.courses.edit', $courseId) }}"
            class="page-header btn btn-xs btn-primary">Edit Course</a>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<div class="modal fade" id="StudentNote"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Student Note</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="StudentNoteForm">
                <div class="modal-body"> 
                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="attendance[notes]" class="attendance_note form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="attendance_id" value="" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="StudentNoteBtn btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
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
                            @foreach ($filteredDays as $key => $day)
                            <option value="{{ $day['date'] }}"
                                {{ $loop->first ? 'selected' : '' }}
                                data-id="{{ $day['key'] }}">
                                {{ $day['date'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Attendance</label>
                        <select name="attendance[participants][]" multiple="multiple"
                                class="selectParticipants form-control">
                            @foreach ($course->participants as $participant)
                            @if (isset($participant->application->id))
                            <option value="{{ $participant->application->id }}">
                                {{ $participant->application->student->name }}
                                {{ $participant->application->student->father[0]->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="attendance[notes]" class="attendance_note form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="course_id" value="{{ $courseId }}" />
                <input type="hidden" name="time_to_course_id" id="time_to_course_id"
                    value="{{ isset($days[0]) ? $days[0]['key'] : '' }}" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addAttendance btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAttendance"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Attendance</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="editAttendanceForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Attendance</label>
                        <select name="attendance[participants][]" multiple="multiple"
                                class="selectParticipants form-control">
                            @foreach ($course->participants as $participant)
                            @if (isset($participant->application->id))
                            <option value="{{ $participant->application->id }}">
                                {{ $participant->application->student->name }}
                                {{ $participant->application->student->father[0]->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="attendance[notes]" id="attendance_note" class="form-control">
                            
                        </textarea>
                    </div>
                </div>
                <input type="hidden" name="attendance_id" id="attendance_id" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="updateAttendance btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@if(isset($days[0]) == false)
<div class="row">
    <div class="alert alert-danger">
        You should add some course dates to this course
    </div>
</div>
@endif

<!-- /.row -->
<div class="row">
    <div class="col-lg-12" style="white-space: nowrap;">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <th class="col-md-3 text-center" style="vertical-align: middle;">Name</th>
                @foreach ($days as $key => $day)
                <th class="text-center">
                    {{ $key + 1 }}
                    <hr />
                    {{ $day['month'] . '/' . $day['day'] }}
                </th>
                @endforeach
            </thead>
            <tbody>
                @foreach ($course['participants'] as $participant)
                @if (isset($participant->application->student))
                <tr>
                    <td>
                        {{ $participant->application->student->name }}
                        {{ $participant->application->student->father[0]->name }}
                    </td>
                    @foreach ($days as $key => $day)
                    <td class="text-center">
                    @if ($maxAttendanceDate >= $day['timestamp'])
                        @if (isset($attendances[$participant->application->id][$day['timestamp']]))
                            <span class="fa fa-check" style="color: green; cursor: default;"></span>
                            <a data-id="{{ $attendances[$participant->application->id][$day['timestamp']]['id'] }}" 
                                class="fa fa-sticky-note" 
                                href="javascript:ShowStudentNote({{ $attendances[$participant->application->id][$day['timestamp']]['id']}}, '{{ $attendances[$participant->application->id][$day['timestamp']]['notes']}}');"></a>
                        @else
                            <span class="fa fa-close" style="color: red; cursor: default;"></span>
                        @endif

                    @else
                    -
                    @endif
                    </td>
                    @endforeach
                </tr>
                @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Notes</td>
                    @foreach ($days as $key => $day)
                    <td class="text-center small">
                        @if ($maxAttendanceDate >= $day['timestamp'])
                            @if (isset($notes[$day['timestamp']]))
                            <a class="notesModal"
                                data-id="{{ $notes[$day['timestamp']]['attendance_id'] }}"
                                data-note="{{ $notes[$day['timestamp']]['note'] }}"
                                data-attendances='@json($notes[$day["timestamp"]]["attendances"])'
                                href="#editAttendance" data-toggle="modal">
                                {{ substr($notes[$day['timestamp']]['note'], 0, 10) }} ...
                            </a>
                            @endif
                        @endif
                    </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection
