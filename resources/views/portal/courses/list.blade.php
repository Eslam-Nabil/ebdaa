@extends('portal.layout')

@section('headers')

<link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />

@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('js/portal/courses.js') }}"></script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-4" style="text-align: right;">
        <a href="{{ route('portal.courses.grid') }}" class="page-header btn btn-primary">Grid View</a>
        <a href="{{ route('portal.courses.create') }}" class="page-header btn btn-primary">Add Course</a>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List all Courses
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper"
                     data-source="{{ route('portal.courses.list') }}"
                     data-edit="{{ url('/portal/courses') }}">
                    <table id="dtList"
                           class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Action</th>
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
