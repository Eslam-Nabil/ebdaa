var membershipListTable;

$(document).ready(function () {
    $("#new-membership-button").click(function (event) {
        $('#new-membership-container').toggleClass('hide');
        $(this).toggleClass('cancelForm');
    });

    $('#new-membership-button').click(function (event) {
        event.preventDefault();
    });

    $('#new-membership-form').submit(function (event) {
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
                    membershipListTable.ajax.reload();
                }
                alert(response['message']);
            }
        });

    });

    membershipListTable = $('#dtMembershipsList').DataTable({
        responsive: true,
        lengthMenu: [[50, 100, -1], [50, 100, "All"]],
        displayLength: 50,
        ajax: $('#dtMembershipsList').parent().data('source'),
        "columns": [
            {data: 'id'},
            {data: 'name'},
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
        url: 'memberships/' + id + '/delete',
        method: 'get',
        success: function () {
            membershipListTable.ajax.reload();
        }
    });
}
