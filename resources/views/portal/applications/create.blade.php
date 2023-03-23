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

$('.removeRelative').click(function () {
    $(this).parent().parent().remove();
    if ($('#relatives').find('tbody tr').length == 1) {
        $('.removeRelative').addClass('disabled');
    }
});
$('.removeMembership').click(function () {
    $(this).parent().parent().remove();
    if ($('#memberships').find('tbody tr').length == 1) {
        $('.removeMembership').addClass('disabled');
    }
});

$('.select2').select2({
    minimumResultsForSearch: -1
});

$('#motherLookup').autocomplete({
    delay: 500,
    source: function(request, response) {
        $.ajax({
            url: '{{ route("portal.applications.lookup") }}',
            data: {
                'type': 'mother',
                'keyword': request.term
            },
            dataType: 'json',
            method: 'post',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item.name,
                        value: item.id
                    }
                }));
            }
        });
    },
    select: function(event, ui) {

        if (ui.item.value > 0) {
            $('#motherLookup').val(ui.item.label);
            $('#motherHiddenLookup').val(ui.item.value);
            addRelatives(ui.item.value);
        } else {
            $('#motherNew').trigger('click');
        }
        return false;
    },
    focus: function(event, ui) {
        return false;
    }
});

$('#motherNew').click(function () {
    $(this).toggleClass('search');
    $('.newMotherContainer').toggleClass('hide');
    $('#motherHiddenLookup').val('0');
    $('#motherLookup').toggleClass('hide');
    $('#motherLookup').attr('disabled', function(_, attr){ return !attr});
});

$('#fatherLookup').autocomplete({
    delay: 500,
    source: function(request, response) {
        $.ajax({
            url: '{{ route("portal.applications.lookup") }}',
            data: {
                'type': 'father',
                'keyword': request.term
            },
            dataType: 'json',
            method: 'post',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item.name,
                        value: item.id
                    }
                }));
            }
        });
    },
    select: function(event, ui) {

        if (ui.item.value > 0) {
            $('#fatherLookup').val(ui.item.label);
            $('#fatherHiddenLookup').val(ui.item.value);
            addRelatives(ui.item.value);
        } else {
            $('#fatherNew').trigger('click');
        }
        return false;
    },
    focus: function(event, ui) {
        return false;
    }
});

$('#fatherNew').click(function () {
    $(this).toggleClass('search');
    $('.newFatherContainer').toggleClass('hide');
    $('#fatherHiddenLookup').val('0');
    $('#fatherLookup').toggleClass('hide');
    $('#fatherLookup').attr('disabled', function(_, attr){ return !attr});
});

function addRelatives(parentId)
{
    
}

$('.newRelative').click(function () {

    var $rt = $('#relatives');

    var $row = $rt.find('tbody tr:last-child');

    $row.find('select').select2('destroy');

    var $copy = $row.clone(true);

    $copy.find('[name]').each(function () {

        var $newName = this.name.replace(/relatives\[(\d+)\]/g, function (match, index) {
            var index = parseInt(index) + 1;
            return 'relatives[' + (index) + ']';
        });

        if (this.name.match(/relatives\[(\d+)\]\[dob\]/g)) {
            $(this).removeAttr('class');
            $(this).removeAttr('id');
            $(this).attr('class', 'form-control datepicker');

            $(this).datepicker({
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
                yearRange: '1990:2030'
            });
        }

        this.name = $newName;
        if (!this.name.match(/relatives\[(\d+)\]\[school_id\]/g)) {
            this.value = '';
        }

    });

    $row.after($copy);

    $('.select2', $rt).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    if ($rt.find('tbody tr').length > 1) {
        $('.removeRelative').removeClass('disabled');
    } else {
        $('.removeRelative').addClass('disabled');
    }
});

$('.newMembership').click(function () {

    var $rt = $('#memberships');

    var $row = $rt.find('tbody tr:last-child');

    $row.find('select').select2('destroy');

    var $copy = $row.clone(true);

    $copy.find('[name]').each(function () {

        var $newName = this.name.replace(/memberships\[(\d+)\]/g, function (match, index) {
            var index = parseInt(index) + 1;
            return 'memberships[' + (index) + ']';
        });

        this.name = $newName;
        this.value = '';

    });

    $row.after($copy);

    $('.select2', $rt).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    if ($rt.find('tbody tr').length > 1) {
        $('.removeMembership').removeClass('disabled');
    } else {
        $('.removeMembership').addClass('disabled');
    }
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
            action="{{ route('applications.store') }}">
            {{ csrf_field() }}
            <div class="panel panel-default">
                <div class="panel-heading">
                    Student Informations
                </div>
                <div class="panel-body newStudentContainer">
                    <div class="col-md-10">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Name</label> <span class="required">*</span>
                                <input class="form-control"
                                    value="{{ old('student.name') }}"
                                    name="student[name]">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Date of birth</label> <span class="required">*</span>
                                <input class="form-control datepicker"
                                    value="{{ old('student.dob') }}"
                                    name="student[dob]" type="text">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>School Name</label> <span class="required">*</span>
                                <select name="student[school_id]"
                                        class="select2 form-control">
                                    @foreach ($schools as $school)
                                    <option {{ old('student.school_id') == $school['id'] ? 'selected' : '' }}
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
                                    value="{{ old('student.grade') }}"
                                    name="student[grade]" type="text">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Home Phone</label>
                                <input class="form-control"
                                    value="{{ old('student.phone') }}"
                                    name="student[phone]">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address 1</label> <span class="required">*</span>
                                <input class="form-control"
                                    value="{{ old('student.address_1') }}"
                                    name="student[address_1]">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 profileContainer">
                        <img class="profilePicture"
                        src="{{ asset('no-img.gif') }}" />
                        <label class="btn btn-primary col-md-12">
                            Browse <input type="file" name="student[photo]"
                                class="hide">
                        </label>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Mother Informations
                </div>
                <div class="panel-body motherLookup">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input class="form-control" id="motherLookup" name="tmpMotherName"
                                value="{{ old('tmpMotherName') }}"
                            >
                            <input class="form-control" type="hidden"
                                id="motherHiddenLookup" value="{{ old('motherHiddenLookup', 0) }}"
                                name="motherHiddenLookup">
                        </div>
                    </div>
                </div>
                <div class="panel-body newMotherContainer hide">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Name</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ old('mother.name') }}"
                            name="mother[name]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Job</label>
                            <input class="form-control"
                            value="{{ old('mother.job') }}"
                            name="mother[job]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Phone</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ old('mother.phone_1') }}"
                            name="mother[phone_1]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Additional Phone</label>
                            <input class="form-control"
                            value="{{ old('mother.phone_2') }}"
                            name="mother[phone_2]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Mother's Email</label>
                            <input class="form-control"
                            value="{{ old('mother.email') }}"
                            name="mother[email]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-lg-12">
                        <a id="motherNew" class="btn btn-warning newInput"></a>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Father Informations
                </div>
                <div class="panel-body fatherLookup">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input class="form-control" id="fatherLookup"
                            value="{{ old('tmpMotherName') }}" name="tmpMotherName">
                            <input class="form-control" type="hidden"
                                id="fatherHiddenLookup" value="{{ old('fatherHiddenLookup', 0) }}"
                                name="fatherHiddenLookup">
                        </div>
                    </div>
                </div>
                <div class="panel-body newFatherContainer hide">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Name</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ old('father.name') }}"
                            name="father[name]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Job</label>
                            <input class="form-control"
                            value="{{ old('father.job') }}"
                            name="father[job]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Phone</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ old('father.phone_1') }}"
                            name="father[phone_1]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Alternative Phone</label>
                            <input class="form-control"
                            value="{{ old('father.phone_2') }}"
                            name="father[phone_2]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Father's Email</label>
                            <input class="form-control"
                            value="{{ old('father.email') }}"
                            name="father[email]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-lg-12">
                        <a id="fatherNew" class="btn btn-warning newInput"></a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Brothers and Sisters
                </div>
                <div class="panel-body">
                    <div class="col-lg-12">
                        <table id="relatives" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Name <span class="required">*</span></th>
                                <th>Birth day <span class="required">*</span></th>
                                <th>School</th>
                                <th>Grade</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (old('relatives'))
                            @foreach (old('relatives') as $k => $r)
                            <tr class='relative' data-rowId='{{ $k }}'>
                                <td><input name='relatives[{{ $k }}][name]'
                                        value="{{ $r['name'] }}"
                                        class='form-control' /></td>
                                <td><input name='relatives[{{ $k }}][dob]'
                                        value="{{ $r['dob'] }}"
                                        class='form-control datepicker' /></td>
                                <td>
                                    <select class='form-control select2'
                                        name='relatives[{{ $k }}][school_id]'>
                                        @foreach ($schools as $school)
                                        <option {{ $r['school_id'] == $school['id'] ? 'selected' : '' }}
                                            value="{{ $school['id'] }}">
                                            {{ $school['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input name='relatives[{{ $k }}][grade]'
                                        value="{{ $r['grade'] }}"
                                        class='form-control' /></td>
                                <td>
                                    <a class="btn btn-danger disabled removeRelative">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class='relative' data-rowId='0'>
                                <td><input name='relatives[0][name]'
                                        class='form-control' /></td>
                                <td><input name='relatives[0][dob]'
                                        class='form-control datepicker' /></td>
                                <td>
                                    <select class='form-control select2'
                                        name='relatives[0][school_id]'>
                                        @foreach ($schools as $school)
                                        <option value="{{ $school['id'] }}">
                                            {{ $school['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input name='relatives[0][grade]'
                                        class='form-control' /></td>
                                <td>
                                    <a class="btn btn-danger disabled removeRelative">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5">
                                    <a class='btn btn-primary newRelative'>Add</a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


            <div class="panel panel-default">
                <table id="memberships" class="panel-body table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Club memberships</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (old('memberships'))
                    @foreach (old('memberships') as $k => $m)
                    <tr class='membership'>
                        <td>
                            <select class='form-control select2'
                                name='memberships[{{ $k }}]'>
                                @foreach ($memberships as $membership)
                                <option {{ $m == $membership['id'] ? 'selected' : '' }}
                                    value="{{ $membership['id'] }}">
                                    {{ $membership['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <a class="btn btn-danger disabled removeMembership">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr class='membership'>
                        <td>
                            <select class='form-control select2'
                                name='memberships[0]'>
                                @foreach ($memberships as $membership)
                                <option value="{{ $membership['id'] }}">
                                    {{ $membership['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <a class="btn btn-danger disabled removeMembership">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <a class='btn btn-primary newMembership'>Add</a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
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
                                <option {{ old('additional.classification') == 'A' ? 'selected' : '' }}
                                    value="A">A</option>
                                <option {{ old('additional.classification') == 'B' ? 'selected' : '' }}
                                    value="B">B</option>
                                <option {{ old('additional.classification') == 'C' ? 'selected' : '' }}
                                    value="C">C</option>
                                <option {{ old('additional.classification') == 'D' ? 'selected' : '' }}
                                    value="D">D</option>
                            </select>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>

                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea class="form-control" rows="3"
                                name='additional[notes]'>{{ old('additional.notes') }}</textarea>
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
        </form>
    </div>
</div>
@endsection
