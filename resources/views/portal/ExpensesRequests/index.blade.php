@extends('portal.layout')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <div class="col-lg-4" style="text-align: right;">
            <a href="{{ route('portal.request.create') }}" class="page-header btn btn-primary">Add Expense Request</a>
        </div>
        @include('portal/breadcrumbs')
        <!-- /.col-lg-12 -->
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    List all Requests
                </div>
                <form class="row" action="{{ url()->current() }}" method="GET">
                    <div class="col-md-7" style="margin-top: 10px">
                        <div class="col-md-6">
                            <label>Start Date</label>
                            <input type="text" name="start_date"  
                             value="@if (isset($_GET['start_date']) && $_GET['start_date'] != '') {{ $_GET['start_date'] }} @endif "
                             class="datetimepicker form-control" />
                        </div>
                        <div class="col-md-6">
                            <label>End Date</label>
                            <input type="text" name="end_date" 
                            value="@if (isset($_GET['end_date']) && $_GET['end_date'] != '') {{ $_GET['end_date'] }} @endif "
                                class="datetimepicker form-control" />
                        </div>
                    </div>
                    <div class="col-lg-5" style="margin-top:33px">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper" data-source="{{ route('portal.request.list',$inputs) }}"
                        data-accept="{{ route('portal.request.accept', [0]) }}">
                        <table id="dtList" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Expense</th>
                                    <th>Amount</th>
                                    <th>Created By</th>
                                    <th>Accpeted By</th>
                                    <th>Created At</th>
                                    @if (in_array($user['group_id'], [1, 5]))
                                        <th>action</th>
                                    @endif
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

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script>
        var dtGrid;
        var coursesGrid = $('#dtList');
        var dataSource = coursesGrid.parent().data('source');
        var dataAcceptUrl = coursesGrid.parent().data('accept');

        $(document).ready(function() {
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                // dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                // dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<div class="col-md-6"><span>Filter:</span> _INPUT_</div>',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<div class="col-md-6"><span>Show:</span> _MENU_</div>',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': '&rarr;',
                        'previous': '&larr;'
                    }
                },
                lengthMenu: [
                    [50, 100, -1],
                    [50, 100, "All"]
                ],
                displayLength: 50,
            });

            dtGrid = coursesGrid.DataTable({
                stateSave: true,
                ajax: {
                    url: dataSource
                },
                'columnDefs': [{
                    targets: 0,
                    width: '20px'
                }, ],
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'expense',
                    },
                    {
                        data: 'amount',
                    },
                    {
                        data: 'createdBy',
                    },
                    {
                        data: 'acceptedBy',
                    },
                    {
                        data: 'created_at',
                    }
                    @if (in_array($user['group_id'], [1, 5]))
                        , {
                            "data": {
                                'id': 'id',
                                'accept': 'acceptedBy'
                            },
                            render: function(data) {
                                if (data.acceptedBy == 'Not accepted yet') {
                                    return renderAcceptButton(data.id);
                                } else {
                                    return renderAcceptedButton();
                                }
                            }
                        }
                    @endif
                ]
            });

            var renderAcceptButton = function(data) {
                return '<a class="btn btn-primary" ' +
                    'href="' + dataAcceptUrl + data + '/">Accept</a>';
            };
            var renderAcceptedButton = function() {
                return '<a class="btn btn-primary" disabled >Accepted</a>';
            };

        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('portal.request.list') }}',
                success: function(res) {
                    console.log(res);
                }
            })
        })
        $('.datetimepicker').datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            changeMonth: true,
            yearRange: '1990:2030'
        });
    </script>
@endsection
