@extends('portal.layout')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/portal/invoices.js') }}"></script>
    <script>
        $('.datetimepicker').datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            changeMonth: true,
            yearRange: '1990:2030'
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <div class="col-lg-4" style="text-align: right;">
            <a href="{{ route('portal.invoice.create') }}" class="page-header btn btn-primary">Add Invoice</a>
        </div>

        @include('portal/breadcrumbs')
        <!-- /.col-lg-12 -->
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    List all Invoices
                </div>
                <form class="row" action="{{ url()->current() }}" method="GET">
                    <div class="col-md-9" style="margin-top: 10px">
                        <div class="col-md-5">
                            <label>Start Date</label>
                            <input type="text" name="start_date"
                                value="@if (isset($_GET['start_date']) && $_GET['start_date'] != '') {{ $_GET['start_date'] }} @endif "
                                class="datetimepicker form-control" />
                        </div>
                        <div class="col-md-5">
                            <label>End Date</label>
                            <input type="text" name="end_date"
                                value="@if (isset($_GET['end_date']) && $_GET['end_date'] != '') {{ $_GET['end_date'] }} @endif "
                                class="datetimepicker form-control" />
                        </div>
                        <div class="col-md-2" style="margin-top:33px">
                            <label>Not Finished
                                <input type="checkbox" name="not_finished"
                                    @if (isset($_GET['not_finished']) && $_GET['not_finished'] == '1') checked="checked" @endif value="1" />
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3" style="margin-top:33px">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper" data-source="{{ route('portal.invoice.list', $inputs) }}"
                        data-print="{{ route('portal.invoice.print', ['id' => 0]) }}"
                        data-view="{{ route('portal.invoice.view', ['id' => 0]) }}"
                        data-userview="{{ url('/portal/applications') }}">
                        <table id="dtList" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Student</th>
                                    <th>total</th>
                                    <th>Remaining</th>
                                    <th>created_at</th>
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
