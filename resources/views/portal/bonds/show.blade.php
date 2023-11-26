@extends('portal.layout')

@section('headers')
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection
@section('scripts')
<!-- <script src="{{ asset('js/portal/courses.js') }}"></script> -->
<!-- <script src="{{ asset('js/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script> -->
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script>
    $('.confirmDelete').click(function(e) {
        e.preventDefault();
        if (confirm('Are you sure that you want to delete this?')) {
            location.href = $(this).attr('data-href');
        }
    });
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Show course</h1>
    </div>
    <div class="col-lg-6" style="text-align: right;">
        <a href="{{ route('portal.courses.attendance', $course['id']) }}"
            class="page-header btn btn-sm btn-info">Course Attendence</a>
        @if ($user['group_id'] == 1)
        <a data-href="{{ route('portal.courses.delete', $course->id) }}"
            class="confirmDelete page-header btn btn-sm btn-danger">Delete Course</a>
        @endif
        <a href="{{ route('portal.courses.grid') }}"
        class="page-header btn btn-sm btn-primary">List Courses</a>
        <a href="{{ route('portal.courses.edit', $course->id) }}"
            class="page-header btn btn-sm btn-primary">Edit Course</a>
        <a href="{{ route('portal.courses.copy', $course->id) }}"
            class="page-header btn btn-sm btn-success">Copy Course</a>
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
    <div class="panel panel-default">
        <div class="panel-heading">
            Course Info
        </div>
        <div class="panel-body">
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Course Title</label>
                    <p class="help-block">{{ $course->title->title }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Lab</label>
                    <p class="help-block">{{ $course->lab }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Cost</label>
                    <div class="input-group">
                        <p class="help-block">{{ $course->cost }} EGP</p>
                        @if ($course->tournament && $course->tournament == 1)
                        <p class="help-block"><b>Registration:</b> {{ $course->cost_1 }} EGP</p>
                        <p class="help-block"><b>T-Shirt:</b>  {{ $course->cost_2 }} EGP</p>
                        <p class="help-block"><b>Poster:</b>   {{ $course->cost_3 }} EGP</p>
                        <p class="help-block"><b>Transportation:</b>   {{ $course->cost_4 }} EGP</p>
                        <p class="help-block"><b>Others:</b>   {{ $course->cost_5 }} EGP</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <label>Coaches</label>
                @foreach ($course->coaches as $coach)
                <p class="help-block">{{ $coach->coach->name }}</p>
                @endforeach
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
                    <p class="help-block">{{ $course->start_date }}</p>
                </div>
                <div class="col-md-6">
                    <label>End Date</label>
                    <p class="help-block">{{ $course->end_date }}</p>
                </div>
            </div>
            <table class="table" id="times">
                <thead>
                <tr>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($course->times as $time)
                    <tr>
                        <td>{{ $time->day }}</td>
                        <td>{{ $time->start_time }}</td>
                        <td>{{ $time->end_time }}</td>
                    </tr>
                    @endforeach
                </tbody>
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
                    <th class="col-md-4">Student name</th>
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
                </tr>
                </thead>
                <tbody>
                    @foreach($course->participants as $participant)
                    @if ($participant->application)
                    <tr>
                        <td>
                            <a href="{{ route('applications.show', $participant->application->id) }}">
                            {{ $participant->application->student->name }}
                            @foreach($participant->application->student->s2p as $parent)
                            {{ $parent->parent->type == 1 ? $parent->parent->name : null }}
                            @endforeach
                            </a>
                        </td>
                        <td>{{ $participant->paid }}</td>
                        @if ($course->tournament && $course->tournament == 1)
                        <td>{{ $participant->paid_1 }}</td>
                        <td>{{ $participant->paid_2 }}</td>
                        <td>{{ $participant->paid_3 }}</td>
                        <td>{{ $participant->paid_4 }}</td>
                        <td>{{ $participant->paid_5 }}</td>
                        @endif
                        <td>{{ $participant->invoice ?: '-' }}</td>
                        <td>{{ $participant->discount ?: '-' }}</td>
                        <td>{{ $participant->get_books ? 'Yes' : 'No' }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
