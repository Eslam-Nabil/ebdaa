@extends('portal.layout')

@section('headers')

<link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />

@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('js/portal/courses_titles.js') }}"></script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-2">
        <button type="button"
            id="new-courseTitle-button"
            class="page-header btn btn-primary"
            data-cancel="Cancel"
        ><span>Add Course</span></button>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>
@include('portal.courses_titles.create')
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List Courses Titles
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper"
                     data-source="{{ route('portal.courseTitle.list') }}"
                     data-edit="{{ url('/portal/courseTitles') }}">
                    <table id="dtList"
                           class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
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
