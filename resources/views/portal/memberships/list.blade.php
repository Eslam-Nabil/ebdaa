@extends('portal.layout')

@section('headers')

<link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />

@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('js/portal/memberships.js') }}"></script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-2">
    @if (in_array($user['group_id'], [1]))
        <button type="button"
            id="new-membership-button"
            class="page-header btn btn-primary"
            data-cancel="Cancel"
        ><span>Add Membership</span></button>
    </div>
    @endif

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>
@include('portal.memberships.create')
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List Memberships
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper"
                     data-source="{{ route('portal.memberships.list') }}"
                     data-edit="{{ url('/portal/membership') }}">
                    <table id="dtMembershipsList"
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
