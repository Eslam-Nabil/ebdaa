var dtGrid;
var dtUsersList = $('#dtUsersList');
var dataSource = dtUsersList.parent().data('source');
var dataEditUrl = dtUsersList.parent().data('edit');

$(document).ready(function () {

    dtGrid = dtUsersList.DataTable({
        lengthMenu: [[50, 100, -1], [50, 100, "All"]],
        displayLength: 50,
        'ajax': dataSource,
        'columns': [
            {'data': 'id'},
            {'data': 'email'},
            {'data': 'code'},
            {
                'data': 'id',
                'render': function (data, type, row) {
                    return renderEditButton(data) + ' ' + renderDeleteButton(data) + renderTokenButton(data);
                }
            },
        ]
    });

    $('#inline-new-user-button').click(function () {
        $('#inline-new-user-container').toggleClass('hide');
    });

    var renderEditButton = function(data) {
        return '<a class="btn btn-primary btn-xs" ' +
            'href="' + dataEditUrl + '/' + data + '">Edit</a>';
    };

    var renderDeleteButton = function(data) {
        if (user.group_id == 1) {
            return '<a class="btn btn-danger btn-xs" ' +
                'onclick="deleteItem(' + data + ')">Delete</a>';
        }

        return '';
    };

    var renderTokenButton = function(data) {
        if (user.group_id == 1) {
            return ' <a class="btn btn-success btn-xs" ' +
            'href="'+ dataEditUrl + 's/token?id=' + data + '">Generate Token</a>';
        }
    };

});

function deleteItem(id) {

    if (id == 1) {
        alert('can not delete super user');
        return false;
    }

    if (confirm("Are you sure that you want to delete this user?")) {
        $.ajax({
            url: dataEditUrl + '/' + id + '/delete',
            method: 'get',
            success: function () {
                dtGrid.ajax.reload();
            }
        });
    }
}

$('.select2').select2({
    minimumResultsForSearch: -1
});

$('.selectWithSearch').select2({
    minimumResultsForSearch: 5
});

$('.selectTimes').select2({
    minimumResultsForSearch: -1,
    width: '100%'
});

$('#group').change((e)=>{
    if($('#group').val() != 4) {
        $('.course').css('display', 'none');
        return false;
    }
    $('.course').css('display', 'block');
    $('.courses').select2();
})