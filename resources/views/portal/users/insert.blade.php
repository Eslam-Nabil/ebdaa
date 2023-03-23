@extends('portal.layout')

@section('headers')
<link href="{{ asset('css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('css/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection
@section('scripts')
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('js/portal/users.js') }}"></script>
<script src="{{ asset('js/dataTables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables/dataTables.bootstrap.min.js') }}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <div class="col-lg-2">
        <a href="{{ route('portal.users.browse') }}" class="page-header btn btn-primary">List users</a>
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
<div class="panel panel-default panel-body" id="inline-new-user-container">
    <div class="row">
        <form method="post" action="{{ route('portal.users.create') }}">
            {{ csrf_field() }}
            <div class="col-md-12">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>E-mail</label>
                        <input class="form-control" name="email">
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" name="password" type="password">
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" name="name">
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Group</label>
                        <select class="form-control" name="group" id="group">
                        @foreach ($groups as $group)
                        <option value="{{ $group['id'] }}">
                            {{ $group['name'] }}
                        </option>
                        @endforeach
                        </select>
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
                <div class="col-md-12 course" style="display: none;">
                    <div class="form-group">
                        <label>Courses</label>
                        <select class="form-control courses" name="course[]" multiple="multiple">
                            @foreach ($courses as $course)
                                <option value="{{ $course['id'] }}">
                                    {{ $course['title'] }}
                                </option>
                            @endforeach
                        </select>
                        <!-- <p class="help-block">Example block-level help text here.</p> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Submit Button</button>
                <button type="reset" class="btn btn-warning">Reset Button</button>
            </div>
        </form>
    </div>
</div>
@endsection
