@extends('portal.layout')

@section('headers')
<link href="{{ asset('css/dataTables/dataTables.bootstrap.css') }}"  type="text/css"  rel="stylesheet">
<link href="{{ asset('css/dataTables/dataTables.responsive.css') }}" type="text/css"  rel="stylesheet">
@endsection
@section('scripts')
<script src="{{ asset('js/portal/users.js') }}"></script>
<script src="{{ asset('js/dataTables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables/dataTables.bootstrap.min.js') }}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-2">
        <a href="{{ route('portal.users.insert') }}" class="page-header btn btn-primary">Add User</a>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List all users
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper"
                     data-source="{{ route('portal.users.list') }}"
                     data-edit="{{ url('/portal/user') }}">
                    <table id="dtUsersList"
                           class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>email</th>
                                <th>code</th>
                                <th>options</th>
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
