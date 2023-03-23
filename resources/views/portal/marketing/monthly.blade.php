@extends('portal.layout')

@section('headers')

<link rel="stylesheet" href="{{ asset('css/DataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />

@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script>

$('.months').select2({
    minimumResultsForSearch: 5,
    width: '60%'
});

$('.months').on('select2:select', function (event) {
    var data = event.params.data;

    window.location = '{{ route("portal.marketingSummary.monthly") }}/' + data.text;
});

</script>

@endsection

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-2" style="text-align: right;">
        <a href="{{ route('portal.courses.grid', $startDate) }}"
            class="page-header btn btn-warning">Show Grid</a>
    </div>

    @include('portal/breadcrumbs')
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="">
        <div class="panel panel-default">
            <div class="panel-heading col-md-12">
                <div class="col-md-7">
                    Marketing summary for <span style="font-weight: bold; color: tomato;">
                        {{ date("F Y", strtotime($startDate)) }}
                    </span>
                </div>

                <div class="heading-nav col-md-5" style="text-align: right;">
                    <label class="label-control">Select Month</label>
                    <select class="months">
                        @foreach ($months as $month)
                            <option {{ $startDate == $month ? 'selected' : '' }}
                                value="{{ $month }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Marketer</th>
                                <th>Students</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($summaries as $summary)
                        <tr>
                            <td>{{ $summary['name'] }}</td>
                            <td>
                                <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Paid</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                @foreach($summary['applications'] as $application)
                                <tr>
                                    <td>{{ $application['student'] }}</td>
                                    <td>{{ $application['paid'] ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('portal.courses.show', $application['course_id']) }}">
                                            Visit Course
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                </table>
                            </td>
                            <td>{{ $summary['paid'] }}</td>
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
