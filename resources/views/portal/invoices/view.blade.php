@extends('portal.layout')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection
@section('scripts')
    <!-- <script src="{{ asset('js/portal/invoices.js') }}"></script> -->
    <!-- <script src="{{ asset('js/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script> -->
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

    <script>
        $('.confirmDelete').click(function(e) {
            e.preventDefault();
            if (confirm('Are you sure that you want to delete this?')) {
                location.href = $(this).attr('data-href');
            }
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Show invoice</h1>
        </div>

        @include('portal/breadcrumbs')
        <!-- /.col-lg-12 -->
    </div>


    @if ($errors->any())
        <div class="">
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- /.row -->
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                Invoice Info
                <a href="{{ route('portal.bond.create', $invoice->id ) }}" class="btn btn-primary">Add Bon</a>
                {{-- <div class="col-lg-4" style="text-align: right;">
                </div> --}}
            </div> 
            
            <div class="panel-body">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Title</label>
                        <p class="help-block">{{ $invoice->income->title }}</p>
                    </div>
                </div>
                @if ($invoice->course_id)
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Course title</label>
                            <p class="help-block">{{ $invoice->course->title->title }}</p>
                        </div>
                    </div>
                @endif
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Student</label>
                        <p class="help-block">{{ $invoice->student->name }}</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Total</label>
                        <p class="help-block">{{ $invoice->total }}</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Remaining</label>
                        <p class="help-block">{{ $invoice->remaining ?? 0 }}</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Date</label>
                        <p class="help-block">{{ date('d-m-Y', strtotime($invoice->created_at)) }}</p>
                    </div>
                </div>
                <div class="clearfix"></div>

            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Bonds
            </div>

            <div class="panel-body">
                <table class="table" id="participants">
                    <thead>
                        <tr>
                            <th class="col-md-2">id</th>
                            <th class="col-md-2">Paid</th>
                            <th class="col-md-3">created_by</th>
                            <th class="col-md-3">accepted_by</th>
                            <th class="col-md-2">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->bond as $bond)
                            <tr>
                                <td>{{ $bond->id }}</td>
                                <td>{{ $bond->amount }}</td>
                                <td>{{ $bond->created_by->name }}</td>
                                <td>{{ $bond->accepted_by->name ?? ' Not accepted yet' }}</td>
                                {{-- <td><a class="btn btn-primary"
                                        href="{{ route('portal.bond.view', ['id' => $bond->id]) }}">View</a>
                                    </td> --}}
                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
