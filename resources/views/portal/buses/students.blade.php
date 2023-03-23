@extends('portal.layout')

@section('headers')
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}" />
@endsection
@section('scripts')
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script>
function showStudent(bus_id)
{
    $('#addStudentForm input[name="bus_id"]').val(bus_id);
    $('#addStudent').modal('toggle');
    $('#addStudent').on('shown.bs.modal', function () {
        $('.student_select').select2({
            minimumResultsForSearch: 5,
            width: '100%',
            ajax: {
                url: "{{ route('portal.courses.student') }}",
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data.students, function (s, i) {
                            return {
                                id: s.id,
                                text: s.fullName
                            }
                        })
                    };
                }
            },
        });
    });
}

function removeStudent(bus_id, student_id)
{
    $.post(
        "{{ route('portal.bus.removestudent') }}",
        {
            bus_id: bus_id,
            student_id: student_id,
        },
        function(response)
        {
            if (response.code == 0)
            {
                $(".alert-danger").text();
                var row = $(`#${bus_id} tr[data-id="${student_id}"]`);
                row.remove();
            }
            else
            {
                $(".alert-danger").text(response.message);
            }
        }
    );
}

$(".addStudentBtn").click( ()=> {
    $.post(
        "{{ route('portal.bus.addstudent') }}",
        {
            student_id: $("#addStudentForm .student_select option:selected").val(),
            bus_id: $("#addStudentForm input[name='bus_id']").val(),
        },
        function(response)
        {
            if (response.code == 0)
            {
                location.reload();
            }
            else
            {
                $("#addStudentForm .student_select").select2('val', 'All');
                $("#addStudentForm .student_select").trigger('change');
                $('#addStudent').modal('hide');
                $(".alert-danger").text(response.message);
            }
        }
    )
})
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Bus Students</h1>
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

<div class="modal fade" id="addStudent" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Student</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="addStudentForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Student Name</label>
                        <select class="student student_select" id="student_select"></select>
                    </div>
                </div>
                <input type="hidden" name="bus_id" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addStudentBtn btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- /.row -->
<div class="row">    
    @foreach($buses as $bus)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-4"><b>Bus:</b> {{$bus['RN']}}</div>
                <div class="col-xs-4"><b>Driver:</b> {{ isset($bus['driver']) ? $bus['driver']->name : ""}}</div>
                <div class="col-xs-4"><b>Seats:</b> {{$bus['seats']}}</div>  
            </div>            
        </div>

        <div class="panel-body">
            <table class="table" id="{{$bus['id']}}">
                <thead>
                <tr>
                    <th class="col-md-3">Student name</th>
                    <th class="col-md-2"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($bus->students as $s2b)
                    <tr data-id="{{$s2b->student->id}}">
                        <td>
                            {{$s2b->student->name}}
                            @foreach($s2b->student->s2p as $parent)
                                {{ $parent->parent->type == 1 ? $parent->parent->name : null }}
                            @endforeach
                        </td>
                        <td>
                            <a data-id="{{ $s2b->student['id'] }}"
                                class="btn removeStudent"
                                href="javascript:removeStudent({{$bus['id']}}, {{$s2b->student['id']}});">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>                    
                    @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <a class="addStudent btn btn-primary" href="javascript:showStudent({{$bus['id']}})">Add Student</a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endforeach
</div>

@endsection
