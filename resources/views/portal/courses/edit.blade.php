@extends('portal.layout')

@section('headers')
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection
@section('scripts')
<!-- <script src="{{ asset('js/portal/courses.js') }}"></script> -->
<!-- <script src="{{ asset('js/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script> -->
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script>
    var links = {};
    links['students'] = "{{ route('portal.courses.student') }}";
    links['newTime'] = "{{ route('portal.timetocourse.store') }}";
    links['editTime'] = "{{ route('portal.timetocourse.update') }}";
    links['removeTime'] = "{{ route('portal.timetocourse.delete') }}";
    links['newParticipant'] = "{{ route('portal.studenttocourse.store') }}";
    links['editParticipant'] = "{{ route('portal.studenttocourse.update') }}";
    links['removeParticipant'] = "{{ route('portal.studenttocourse.delete') }}";
</script>

<script src="{{ asset('js/portal/course/edit.js') }}"></script>

<script>
    
    $('.confirmDelete').click(function(e) {
        e.preventDefault();
        if (confirm('Are you sure that you want to delete this?')) {
            location.href = $(this).attr('data-href');
        }
    });

    $('input[name="course[tournament]"]').change((e)=>{
        var value = !e.target.checked;
        $('input[name="course[cost_1]"]').prop("disabled", value);
        $('input[name="course[cost_2]"]').prop("disabled", value);
        $('input[name="course[cost_3]"]').prop("disabled", value);
        $('input[name="course[cost_4]"]').prop("disabled", value);
        $('input[name="course[cost_5]"]').prop("disabled", value);
    });
    $('#course').change((e)=>{
        $.ajax({
            url: '/getCoachCourses/' + $('#course').val(),
            method:"get",
            success:function(result)
            {
                $('.coaches').html(result);
            }

        })
    })
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Edit Course</h1>
    </div>
    <div class="col-lg-4" style="text-align: right;">
        <a href="{{ route('portal.courses.attendance', $course['id']) }}"
            class="page-header btn btn-sm btn-info">Course Attendence</a>
        @if ($user['group_id'] == 1)
            <a data-href="{{ route('portal.courses.delete', $course['id']) }}"
                class="confirmDelete page-header btn btn-sm btn-danger">Delete</a>
        @endif
        <a href="{{ route('portal.courses.grid') }}"
            class="page-header btn btn-sm btn-primary">List Courses</a>
    </div>


    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>


@if($errors->any())
<div class="">
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
  @endforeach
</div>
@endif

@include('portal/courses/modals/edit_course_time')

<div class="modal fade" id="newTime"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New time</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newTimeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Day</label>
                        <select class="selectTimes form-control"
                            name="time[day]">
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <select name="time[start_time]"
                                class="selectTimes form-control">
                            @foreach ($times as $time)
                            <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label >End Time</label>
                        <select name="time[end_time]"
                                class="selectTimes form-control">
                            @foreach ($times as $time)
                            <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="course_id" value="{{ $course['id'] }}" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addTime btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newParticipant" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Participant</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newParticipantForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Participant</label>
                        <select class="student student_select" id="student_select"
                            name="participant[application_id]"></select>
                    </div>

                    <div class="form-group">
                        <label>Paid</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid]"
                                 class="form-control" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Registration</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_1]"
                                 class="form-control" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>T-Shirt</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_2]"
                                 class="form-control" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Poster</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_3]"
                                 class="form-control" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Transportation</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_4]"
                                 class="form-control" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Others</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_5]"
                                 class="form-control" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Envoice</label>
                        <input type="text" name="participant[invoice]"
                            class="form-control" />
                    </div>

                    <div class="form-group">
                        <label>Discount</label>
                        <input type="text" name="participant[discount]"
                            class="form-control" />
                    </div>

                    <div class="form-group">
                        <label>Received the books</label>
                        <select class="form-control modalBooks select2FullWidth"
                            name="participant[get_books]">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="course_id" value="{{ $course['id'] }}" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addParticipant btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editParticipant" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Participant</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="editParticipantForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Participant</label>
                        <div class="modalParticipantName"></div>
                    </div>

                    <div class="form-group">
                        <label>Paid</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid]"
                                 class="form-control modalPaid" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                    @if ($course->tournament && $course->tournament == 1)
                    <div class="form-group">
                        <label>Registration</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_1]"
                                 class="form-control modalPaid_1" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>T-Shirt</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_2]"
                                 class="form-control modalPaid_2" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Poster</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_3]"
                                 class="form-control modalPaid_3" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Transportation</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_4]"
                                 class="form-control modalPaid_4" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Others</label>
                        <div class="form-group input-group">
                            <input type="text" name="participant[paid_5]"
                                 class="form-control modalPaid_5" />
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Envoices</label>
                        <span class="small">Seperated by comma</span>
                        <input type="text" name="participant[invoice]"
                            class="form-control modalInvoice" />
                    </div>

                    <div class="form-group">
                        <label>Discount</label>
                        <input type="text" name="participant[discount]"
                            class="form-control modalDiscount" />
                    </div>

                    <div class="form-group">
                        <label>Received the books</label>
                        <select class="form-control modalBooks select2FullWidth"
                            name="participant[get_books]">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="modal_participant_id" name="participant_id" value="" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="updateParticipant btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>



<!-- /.row -->
<div class="row">
    <form method="post" action="{{ route('portal.courses.update', $id) }}">
        {{ csrf_field() }}
        <div class="panel panel-default">
            <div class="panel-heading">
                Course Info
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Course Title</label>
                        <select class="form-control selectWithSearch"
                                name="course[title_id]" id="course">
                            @foreach($titles as $title)
                            <option value="{{ $title['id'] }}"
                                {{ $title['id'] == $course->title->id ? 'selected' : '' }}>
                                {{ $title['title'] }}
                            </option>
                            @endforeach
                        </select>
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Lab</label>
                        <select name="course[lab]" class="form-control select2">
                            <option {{ $course->lab == 1 ? 'selected' : '' }}
                                value="1">1</option>
                            <option {{ $course->lab == 2 ? 'selected' : '' }}
                                value="2">2</option>
                            <option {{ $course->lab == 3 ? 'selected' : '' }}
                                value="3">3</option>
                            <option {{ $course->lab == 4 ? 'selected' : '' }}
                                value="4">4</option>
                            <option {{ $course->lab == 5 ? 'selected' : '' }}
                                value="5">5</option>
                            <option {{ $course->lab == 6 ? 'selected' : '' }}
                                value="6">6</option>
                            <option {{ $course->lab == 7 ? 'selected' : '' }}
                                value="7">7</option>
                        </select>
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Cost</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost]"
                                value="{{ $course->cost }}">
                            <span class="input-group-addon">EGP</span>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label></label>
                        <div class="checkbox">
                            <label><input type="checkbox" value="1" name="course[tournament]" {{ $course->tournament == 1 ? 'checked="checked"' : '' }}>Tournament</label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Registration</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_1]"
                            value="{{ $course->cost_1 }}" {{ $course->tournament != 1 ? 'disabled="true"' : '' }}>
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>T-Shirt</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_2]"
                            value="{{ $course->cost_2 }}" {{ $course->tournament != 1 ? 'disabled="true"' : '' }}>
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Poster</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_3]"
                            value="{{ $course->cost_3 }}" {{ $course->tournament != 1 ? 'disabled="true"' : '' }}>
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Transportation</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_4]"
                            value="{{ $course->cost_4 }}" {{ $course->tournament != 1 ? 'disabled="true"' : '' }}>
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Others</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_5]"
                            value="{{ $course->cost_5 }}" {{ $course->tournament != 1 ? 'disabled="true"' : '' }}>
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>Coaches</label>
                    <select name="coaches[]"
                        class="form-control coaches" multiple="multiple">
                        @foreach ($coaches as $coach)
                        <option value="{{ $coach['id'] }}"
                        {{ in_array($coach['id'], $selectedCoaches) ? 'selected' : '' }}>
                            {{ $coach['name'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Dates
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <label>Start Date</label>
                        <input type="text" name="course[start_date]"
                            class="datetimepicker form-control"
                            value="{{ $course->start_date }}" />
                    </div>
                    <div class="col-md-6">
                        <label>End Date</label>
                        <input type="text" name="course[end_date]"
                            class="datetimepicker form-control"
                            value="{{ $course->end_date }}" />
                    </div>
                </div>
                <table class="table" id="times">
                    <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th class="col-md-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($course->times as $key => $courseTime)
                    <tr data-id="{{ $courseTime['id'] }}">
                        <td class='course-day' data-value="{{ $courseTime->day }}">
                            {{ $courseTime->day }}
                        </td>
                        <td class="start-time" data-value="{{ $courseTime->start_time }}">
                            {{ $courseTime->start_time }}
                        </td>
                        <td class="end-time" data-value="{{ $courseTime->end_time }}">
                            {{ $courseTime->end_time }}
                        </td>
                        <td>
                            <a data-id="{{ $courseTime['id'] }}"
                                href="#editTime" data-toggle="modal"
                                class="btn editTime">
                                <i class="fa fa-gear"></i>
                            </a>
                            <a data-id="{{ $courseTime['id'] }}"
                                class="btn removeTime">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <a class="btn btn-primary newTime">New</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Participants
            </div>

            <div class="panel-body">
                <table class="table" id="participants">
                    <thead>
                    <tr>
                        <th class="col-md-3">Student name</th>
                        <th class="col-md-1">Paid</th>
                        @if ($course->tournament && $course->tournament == 1)
                        <th class="col-md-1">Registration</th>
                        <th class="col-md-1">T-Shirt</th>
                        <th class="col-md-1">Poster</th>
                        <th class="col-md-1">Transportation</th>
                        <th class="col-md-1">Others</th>
                        @endif
                        <th class="col-md-1">Invoice</th>
                        <th class="col-md-1">Discount</th>
                        <th class="col-md-1">Books</th>
                        <th class="col-md-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($course->participants as $participant)
                        @if ($participant->application)
                        <tr data-id="{{ $participant['id'] }}">
                            <td class="participantName">
                                {{ $participant->application->student->name }}
                                @foreach($participant->application->student->s2p as $parent)
                                {{ $parent->parent->type == 1 ? $parent->parent->name : null }}
                                @endforeach
                            </td>
                            <td class='paid'>{{ $participant->paid }}</td>
                            @if ($course->tournament && $course->tournament == 1)
                            <td class='paid_1'>{{ $participant->paid_1 }}</td>
                            <td class='paid_2'>{{ $participant->paid_2 }}</td>
                            <td class='paid_3'>{{ $participant->paid_3 }}</td>
                            <td class='paid_4'>{{ $participant->paid_4 }}</td>
                            <td class='paid_5'>{{ $participant->paid_5 }}</td>
                            @endif
                            <td class='invoice'>{{ $participant->invoice ?: '-' }}</td>
                            <td class='discount'>{{ $participant->discount ?: '-' }}</td>
                            <td data-val="{{ $participant->get_books }}"
                                class='books'>{{ $participant->get_books ? 'Yes' : 'No' }}</td>
                            <td>
                            @if ($user['group_id'] == 3)
                                @if ($participant['owner_id'] == $user['id'])
                                <a data-id="{{ $participant['id'] }}"
                                    href="#editParticipant" data-toggle="modal"
                                    class="btn editParticipant">
                                    <i class="fa fa-gear"></i>
                                </a>
                                <a data-id="{{ $participant['id'] }}"
                                    class="btn removeParticipant">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                                @endif
                            @else
                                <a data-id="{{ $participant['id'] }}"
                                    href="#editParticipant" data-toggle="modal"
                                    class="btn editParticipant">
                                    <i class="fa fa-gear"></i>
                                </a>
                                <a data-id="{{ $participant['id'] }}"
                                    class="btn removeParticipant">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <a class="newParticipant btn btn-primary">New Participant</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary">Submit Button</button>
            <button type="reset" class="btn btn-warning">Reset Button</button>
        </div>
    </form>
</div>

@endsection
