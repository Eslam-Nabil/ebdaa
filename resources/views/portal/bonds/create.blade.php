@extends('portal.layout')

@section('headers')
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection
@section('scripts')
<!-- <script src="{{ asset('js/portal/courses.js') }}"></script> -->
<!-- <script src="{{ asset('js/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script> -->
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script>
    $('.datetimepicker').datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: '1990:2030'
    });

    $('.select2').select2({
        minimumResultsForSearch: -1
    });

    $('.selectWithSearch').select2({
        minimumResultsForSearch: 5
    });

    $('.selectTimes').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    function cloneMe(selector) {
        var $table = $(selector).parents('table');

        var $row = $table.find('tbody tr:last-child');

        $row.find('.selectTimes').select2('destroy');

        var $copy = $row.clone(true);

        $copy.find('[name]').each(function () {

            var $newName = this.name.replace(
                /times\[(\d+)\]/g,
                function (match, index) {
                    var index = parseInt(index) + 1;
                    return 'times[' + (index) + ']';
                }
            );

            this.name = $newName;
            this.value = '';
        });

        $row.after($copy);

        if ($table.find('tbody tr').length > 1) {
            $('.removeTime').removeClass('disabled');
        } else {
            $('.removeTime').addClass('disabled');
        }

        $table.find('.selectTimes').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });
    }

    $('.coaches').select2();

    $('.student').select2({
        minimumResultsForSearch: 5,
        minimumInputLength: 1,
        width: '100%',
        ajax: {
            url: links['students'],
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: $.map(data.students, function (s, i) {
                        return {
                            id: s.application.id,
                            text: s.fullName
                        }
                    })
                };
            }
        },
    });

    $('.student').on('select2:select', function (event) {
        $(event.target).parents('tr').find('.tmp-participant').val(event.params.data['text']);
    });

    $('.new-participant').click(function() {
        var $rt = $(this).parents('.table');

        var $row = $rt.find('tbody tr:last-child');

        var currentStudent = $row.find('select').val();

        if (currentStudent == null || currentStudent < 1) {

            alert("You should select a student before adding new one");

            $row.find('.select2-selection').css('border-color', 'tomato');

            return;
        }

        var $dataId = $row.data('ref');

        var $id = $row.attr('id');

        $row.find('select').select2('destroy');

        var refId = (parseInt($dataId) + 1);

        var $copy = $row.clone(true)
            .attr('id', 'student_' + (parseInt($dataId) + 1))
            .attr('data-ref', (parseInt($dataId) + 1));

        $copy.find('[name]').each(function () {

            var $newName = this.name.replace(
                /participants\[(\d+)\]/g,
                function (match, index) {
                    var index = parseInt(index) + 1;
                    return 'participants[' + (index) + ']';
                }
            );

            this.name = $newName;
            this.value = '';
        });

        $copy.find('.student').attr('id', 'student_select_' + (refId));

        // $copy.find('.select2.select2-container').remove();
        // $copy.find('select').select2('destroy');

        $row.after($copy);
        // $copy.appendTo();

        if ($rt.find('tbody tr').length > 1) {
            $('.removeParticipant').removeClass('disabled');
        } else {
            $('.removeParticipant').addClass('disabled');
        }

        $('.student').select2({
            minimumResultsForSearch: 5,
            minimumInputLength: 1,
            width: '100%',
            ajax: {
                url: links['students'],
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data.students, function (s, i) {
                            return {
                                id: s.application.id,
                                text: s.fullName
                            }
                        })
                    };
                }
            },
        });

        $('.student').on('select2:select', function (event) {
            $(event.target).parents('tr').find('.tmp-participant').val(event.params.data['text']);
        });
        // console.log($rt.find('tbody tr:last-child'));
    });

    $('.removeParticipant').click(function () {
        $(this).parent().parent().remove();
        if ($('#participants').find('tbody tr').length == 1) {
            $('.removeParticipant').addClass('disabled');
        }
    });

    $('.removeTime').click(function () {
        $(this).parent().parent().remove();
        if ($('#times').find('tbody tr').length == 1) {
            $('.removeTime').addClass('disabled');
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
    <div class="col-lg-10">
        <h1 class="page-header">New Course</h1>
    </div>
    <div class="col-lg-2">
        <a href="{{ route('portal.courses.grid') }}" class="page-header btn btn-primary">List Courses</a>
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



<!-- /.row -->
<div class="row">
    <form method="post" action="{{ route('portal.courses.store') }}">
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
                            <option {{ old('course.title_id') == $title['id'] ? 'selected' : '' }}
                                value="{{ $title['id'] }}">
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
                            <option {{ old('course.lab') == '1' ? 'selected' : '' }}
                            value="1">1</option>
                            <option {{ old('course.lab') == '2' ? 'selected' : '' }}
                            value="2">2</option>
                            <option {{ old('course.lab') == '3' ? 'selected' : '' }}
                            value="3">3</option>
                            <option {{ old('course.lab') == '4' ? 'selected' : '' }}
                            value="4">4</option>
                            <option {{ old('course.lab') == '5' ? 'selected' : '' }}
                            value="5">5</option>
                            <option {{ old('course.lab') == '6' ? 'selected' : '' }}
                            value="6">6</option>
                            <option {{ old('course.lab') == '7' ? 'selected' : '' }}
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
                            value="{{ old('course.cost') }}">
                            <span class="input-group-addon">EGP</span>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label></label>
                        <div class="checkbox">
                            <label><input type="checkbox" value="1" name="course[tournament]" {{ old('course.tournament') == 1 ? 'checked="checked"' : '' }}>Tournament</label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Registration</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_1]"
                            value="{{ old('course.cost_1') }}" disabled="true">
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>T-Shirt</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_2]"
                            value="{{ old('course.cost_2') }}" disabled="true">
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Poster</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_3]"
                            value="{{ old('course.cost_3') }}" disabled="true">
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Transportation</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_4]"
                            value="{{ old('course.cost_4') }}" disabled="true">
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Others</label>
                        <div class="input-group">
                            <input class="form-control" name="course[cost_5]"
                            value="{{ old('course.cost_5') }}" disabled="true">
                            <span class="input-group-addon">EGP</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>Coaches</label>
                    <select name="coaches[]"
                        class="form-control coaches" multiple="multiple">
                        @foreach ($coaches as $k => $coach)
                        <option {{ old('coaches') && in_array($coach['id'], old('coaches')) ? 'selected' : '' }}
                        {{-- old('coaches.' . $k) == $coach['id'] ? 'selected' : '' --}}
                        value="{{ $coach['id'] }}">
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
                        <input type="text" name="course[start_date]" value="{{ old('course.start_date') }}"
                            class="datetimepicker form-control" />
                    </div>
                    <div class="col-md-6">
                        <label>End Date</label>
                        <input type="text" name="course[end_date]" value="{{ old('course.end_date') }}"
                            class="datetimepicker form-control" />
                    </div>
                </div>
                <table class="table" id="times">
                    <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th class="col-md-1"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (old('times'))
                    @foreach (old('times') as $k => $t)
                    <tr>
                        <td class='courseTime'>
                            <select class="selectTimes form-control"
                                name="times[{{ $k }}][day]">
                                <option {{ $t['day'] == 'saturday' ? 'selected' : '' }}
                                value="saturday">Saturday</option>
                                <option {{ $t['day'] == 'sunday' ? 'selected' : '' }}
                                value="sunday">Sunday</option>
                                <option {{ $t['day'] == 'monday' ? 'selected' : '' }}
                                value="monday">Monday</option>
                                <option {{ $t['day'] == 'tuesday' ? 'selected' : '' }}
                                value="tuesday">Tuesday</option>
                                <option {{ $t['day'] == 'wednesday' ? 'selected' : '' }}
                                value="wednesday">Wednesday</option>
                                <option {{ $t['day'] == 'thursday' ? 'selected' : '' }}
                                value="thursday">Thursday</option>
                                <option {{ $t['day'] == 'friday' ? 'selected' : '' }}
                                value="friday">Friday</option>
                            </select>
                        </td>
                        <td class="form-group">
                            <select name="times[{{ $k }}][start_time]"
                                    class="selectTimes form-control">
                                @foreach ($times as $time)
                                <option {{ $t['start_time'] == $time ? 'selected' : '' }}
                                value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="form-group">
                            <select name="times[{{ $k }}][end_time]"
                                    class="selectTimes form-control">
                                @foreach ($times as $time)
                                <option {{ $t['end_time'] == $time ? 'selected' : '' }}
                                value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <a class="btn btn-danger disabled removeTime">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class='courseTime'>
                            <!-- <input type="text" class="form-control student" data-ref="0" /> -->
                            <select class="selectTimes form-control"
                                name="times[0][day]">
                                <option value="saturday">Saturday</option>
                                <option value="sunday">Sunday</option>
                                <option value="monday">Monday</option>
                                <option value="tuesday">Tuesday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="thursday">Thursday</option>
                                <option value="friday">Friday</option>
                            </select>
                        </td>
                        <td class="form-group">
                            <select name="times[0][start_time]"
                                    class="selectTimes form-control">
                                @foreach ($times as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="form-group">
                            <select name="times[0][end_time]"
                                    class="selectTimes form-control">
                                @foreach ($times as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <a class="btn btn-danger disabled removeTime">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <a onclick="cloneMe(this)" class="btn btn-primary">New</a>
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
                        <th class="col">Student name</th>
                        <th class="col-md-1">Paid</th>
                        <th class="col-md-1">Registration</th>
                        <th class="col-md-1">T-Shirt</th>
                        <th class="col-md-1">Poster</th>
                        <th class="col-md-1">Transportation</th>
                        <th class="col-md-1">Others</th>
                        <th class="col-md-1">Invoice</th>
                        <th class="col-md-1">Discount</th>
                        <th class="col-md-1"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (old('participants'))
                    @foreach (old('participants') as $k => $p)
                    <tr id="student_{{ $k }}" data-ref="1">
                        <td class='participant'>
                            <!-- <input type="text" class="form-control student" data-ref="0" /> -->
                            <select class="student" id="student_select_{{ $k }}"
                                name="participants[{{ $k }}][application_id]">
                                @if (isset($p['application_id']))
                                <option value="{{ $p['application_id'] }}" selected>{{ $p['tmp_name'] }}</option>
                                @endif
                            </select>
                            <input type="hidden" name="participants[{{ $k }}][tmp_name]"
                                class="tmp-participant" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid]"
                                value="{{ $p['paid'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_1]"
                                value="{{ $p['paid_1'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_2]"
                                value="{{ $p['paid_2'] }}"
                                class="form-control" />
                        </td>
                        <td >
                            <input type="text" name="participants[{{ $k }}][paid_3]"
                                value="{{ $p['paid_3'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_4]"
                                value="{{ $p['paid_4'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_5]"
                                value="{{ $p['paid_5'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][invoice]"
                                value="{{ $p['invoice'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][discount]"
                                value="{{ $p['discount'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <a class="btn btn-danger disabled removeParticipant">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @elseif ($participants)
                    @foreach ($participants as $k => $p)
                    <tr id="student_{{ $k }}" data-ref="1">
                        <td class='participant'>
                            <!-- <input type="text" class="form-control student" data-ref="0" /> -->
                            <select class="student" id="student_select_{{ $k }}"
                                name="participants[{{ $k }}][application_id]">
                                @if (isset($p->application))
                                <option value="{{ $p['application_id'] }}" selected>
                                {{ $p->application->student->name }}
                                @foreach($p->application->student->s2p as $parent)
                                {{ $parent->parent->type == 1 ? $parent->parent->name : null }}
                                @endforeach
                                </option>
                                @endif
                            </select>
                            <input type="hidden" name="participants[{{ $k }}][tmp_name]"
                                class="tmp-participant" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid]"
                                value="{{ $p['paid'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_1]"
                                value="{{ $p['paid_1'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_2]"
                                value="{{ $p['paid_2'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_3]"
                                value="{{ $p['paid_3'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_4]"
                                value="{{ $p['paid_4'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][paid_5]"
                                value="{{ $p['paid_5'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][invoice]"
                                value="{{ $p['invoice'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[{{ $k }}][discount]"
                                value="{{ $p['discount'] }}"
                                class="form-control" />
                        </td>
                        <td>
                            <a class="btn btn-danger removeParticipant">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr id="student_1" data-ref="1">
                        <td class='participant'>
                            <!-- <input type="text" class="form-control student" data-ref="0" /> -->
                            <select class="student" id="student_select_0"
                                name="participants[0][application_id]"></select>

                            <input type="hidden" name="participants[0][tmp_name]"
                                class="tmp-participant" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][paid]"
                                 class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][paid_1]"
                                 class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][paid_2]"
                                 class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][paid_3]"
                                 class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][paid_4]"
                                 class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][paid_5]"
                                 class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][invoice]"
                                class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="participants[0][discount]"
                                class="form-control" />
                        </td>
                        <td>
                            <a class="btn btn-danger disabled removeParticipant">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            <a class="new-participant btn btn-primary">New Participant</a>
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
