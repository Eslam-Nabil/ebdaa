@extends('portal.layout')

@section('headers')
<link href="{{ asset('css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('css/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('css/portal/custom.css') }}" rel="stylesheet">
@endsection
@section('scripts')
<script src="{{ asset('js/portal/customers.js') }}"></script>
<script src="{{ asset('js/dataTables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables/dataTables.bootstrap.min.js') }}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-2">
        <button type="button"
            id="inline-new-user-button"
            class="page-header btn btn-primary"
            data-cancel="Cancel"
        ><span>Add Customer</span></button>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>
@include('portal.customers.insert')
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List all users
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper" data-source="{{ route('portal.customers.list') }}">
                    <table class="table table-striped table-bordered table-hover" id="dtUsersList">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>mail</th>
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
