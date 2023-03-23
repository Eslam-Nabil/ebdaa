$('.datetimepicker').datepicker({
    dateFormat: "yy-mm-dd",
    changeYear: true,
    changeMonth: true,
    yearRange: '1990:2030'
});

$('.select2').select2({
    minimumResultsForSearch: -1
});

$('.selectWithSearch').select2({
    minimumResultsForSearch: 5
});

$('.selectTimes,.select2FullWidth').select2({
    minimumResultsForSearch: -1,
    width: '100%'
});

$('.coaches').select2();

$(document).ready(function () {
    $('#editParticipant').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);

        var $parent = $this.parents('tr');

        var $name = $parent.find('.participantName').text();
        var $paid = $parent.find('.paid').text();
        var $paid_1 = $parent.find('.paid_1').text();
        var $paid_2 = $parent.find('.paid_2').text();
        var $paid_3 = $parent.find('.paid_3').text();
        var $paid_4 = $parent.find('.paid_4').text();
        var $paid_5 = $parent.find('.paid_5').text();
        var $invoice = $parent.find('.invoice').text();
        var $discount = $parent.find('.discount').text();
        var $books = $parent.find('.books').attr('data-val');

        $('.modalParticipantName').text($name);
        $('.modalPaid').val($paid);
        $('.modalPaid_1').val($paid_1);
        $('.modalPaid_2').val($paid_2);
        $('.modalPaid_3').val($paid_3);
        $('.modalPaid_4').val($paid_4);
        $('.modalPaid_5').val($paid_5);
        $('.modalInvoice').val($invoice != '-' ? $invoice : '');
        $('.modalDiscount').val($discount != '-' ? $discount : '');
        $('#modal_participant_id').val($this.attr('data-id'));

        $('.modalBooks').val($books).trigger('change');
    });

    $('#editTime').on('show.bs.modal', function(e) {
        var $this = $(e.relatedTarget);

        var $parent = $this.parents('tr');

        var $day = $parent.find('.course-day').attr('data-value');
        var $start_time = $parent.find('.start-time').attr('data-value');
        var $end_time = $parent.find('.end-time').attr('data-value');

        $('.modal-day').val($day).trigger('change');
        $('.modal-start-time').val($start_time).trigger('change');
        $('.modal-end-time').val($end_time).trigger('change');

        $('#modal-time-id').val($this.attr('data-id'));
    });

    $('#editParticipant').on('hide.bs.modal', function(e) {
        $('#editParticipant .errors').html('');
    });
});

function removeParticipant() {
    $('.removeParticipant').click(function () {

        if (!confirm('Are you sure? this can not be retrieved!!')) {
            return;
        }

        var tobeRemoved = $(this).parent().parent();

        var $id = $(this).attr('data-id');

        $.ajax({
            url: links['removeParticipant'],
            data: {id: $id},
            dataType: 'JSON',
            method: 'POST',
            success: function (response) {
                tobeRemoved.remove();
                alert(response.message);
            }
        });
    });
}

removeParticipant();

$('.newTime').click(function () {
    $('#newTime').modal('toggle');
    $('#newTime').on('shown.bs.modal', function () {});
});

$('.newParticipant').click(function () {
    $('#newParticipant').modal('toggle');
    $('#newParticipant').on('shown.bs.modal', function () {
        $('.student').select2({
            minimumResultsForSearch: 5,
            // minimumInputLength: 1,
            width: '100%',
            ajax: {
                url: links['students'],
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: $.map(data.students, function (s, i) {
                            return {
                                id: s.application.id,
                                text: s.fullName
                            }
                        })
                    };
                }
            },
        });
    });
});

function removeTime() {
    $('.removeTime').click(function () {
        var tobeRemoved = $(this).parent().parent();

        var $id = $(this).attr('data-id');

        $.ajax({
            url: links['removeTime'],
            data: {id: $id},
            dataType: 'JSON',
            method: 'POST',
            success: function (response) {
                tobeRemoved.remove();
                alert(response.message);
            }
        });
    });
}

removeTime();

function updateTime() {
    $('.updateTime').click(function (ev) {
        ev.preventDefault();

        var data = $('#editTimeForm').serialize();

        $('#editTime .errors').html('');

        $.ajax({
            url: links['editTime'],
            method: 'POST',
            dataType: 'json',
            data: data,
            success: function (response) {
                if (response.status == '1') {
                    var data = response.data;
                    var participants = $('#times tbody tr[data-id="'+data.id+'"]');
                    participants.find('.course-day').html(data.day);
                    participants.find('.start-time').html(data.start_time);
                    participants.find('.end-time').html(data.end_time);
                    $('#editTime').modal('toggle');
                } else {
                    var errors = response.errors;

                    for (i in errors) {
                        var error = errors[i];
                        $('#editTime .errors')
                            .append('<div class="alert alert-danger">'+error+'</div>');
                    }
                }
            }
        });
    });
}

$(document).ready(function () {

    updateTime();

    $('.addTime').click(function (event) {
        $('#newTime .errors').html('');
        var $data = $('#newTimeForm').serialize();
        $.ajax({
            url: links['newTime'],
            data: $data,
            method: 'POST',
            dataType: 'JSON',
            success: function (response) {
                if (response.status == '1') {
                    var data = response.data;
                    var relatives = $('#times tbody');
                    relatives.append('<tr data-id="'+data.id+'">' +
                    '<td class="course-day" data-value="' + data.day + '">' + data.day + '</td>' +
                    '<td class="start-time" data-value="' + data.start_time + '">' + data.start_time + '</td>' +
                    '<td class="end-time" data-value="' + data.end_time + '">' + data.end_time + '</td>' +
                    '<td><a data-id="' + data.id + '"' +
                    'href="#editTime" data-toggle="modal"' +
                    'class="btn editTime">' +
                    '<i class="fa fa-gear"></i>' + 
                    '</a>' +
                    '<a data-id="' + data.id + '"' +
                    'class="btn removeTime">' +
                    '<i class="fa fa-trash-o"></i></a></td>' +
                    '</tr>');
                    $('#newTime').modal('toggle');

                    removeTime();
                } else {
                    var errors = response.errors;

                    for (i in errors) {
                        var error = errors[i];
                        $('#newTime .errors')
                            .append('<div class="alert alert-danger">'+error+'</div>');
                    }
                }
            }
        });
    });


    $('.addParticipant').click(function (event) {
        $('#newParticipant .errors').html('');
        var $data = $('#newParticipantForm').serialize();
        $.ajax({
            url: links['newParticipant'],
            data: $data,
            method: 'POST',
            dataType: 'JSON',
            success: function (response) {
                if (response.status == '1') {
                    var data = response.data;
                    var participants = $('#participants tbody');
                    participants.append('<tr data-id="' + data.id + '">' +
                    '<td class="participantName">' +
                    data.application.student.name + ' ' +
                    data.application.student.s2p[0].father.name +
                    '</td>' +
                    '<td class="paid">' + data.paid + '</td>' +
                    '<td class="paid_1">' + data.paid_1 + '</td>' +
                    '<td class="paid_2">' + data.paid_2 + '</td>' +
                    '<td class="paid_3">' + data.paid_3 + '</td>' +
                    '<td class="paid_4">' + data.paid_4 + '</td>' +
                    '<td class="paid_5">' + data.paid_5 + '</td>' +
                    '<td class="invoice">' + (data.invoice ? data.invoice : '') + '</td>' +
                    '<td>' + (data.discount ? data.discount : '') + '</td>' +
                    '<td class="books" data-val="'+data.get_books+'">' + 
                    (data.get_books == 1 ? 'Yes' : 'No') +
                    '</td>' +
                    '<td>' +
                    '<a data-id="' + data.id + '"' +
                    'href="#editParticipant" data-toggle="modal"' +
                    'class="btn editParticipant">' +
                    '<i class="fa fa-gear"></i></a>' +
                    '<a data-id="' + data.id + '"' +
                    'class="btn removeParticipant">' +
                    '<i class="fa fa-trash-o"></i></a>' +
                    '</td></tr>');
                    $('#newParticipant').modal('toggle');

                    removeParticipant();
                } else {
                    var errors = response.errors;

                    for (i in errors) {
                        var error = errors[i];
                        $('#newParticipant .errors')
                            .append('<div class="alert alert-danger">'+error+'</div>');
                    }
                }
            }
        });
    });

    $('.updateParticipant').click(function (ev) {
        ev.preventDefault();

        var data = $('#editParticipantForm').serialize();

        $('#editParticipant .errors').html('');

        $.ajax({
            url: links['editParticipant'],
            method: 'POST',
            dataType: 'json',
            data: data,
            success: function (response) {
                if (response.status == '1') {
                    var data = response.data;
                    var participants = $('#participants tbody tr[data-id="'+data.id+'"]');
                    participants.find('.paid').html(data.paid);
                    participants.find('.paid_1').html(data.paid_1);
                    participants.find('.paid_2').html(data.paid_2);
                    participants.find('.paid_3').html(data.paid_3);
                    participants.find('.paid_4').html(data.paid_4);
                    participants.find('.paid_5').html(data.paid_5);
                    participants.find('.invoice').html(data.invoice);
                    participants.find('.discount').html(data.discount);
                    participants.find('.books').html(data.get_books == 1 ? 'Yes' : 'No');
                    $('#editParticipant').modal('toggle');
                } else {
                    var errors = response.errors;

                    for (i in errors) {
                        var error = errors[i];
                        $('#editParticipant .errors')
                            .append('<div class="alert alert-danger">'+error+'</div>');
                    }
                }
            }
        });
    });
});
