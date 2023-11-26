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
         $('select').select2({
        minimumResultsForSearch: 10
    });

        $('#invoices').on('change', function() {
            var id = $(this).val();
            $.ajax({
                url: '{{ route('portal.invoice.view_json', ['id' => 0]) }}' + id,
                type: 'GET',
                data: {
                    id:id
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                   $('.total').parent().show();
                   $('.remaining').parent().show();
                   $('.remaining span').empty();
                   $('.remaining span').append(res.remaining);
                   $('.total span').empty();
                   $('.total span').append(res.total);
                }
            });
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
            <form method="post" enctype="multipart/form-data" action="{{ route('portal.bond.store') }}">
                {{ csrf_field() }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Bond Informations
                    </div>
                    <div class="panel-body newStudentContainer">
                        <div class="col-md-6">
                            <label>Invoice</label>
                            <select name="invoice_id" id="invoices" class="form-control select2">
                                <option value="" selected disabled>
                                    {{'select invoice'}}
                                </option>
                                @foreach ($invoices as $invoice)
                                    <option @if ($invoice->id == request()->segment(3)) {{ 'selected' }} @endif
                                        {{-- {{ old('invoice') && in_array($coach['id'], old('invoice')) ? 'selected' : '' }} --}} {{-- old('coaches.' . $k) == $coach['id'] ? 'selected' : '' --}} 
                                        value="{{ $invoice->id }}">
                                        @if ($invoice->income->title == 'Courses')
                                            {{ $invoice->id ." - ".  $invoice->income->title . ": " . $invoice->course->title->title ?? '' }}
                                        @else
                                            {{ $invoice->id ." - ".  $invoice->income->title }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Amount</label> <span class="required">*</span>
                                <input class="form-control" value="{{ old('amount') }}" name="amount">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6" style="display: none">
                            <div class="form-group total">
                                <label>Total: </label> <span></span>               
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6 " style="display: none">
                            <div class="form-group remaining">
                                <label>Remaining: </label> <span></span>               
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
