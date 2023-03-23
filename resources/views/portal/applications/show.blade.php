@extends('portal.layout')

@section('headers')
<link rel="stylesheet" href="{{ asset('css/portal/custom.css') }}" />
@endsection
@section('scripts')

@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-4">
        <a href="{{ route('applications.index') }}"
        class="page-header btn btn-primary">Applications</a>
        <a href="{{ route('applications.edit', $application->id) }}"
            class="page-header btn btn-primary">Edit</a>
        <a href="{{ route('portal.parents.createtoken', ['id' => $application->student->id]) }}"
            class="page-header btn btn-success">Generate Tokens</a>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Student Informations
                <div class='heading-elements'>
                    Student ID <span class="bold red">{{ $application->customId }}</span>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-10">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Name</label>
                            <p class="help-block">{{ $application->student->name }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Date of birth</label>
                            <p class="help-block">{{ $application->student->dob }}</p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>School Name</label>
                            <p class="help-block">{{ $application->student->school->name }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>School Grade</label>
                            <p class="help-block">{{ $application->student->grade }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Home Phone</label>
                            <p class="help-block">{{ $application->student->phone }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address 1</label>
                            <p class="help-block">{{ $application->student->address_1 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <img class="profilePicture" src="{{ asset('storage/' . $application->student->photo) }}" />
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Mother Informations
            </div>
            <div class="panel-body">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Mother's Name</label>
                        <p class="help-block">{{ $mother->name }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Mother's Job</label>
                        <p class="help-block">{{ $mother->job }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Mother's Phone</label>
                        <p class="help-block">{{ $mother->phone_1 }}</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Mother's Additional Phone</label>
                        <p class="help-block">{{ $mother->phone_2 }}</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Mother's Email</label>
                        <p class="help-block">{{ $mother->email }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Mother's Authentication Code</label>
                        <p class="help-block">{{ $mother->code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Father Informations
            </div>
            <div class="panel-body">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Father's Name</label>
                        <p class="help-block">{{ $father->name }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Father's Job</label>
                        <p class="help-block">{{ $father->job }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Father's Phone</label>
                        <p class="help-block">{{ $father->phone_1 }}</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Father's Additional Phone</label>
                        <p class="help-block">{{ $father->phone_2 }}</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Father's Email</label>
                        <p class="help-block">{{ $father->email }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Father's Authentication Code</label>
                        <p class="help-block">{{ $father->code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Brothers and Sisters
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <table id="relatives" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Birth day</th>
                            <th>School</th>
                            <th>Grade</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($application->student->relatives as $relative)
                        <tr>
                            <td>{{ $relative->name }}</td>
                            <td>{{ $relative->dob }}</td>
                            <td>{{ $relative->school->name }}</td>
                            <td>{{ $relative->grade }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('portal.applications.clone.create', [
                                'appId' => $application->id,
                                'relativeId' => $relative->id
                                ]) }}">
                                    Create
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <table class="panel-body table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>Club memberships</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($application->student->memberships as $membership)
                <tr>
                    <td>{{ $membership->membership->name }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Additional Informations
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Classification</label>
                        <p class="help-block">{{ $application->classification }}</p>
                    </div>

                    <div class="form-group">
                        <label>Additional Notes</label>
                        <textarea class="form-control" rows="3"
                            name='additional[notes]'>{{ $application->notes }}</textarea>
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Past Courses
            </div>
            <div class="panel-body">
                <table class="panel-body table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Course Title</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($courses) > 0)
                    @foreach ($courses as $course)
                    <tr>
                        <td>{{ $course['course']['title']['title'] }}</td>
                        <td>{{ $course['course']['start_date'] }} : {{ $course['course']['end_date'] }}</td>
                        <td>
                        <a class="btn btn-xs btn-info" href="{{ route('portal.courses.show', $course['course_id']) }}">
                        Visit course
                        </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" align="center">No data</td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
