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
                <div class="col-lg-4 form-group">
                    <label>Id</label>
                    <p>{{ $user['id'] }}</p>
                </div>
                <div class="col-lg-4  form-group">
                    <label>Email</label>
                    <p>{{ $user['email'] }}</p>
                </div>
                <div class="col-lg-4  form-group">
                    <label>Name</label>
                    <p>{{ $user['name'] }}</p>
                </div>
                <div class="col-lg-4 form-group">
                    <label>Total In</label>
                    <p>{{ $total_in }}</p>
                </div>
                <div class="col-lg-4 form-group">
                    <label>Total Out</label>
                    <p>{{ $total_out }}</p>
                </div>
                <div class="col-lg-4 form-group">
                    <label>Wallet balance</label>
                    <p>{{ $total_in - $total_out }}</p>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table" id="participants">
                <thead>
                    <tr>
                        <th class="col-md-2">id</th>
                        <th class="col-md-2">amount</th>
                        <th class="col-md-3">type</th>
                        <th class="col-md-3">created_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wallet as $w)
                        <tr>
                            <td>{{ $w->id }}</td>
                            <td>{{ $w->amount }}</td>
                            <td>{{ $w->type }}</td>
                            <td>{{ \Carbon\Carbon::parse($w->created_at)->format('d-m-Y') }}</td>
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

    <!-- /.row -->
@endsection
