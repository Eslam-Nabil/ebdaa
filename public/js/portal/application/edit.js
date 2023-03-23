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
    var tobeRemoved = $(this).parent().parent();

    var $id = $(this).attr('data-id');

    $.ajax({
        url: links['removeRelative'],
        data: {id: $id},
        dataType: 'JSON',
        method: 'POST',
        success: function (response) {
            tobeRemoved.remove();
            alert(response.message);
        }
    });
});

$('.removeMembership').click(function () {
    var tobeRemoved = $(this).parent().parent();

    var $id = $(this).attr('data-id');

    $.ajax({
        url: links['removeMembership'],
        data: {id: $id},
        dataType: 'JSON',
        method: 'POST',
        success: function (response) {
            tobeRemoved.remove();
            alert(response.message);
        }
    });
});

$('.select2').select2({
    minimumResultsForSearch: -1
});

$('.newRelative').click(function () {
    $('#newRelative').modal('toggle');
    $('#newRelative').on('shown.bs.modal', function () {})
});

$('.newMembership').click(function () {
    $('#newMembership').modal('toggle');
    $('#newMembership').on('shown.bs.modal', function () {})
});

$(document).ready(function () {
    $('.addRelative').click(function (event) {
        $('#newRelative .errors').html('');
        var $data = $('#newRelativeForm').serialize();
        $.ajax({
            url: links['newRelative'],
            data: $data,
            method: 'POST',
            dataType: 'JSON',
            success: function (response) {
                if (response.status == '1') {
                    var data = response.data;
                    var relatives = $('#relatives tbody');
                    relatives.append('<tr>' +
                    '<td>' + data.name + '</td>' +
                    '<td>' + data.dob + '</td>' +
                    '<td>' + data.school.name + '</td>' +
                    '<td>' + (data.grade ? data.grade : '') + '</td>' +
                    '<td><a data-id="' + data.id + '"' +
                    'class="btn btn-danger removeRelative">' +
                    '<i class="fa fa-trash-o"></i></a></td>' +
                    '</tr>');
                    $('#newRelative').modal('toggle');
                } else {
                    var errors = response.errors;

                    for (i in errors) {
                        var error = errors[i];
                        $('#newRelative .errors')
                            .append('<div class="alert alert-danger">'+error+'</div>');
                    }
                }
            }
        });
    });

    $('.addMembership').click(function (event) {
        $('#newMembership .errors').html('');
        var $data = $('#newMembershipForm').serialize();
        $.ajax({
            url: links['newMembership'],
            data: $data,
            method: 'POST',
            dataType: 'JSON',
            success: function (response) {
                if (response.status == '1') {
                    var data = response.data;
                    var relatives = $('#memberships tbody');
                    relatives.append('<tr>' +
                    '<td>' + data.membership.name + '</td>' +
                    '<td><a data-id="' + data.id + '"' +
                    'class="btn btn-danger removeMembership">' +
                    '<i class="fa fa-trash-o"></i></a></td>' +
                    '</tr>');
                    $('#newMembership').modal('toggle');
                } else {
                    var errors = response.errors;

                    for (i in errors) {
                        var error = errors[i];
                        $('#newMembership .errors')
                            .append('<div class="alert alert-danger">'+error+'</div>');
                    }
                }
            }
        });
    });
});
