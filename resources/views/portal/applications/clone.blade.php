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

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('select').select2({
    minimumResultsForSearch: -1,
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


@if($errors->any())
<div class="">
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
  @endforeach
</div>
@endif

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <form method="post" enctype="multipart/form-data"
            action="{{ route('portal.applications.clone.store') }}">
            {{ csrf_field() }}
            <div class="panel panel-default">
                <div class="panel-heading">
                    Student Informations
                </div>
                <div class="panel-body newStudentContainer">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Name</label> <span class="required">*</span>
                            <input class="form-control"
                                value="{{ $relative['name'] }}" readonly="true"
                                name="student[name]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Date of birth</label> <span class="required">*</span>
                            <input class="form-control datepicker"
                                value="{{ $relative['dob'] }}" readonly="true"
                                name="student[dob]" type="text">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>School Name</label> <span class="required">*</span>
                            <select name="student[school_id]" readonly="true"
                                    class="select2 form-control">
                                @foreach ($schools as $school)
                                <option {{ $relative['school_id'] == $school['id'] ? 'selected' : '' }}
                                    value="{{ $school['id'] }}">
                                    {{ $school['name'] }}
                                </option>
                                @endforeach
                            </select>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>School Grade</label>
                            <input class="form-control"
                                value="{{ $relative['grade'] }}" readonly="true"
                                name="student[grade]" type="text">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Home Phone</label>
                            <input class="form-control"
                                value="{{ $student['phone'] }}"
                                name="student[phone]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address 1</label> <span class="required">*</span>
                            <input class="form-control"
                                value="{{ $student['address_1'] }}"
                                name="student[address_1]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Additional Informations
                </div>
                <div class="panel-body">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Classification</label>
                            <select class='select2 form-control' name='additional[classification]'>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>

                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea class="form-control" rows="3"
                                name='additional[notes]'></textarea>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
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

        <input type="hidden" name="ids[appId]" value="{{ $appId }}" />
        <input type="hidden" name="ids[relativeId]" value="{{ $relativeId }}" />
        </form>
    </div>
</div>
@endsection
