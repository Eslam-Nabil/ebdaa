var courseTitleListTable;

$(document).ready(function () {
    $("#new-courseTitle-button").click(function (event) {
        $('#new-courseTitle-container').toggleClass('hide');
        $(this).toggleClass('cancelForm');
    });

    $('#new-courseTitle-button').click(function (event) {
        event.preventDefault();
    });

    $('#new-courseTitle-form').submit(function (event) {
        event.preventDefault();

        var me = $(this);

        var action = me.data('action');

        var formData = me.serialize();

        $.ajax({
            url: action,
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response['status'] == 1) {
                    courseTitleListTable.ajax.reload();
                }
                alert(response['message']);
            }
        });

    });

    courseTitleListTable = $('#dtList').DataTable({
        responsive: true,
        lengthMenu: [[50, 100, -1], [50, 100, "All"]],
        displayLength: 50,
        ajax: $('#dtList').parent().data('source'),
        "columns": [
            {data: 'id'},
            {data: 'title'},
            {
                data: 'id',
                render: function (data, type, row) {
                    if (user.group_id == 1) {
                        return '<a class="btn btn-danger" onclick="deleteItem(' + data + ')">Delete</a>';
                    } else {
                        return '';
                    }
                }
            },
        ]
    });
});

function deleteItem(id) {
    $.ajax({
        url: 'courseTitles/' + id + '/delete',
        method: 'get',
        success: function () {
            courseTitleListTable.ajax.reload();
        }
    });
}
