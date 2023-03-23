$(document).ready(function () {
    $("#inline-new-user-button").click(function (event) {
        $('#inline-new-user-container').toggleClass('hide');
        $(this).toggleClass('cancelForm');
    });

    $('#inline-new-user-button').click(function (event) {
        event.preventDefault();
    });

    $('#inline-new-user-form').submit(function (event) {
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
                    userListTable.ajax.reload();
                }
                alert(response['message']);
            }
        });

    });

    var userListTable = $('#dtUsersList').DataTable({
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, 150, 200, -1], [10, 25, 50, 100, 150, 200, "All"]],
        displayLength: 50,
        ajax: $('#dtUsersList').parent().data('source'),
        "columns": [
            {data: 'id'},
            {data: 'email'},
            {data: function (data, type, row) {
                return '<a href="user/'+data['id']+'/">view</a>';
            }},
        ]
    });
});
