@extends('portal.layout')

@section('headers')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.4.1/css/colReorder.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.0.2/css/rowGroup.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/1.4.4/css/scroller.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.5/css/select.bootstrap.min.css"/>

@endsection
@section('scripts')

<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/colreorder/1.4.1/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.0.2/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.3/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/1.4.4/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>

<script src="{{ asset('js/portal/applications.js') }}"></script>

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

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List all applications
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class=""
                     data-source="{{ route('portal.users.list') }}"
                     data-edit="{{ url('/portal/user') }}">
                    <table id="applicationsGrid"
                           class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Student name</th>
                                <th>Father name</th>
                                <th>Mother name</th>
                                <th>options</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>{{ $application['id'] }}</td>
                                <td>{{ $application->student->name }}</td>
                                <td>
                                    @foreach($application->student->s2p as $parent)
                                    {{ $parent->parent->type == 1 ? $parent->parent->name : null }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($application->student->s2p as $parent)
                                    {{ $parent->parent->type == 2 ? $parent->parent->name : null }}
                                    @endforeach
                                </td>
                                <td>
                                    <a class='btn btn-primary' href="{{ route('applications.show', $application->id) }}">
                                        View
                                    </a>
                                </td>
                                <td>{{ $application->student->dob }}</td>
                                <td>{{ $application->student->address_1 }}</td>
                                <td>{{ $application->student->address_2 }}</td>
                                <td>{{ $application->student->phone }}</td>
                                <td>{{ $application->student->school }}</td>
                                <td>{{ $application->student->grade }}</td>
                            </tr>
                            @endforeach
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
