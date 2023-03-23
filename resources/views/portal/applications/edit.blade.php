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
    var links = {};
    links['lookup'] = '{{ route("portal.applications.lookup") }}';
    links['newRelative'] = '{{ route("portal.relatives.store") }}';
    links['removeRelative'] = '{{ route("portal.relatives.delete") }}';
    links['newMembership'] = '{{ route("portal.membershiptoapplication.store") }}';
    links['removeMembership'] = '{{ route("portal.membershiptoapplication.delete") }}';
</script>
<script src="{{ asset('js/portal/application/edit.js') }}"></script>
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


<div class="modal fade" id="newRelative"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New relative</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newRelativeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label> <span class="required">*</span>
                        <input class="form-control"
                            name="relative_name" type="text">
                    </div>

                    <div class="form-group">
                        <label>Date of birth</label> <span class="required">*</span>
                        <input class="form-control datepicker"
                            name="relative_dob" type="text">
                    </div>

                    <div class="form-group">
                        <label >School</label>
                        <div>
                            <select class='form-control select2'
                                name='relative_school_id'>
                                @foreach ($schools as $school)
                                <option value="{{ $school['id'] }}">
                                    {{ $school['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Grade</label>
                        <input class="form-control"
                            name="relative_grade" type="text">
                    </div>
                </div>
                <input type="hidden" name="student_id" value="{{ $student['id'] }}" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addRelative btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newMembership"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Membership</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newMembershipForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Membership</label>
                        <div>
                            <select class='form-control select2'
                                name='membership_id'>
                                @foreach ($memberships as $membership)
                                <option value="{{ $membership['id'] }}">
                                    {{ $membership['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <input type="hidden" name="student_id" value="{{ $student['id'] }}" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addMembership btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <form method="post" enctype="multipart/form-data"
            action="{{ route('applications.update', $id) }}">
            @method('PUT')
            {{ csrf_field() }}
            <div class="panel panel-default">
                <div class="panel-heading">
                    Student Informations
                </div>
                <div class="panel-body">
                    <div class="col-md-10">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Name</label> <span class="required">*</span>
                                <input class="form-control" name="student[name]"
                                    value="{{ $student['name'] }}">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Date of birth</label> <span class="required">*</span>
                                <input class="form-control datepicker"
                                    name="student[dob]" type="text"
                                    value="{{ $student['dob'] }}">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>School Name</label> <span class="required">*</span>
                                <select name="student[school_id]"
                                        class="select2 form-control">
                                    @foreach ($schools as $school)
                                    <option value="{{ $school['id'] }}"
                                    {{ $school['id'] == $student['school_id'] ? 'selected' : '' }}>
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
                                    value="{{ $student['grade'] }}"
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
                    <div class="col-md-2 profileContainer">
                        <img class="profilePicture"
                        src="{{ asset('storage/' . $student['photo']) }}" />
                        <label class="btn btn-primary col-md-12">
                            Browse <input type="file" name="student[photo]"
                                class="hide">
                                <input type="hidden" name="student[photo_tmp]"
                                    value="{{ $student['photo'] }}" />
                        </label>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Mother Informations
                </div>
                <div class="panel-body">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Name</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ $s2p[1]['mother']['name'] }}"
                            name="mother[name]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Job</label>
                            <input class="form-control"
                            value="{{ $s2p[1]['mother']['job'] }}"
                            name="mother[job]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Phone</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ $s2p[1]['mother']['phone_1'] }}"
                            name="mother[phone_1]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Mother's Additional Phone</label>
                            <input class="form-control"
                            value="{{ $s2p[1]['mother']['phone_2'] }}"
                            name="mother[phone_2]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Mother's Email</label>
                            <input class="form-control"
                            value="{{ $s2p[1]['mother']['email'] }}"
                            name="mother[email]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Father Informations
                </div>
                <div class="panel-body">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Name</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ $s2p[0]['father']['name'] }}"
                            name="father[name]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Job</label>
                            <input class="form-control"
                            value="{{ $s2p[0]['father']['job'] }}"
                            name="father[job]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Phone</label> <span class="required">*</span>
                            <input class="form-control"
                            value="{{ $s2p[0]['father']['phone_1'] }}"
                            name="father[phone_1]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Father's Alternative Phone</label>
                            <input class="form-control"
                            value="{{ $s2p[0]['father']['phone_2'] }}"
                            name="father[phone_2]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Father's Email</label>
                            <input class="form-control"
                            value="{{ $s2p[0]['father']['email'] }}"
                            name="father[email]">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
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
                                <th>Name</th>
                                <th>Birth day</th>
                                <th>School</th>
                                <th>Grade</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($student['relatives'] as $key => $relative)
                            <tr class='relative' data-rowId='{{ $key }}'>
                                <td>{{ $relative['name'] }}</td>
                                <td>{{ $relative['dob'] }}</td>
                                <td>
                                    @foreach ($schools as $school)
                                    @if ($relative['school_id'] == $school['id'])
                                        {{ $school['name'] }}
                                    @endif
                                    @endforeach
                                </td>
                                <td>{{ $relative['grade'] }}</td>
                                <td>
                                    <a data-id="{{ $relative['id'] }}"
                                        class="btn btn-danger removeRelative">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                            @endforeach
                            <tfoot>
                            <tr>
                                <td colspan="5">
                                    <!-- <a class='btn btn-primary newRelative'>Add</a> -->
                                    <button type="button"
                                    class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target="#newRelative">
                                      Add
                                    </button>
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
                    @if ($appMemberships)
                    @foreach ($appMemberships as $key => $appMembership)
                    <tr class='membership'>
                        <td>
                            {{ $appMembership['membership']['name'] }}
                        </td>
                        <td>
                            <a data-id="{{ $appMembership['id'] }}"
                                class="btn btn-danger removeMembership">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
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
                                <option value="A"
                                {{ $app['classification'] == 'A' ? 'selected' : '' }}
                                >A</option>
                                <option value="B"
                                {{ $app['classification'] == 'B' ? 'selected' : '' }}
                                >B</option>
                                <option value="C"
                                {{ $app['classification'] == 'C' ? 'selected' : '' }}
                                >C</option>
                                <option value="D"
                                {{ $app['classification'] == 'D' ? 'selected' : '' }}
                                >D</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea class="form-control" rows="3"
                                name='additional[notes]'>{{ $app['notes'] }}</textarea>
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


            <input type="hidden" name="ids[student]" value="{{ $student['id'] }}" />
            <input type="hidden" name="ids[application]" value="{{ $id }}" />
            <input type="hidden" name="ids[father]" value="{{ $s2p[0]['father']['id'] }}" />
            <input type="hidden" name="ids[mother]" value="{{ $s2p[1]['mother']['id'] }}" />
        </form>
    </div>
</div>
@endsection
