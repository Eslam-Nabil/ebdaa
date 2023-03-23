@extends('portal.layout')

@section('headers')
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />

@endsection
@section('scripts')
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(()=>{
        $('#addDriverBtn').click(() => {
            $.post(
                "{{ route('portal.driver.create') }}",
                {
                    name : $('#newDriverForm input[name="driver.name"]').val(),
                },
                function(response){
                    addDriver(response);
                    $('#newDriverForm input[name="driver.name"]').val("");
                    $('#newDriver').modal('hide');
                }
            );
        });

        $('#editDriverBtn').click(()=> {
            $.post(
                "{{ route('portal.driver.edit') }}",
                {
                    id : $('#editDriverForm input[name="driver.id"]').val(),
                    name : $('#editDriverForm input[name="driver.name"]').val(),
                },
                function(response) {
                    editDriver(response);
                    $('#editDriver').modal('hide');
                }
            );
        });

        $(".driver_select").select2({
            data : getDrivers()
        });

        $(".addBusBtn").click(() => {
            $.post(
                "{{ route('portal.bus.create') }}",
                {
                    driver: $('#newBusForm .driver_select').val(),
                    seats: $('#newBusForm input[name="bus.seats"]').val(),
                    RN: $('#newBusForm input[name="bus.RN"]').val(),
                },
                function(response)
                {
                    addBus(response);
                    $('#newBusForm').trigger("reset");
                    $('#newBus').modal('hide');
                }
            )
        });

        $("#editBusBtn").click(() => {
            $.post(
                "{{ route('portal.bus.edit') }}",
                {
                    id: $('#editBusForm input[name="bus.id"]').val(),
                    driver: $('#editBusForm .driver_select').val(),
                    seats: $('#editBusForm input[name="bus.seats"]').val(),
                    RN: $('#editBusForm input[name="bus.RN"]').val(),
                },
                function(response)
                {
                    editBus(response);
                    $('#editBusForm').trigger("reset");
                    $('#editBus').modal('hide');
                }
            );
        });
    });

    function addDriver(driver)
    {
        var table = $('#drivers');
        table.append(`<tr data-id="${driver.id}">
                        <td class="driverName">
                            ${driver.name}
                        </td>
                        <td class='code'>${driver.code}</td>
                        <td>
                        <a data-id="${driver.id}"
                            href="javascript:showDriver(${driver.id}, '${driver.name}');"
                            class="btn editDriver">
                            <i class="fa fa-gear"></i>
                        </a>
                        <a data-id="${driver.id}"
                            class="btn removeDriver"
                            href="javascript:deleteDriver(${driver.id});">
                            <i class="fa fa-trash-o"></i>
                        </a>
                        </td>
                    </tr>`);
        getDrivers();
    }

    function editDriver(driver)
    {
        var row = $('#drivers tr[data-id="' + driver.id + '"]');
        var name = row.children("td.driverName");
        var code = row.children("td.code");
        var editBtn = row.find("td > a.btn.editDriver");
        name.text(driver.name);
        code.text(driver.code);
        editBtn.attr('href', `javascript:showDriver(${driver.id}, '${driver.name}');`);
        getDrivers();
    }

    function showDriver(id, name)
    {
        $('#editDriverForm input[name="driver.id"]').val(id);
        $('#editDriverForm input[name="driver.name"]').val(name);
        $('#editDriver').modal('show');
    }

    function deleteDriver(id)
    {
        $.post(
            "{{ route('portal.driver.delete') }}",
            {
                id : id,
            },
            function () {
                var row = $('#drivers tr[data-id="' + id + '"]');
                row.remove();
                getDrivers();
            }
        )
    }

    var driverList = [];

    function getDrivers()
    {
        driverList = [];
        $(".driver_select").html("");
        $("#drivers tr").each((x, elm) => {
            if (x != 0)
            {
                var name = $(elm).find(".driverName").text();
                var id = elm.getAttribute("data-id");

                if (id != null)
                {
                    driverList.push({
                        text : name,
                        id : id,
                    });
                }                
            }            
        });
        $(".driver_select").select2({
            data : driverList
        });
    }

    function addBus(bus)
    {
        getDrivers();
        var driverName = driverList.find(elm => elm.id == bus.driver_id).text;
        var table = $("table#buses");
        table.append(`<tr data-id="${bus.id}">
                        <td class="busNumber">
                            ${bus.RN}
                        </td>
                        <td class='busDriver'>${driverName}</td>
                        <td class='busSeats'>${bus.seats}</td>
                        <td>
                            <a data-id="${bus.id}"
                                href="javascript:showBus(${bus.id}, '${bus.driver_id}', ${bus.seats}, '${bus.RN}');"
                                class="btn editBus">
                                <i class="fa fa-gear"></i>
                            </a>
                            <a data-id="${bus.id}"
                                class="btn removeBus"
                                href="javascript:deleteBus(${bus.id});">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>`);
    }

    function showBus(id, driver_id, seats, RN)
    {
        $('#editBusForm input[name="bus.id"]').val(id);
        $('#editBusForm .driver_select').val(driver_id)
        $('#editBusForm .driver_select').trigger('change');
        $('#editBusForm input[name="bus.seats"]').val(seats);
        $('#editBusForm input[name="bus.RN"]').val(RN);
        $('#editBus').modal('show');
    }

    function deleteBus(id)
    {
        $.post(
            "{{ route('portal.bus.delete') }}",
            {
                id : id,
            },
            function () {
                var row = $('#buses tr[data-id="' + id + '"]');
                row.remove();
            }
        );
    }

    function editBus(bus)
    {
        var driverName = driverList.find(elm => elm.id == bus.driver_id).text;
        var row = $('#buses tr[data-id="' + bus.id + '"]');
        var name = row.children("td.busDriver");
        var number = row.children("td.busNumber");
        var seats = row.children("td.busSeats");
        var editBtn = row.find("td > a.btn.editBus");
        name.text(driverName);
        number.text(bus.RN);
        seats.text(bus.seats);
        editBtn.attr('href', `javascript:showBus(${bus.id}, '${bus.driver_id}', ${bus.seats}, '${bus.RN}');`)
    }

</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Manage Buses, Drivers and Journeys.</h1>
    </div>
    @include('portal/breadcrumbs')
</div>


@if($errors->any())
<div class="">
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger">{{ $error }}</div>
  @endforeach
</div>
@endif

<div class="modal fade" id="newDriver"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Driver</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newDriverForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label >Name</label>
                        <input type="text" name="driver.name"
                                 class="form-control" />
                    </div>
                </div>
            </form>            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addTime btn btn-primary" id="addDriverBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newBus" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Bus</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="newBusForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Driver</label>
                        <select class="driver_select" id="driver_select"
                            name="bus.driver_id" style="width: 100%"></select>
                    </div>

                    <div class="form-group">
                        <label>Seats</label>
                        <div class="form-group input-group">
                            <input type="number" name="bus.seats"
                                 class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Registration Number</label>
                        <div class="form-group input-group">
                            <input type="text" name="bus.RN"
                                 class="form-control" />
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addBusBtn btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editDriver"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Driver</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="editDriverForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label >Name</label>
                        <input type="text" name="driver.name"
                                 class="form-control" />
                    </div>
                </div>
                <input type="hidden" id="modal_driver_id" name="driver.id" value="" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="editDriverBtn btn btn-primary" id="editDriverBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editBus" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Bus</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="editBusForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Driver</label>
                        <select class="driver_select" id="driver_select"
                            name="bus.driver_id" style="width: 100%"></select>
                    </div>

                    <div class="form-group">
                        <label>Seats</label>
                        <div class="form-group input-group">
                            <input type="number" name="bus.seats"
                                 class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Registration Number</label>
                        <div class="form-group input-group">
                            <input type="text" name="bus.RN"
                                 class="form-control" />
                        </div>
                    </div>
                </div>
                <input type="hidden" id="modal_bus_id" name="bus.id" value="" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="editBusBtn" class="editBusBtn btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- /.row -->
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            Drivers
        </div>

        <div class="panel-body">
            <table class="table" id="drivers">
                <thead>
                <tr>
                    <th class="col-md-3">Driver Name</th>
                    <th class="col-md-2">Code</th>
                    <th class="col-md-2"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                    <tr data-id="{{ $driver['id'] }}">
                        <td class="driverName">
                            {{ $driver->name }}
                        </td>
                        <td class='code'>{{ $driver->code }}</td>
                        <td>
                            <a data-id="{{ $driver['id'] }}"
                                href="javascript:showDriver({{ $driver['id'] }}, '{{ $driver['name'] }}');"
                                class="btn editDriver">
                                <i class="fa fa-gear"></i>
                            </a>
                            <a data-id="{{ $driver['id'] }}"
                                class="btn removeDriver"
                                href="javascript:deleteDriver({{ $driver['id'] }});">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <a class="newDriver btn btn-primary" href="#newDriver" data-toggle="modal">New Driver</a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            Buses
        </div>

        <div class="panel-body">
            <table class="table" id="buses">
                <thead>
                <tr>
                    <th class="col-md-2">Bus Number</th>
                    <th class="col-md-3">Bus Driver</th>
                    <th class="col-md-2">Number of Seats</th>
                    <th class="col-md-2"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($buses as $bus)
                    <tr data-id="{{ $bus['id'] }}">
                        <td class="busNumber">
                            {{ $bus['RN'] }}
                        </td>
                        <td class='busDriver'>{{ isset($bus['driver']) ? $bus['driver']->name : ""}}</td>
                        <td class='busSeats'>{{ $bus['seats'] }}</td>
                        <td>
                            <a data-id="{{ $bus['id'] }}"
                                href="javascript:showBus({{ $bus['id'] }}, '{{ $bus['driver_id'] }}', {{ $bus['seats'] }}, '{{ $bus['RN'] }}');"
                                class="btn editBus">
                                <i class="fa fa-gear"></i>
                            </a>
                            <a data-id="{{ $bus['id'] }}"
                                class="btn removeBus"
                                href="javascript:deleteBus({{ $bus['id'] }});">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <a class="newBus btn btn-primary" href="#newBus" data-toggle="modal">New Bus</a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
