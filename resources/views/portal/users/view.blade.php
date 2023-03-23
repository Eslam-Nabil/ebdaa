@extends('portal.layout')

@section('scripts')
<script src="{{ asset('js/admin/users.js') }}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <!-- /.col-lg-12 -->
    @include('portal/breadcrumbs')
</div>
<div class="panel panel-default panel-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label>Id</label>
                <p>{{ $user['id'] }}</p>
            </div>
            <div class="form-group">
                <label>Email</label>
                <p>{{ $user['email'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- /.row -->
@endsection
