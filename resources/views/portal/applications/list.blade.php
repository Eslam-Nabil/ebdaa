@extends('portal.layout')

@section('headers')

<link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap.min.css" />

@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('js/portal/applications.js') }}"></script>

<script>
    $(document).bind('keypress', function(e) {
        if(e.keyCode==13){
            $('.filter').trigger('click');
        }
    });
</script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-2">
        <a href="{{ route('applications.create') }}" class="page-header btn btn-primary">Add Application</a>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="col-md-8">Filter</div>
            <div class="col-md-4" style="text-align: right;">
                <a class="btn btn-primary btn-xs filter">Search</a>
                <!-- <a class="btn btn-warning btn-xs reset">Reset</a> -->
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <form id="filterForm" method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Student name</label>
                            <input class="form-control"
                                name="filter[name]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mother name</label>
                            <input class="form-control"
                                name="filter[mother]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>School</label>
                            <select name="filter[school_id]" class="form-control">
                                <option selected value="0">All Schools</option>
                                @foreach ($schools as $school)
                                <option value="{{ $school['id'] }}">{{ $school['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Birth Month</label>
                            <select name="filter[month]" class="form-control">
                                <option selected value="0">All Months</option>
                                @foreach ($months as $key => $month)
                                <option value="{{ $key }}">{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Year Range From</label>
                                <input class="form-control"
                                    name="filter[yearFrom]">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To</label>
                                <input class="form-control"
                                    name="filter[yearTo]">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Classification</label>
                            <select class='select2 form-control' name='filter[classification]'>
                                <option value="all">All</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone</label>
                            <input class="form-control"
                                name="filter[phone]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Courses</label>
                            <select name="filter[course_title_id]" class="form-control">
                                <option selected value="0">All Courses</option>
                                @foreach ($courses as $course)
                                <option value="{{ $course['id'] }}">{{ $course['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Not in Courses</label>
                            <select name="filter[not_course_title_id]" class="form-control">
                                <option selected value="0">All Courses</option>
                                @foreach ($courses as $course)
                                <option value="{{ $course['id'] }}">{{ $course['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                List all applications
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class=""
                     data-source="{{ route('portal.applications.list') }}"
                     data-edit="{{ url('/portal/applications') }}">
                    <table id="applicationsGrid"
                           class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student name</th>
                                <th>Mother name</th>
                                <th>Phone number</th>
                                <th>Father's Phone</th>
                                <th>Mother's Phone</th>
                                <th>Birth date</th>
                                <th>Address</th>
                                <th>School</th>
                                <th>Grade</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection
