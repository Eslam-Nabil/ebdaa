@extends('portal.layout')

@section('headers')
<link rel="stylesheet" href="{{ asset('css/dataTables/datatables.min.css') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap.min.css" />
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/dataTables/datatables.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script>
$(document).ready(function() {
    $('#journeys').DataTable( {
        "ajax": "{{route('portal.bus.journey')}}",
        "columns": [
            { "data": "start" },
            { "data": "end" },
            { "data": "bus" },
            { "data": "driver" },
        ]
    } );
} );
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Bus Journeys</h1>
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
    <div class="panel panel-default">
        <div class="panel-heading">
            Bus Journeys            
        </div>

        <div class="panel-body">
            <table class="table" id="journeys">
                <thead>
                <tr>
                    <th class="col-md-3">Start Time</th>
                    <th class="col-md-3">End Time</th>
                    <th class="col-md-2">Bus</th>
                    <th class="col-md-3">Driver</th>
                    <th class="col-md-1"></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection
