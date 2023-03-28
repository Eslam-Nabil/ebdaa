@extends('portal.layout')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/portal/custom.css') }}" />
    <style>
        .newInput:before {
            content: "New record"
        }

        .newInput.search:before {
            content: "Search for existing record?"
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>

    <script>
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            yearRange: '1990:2030'
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.removeRelative').click(function() {
            $(this).parent().parent().remove();
            if ($('#relatives').find('tbody tr').length == 1) {
                $('.removeRelative').addClass('disabled');
            }
        });
        $('.removeMembership').click(function() {
            $(this).parent().parent().remove();
            if ($('#memberships').find('tbody tr').length == 1) {
                $('.removeMembership').addClass('disabled');
            }
        });

        $('.select2').select2({
            minimumResultsForSearch: -1
        });
        $('.invoices').select2({
            minimumResultsForSearch: -1
        });
        $('.incomes').select2({
            minimumResultsForSearch: -1
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
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
        <div class="col-lg-12">
            <form method="post" enctype="multipart/form-data" action="{{ route('portal.invoice.store') }}">
                {{ csrf_field() }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Bond Informations
                    </div>
                    <div class="panel-body newStudentContainer">
                       
                        <div class="col-md-6">
                            <label>Income</label>
                            <select name="income" class="form-control incomes" multiple="multiple">
                                @foreach ($incomes as $income)
                                    <option {{-- {{ old('income') && in_array($coach['id'], old('income')) ? 'selected' : '' }} --}} {{-- old('income.' . $k) == $coach['id'] ? 'selected' : '' --}} value="{{ $income->id }}">
                                        {{ $income->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Total</label> <span class="required">*</span>
                                <input class="form-control" value="{{ old('total') }}" name="total">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>User</label> <span class="required">*</span>
                                <input class="form-control" value="{{ old('user') }}" name="user">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
