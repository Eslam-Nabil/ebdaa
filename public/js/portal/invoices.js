var dtGrid;
var coursesGrid = $('#dtList');
var dataSource = coursesGrid.parent().data('source');
var dataViewUrl = coursesGrid.parent().data('view');
var dataViewUserUrl = coursesGrid.parent().data('userview');
console.log(dataViewUserUrl);
var printUrl = coursesGrid.parent().data('print');

$(document).ready(function () {
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        // dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        // dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<div class="col-md-6"><span>Filter:</span> _INPUT_</div>',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<div class="col-md-6"><span>Show:</span> _MENU_</div>',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        lengthMenu: [[50, 100, -1], [50, 100, "All"]],
        displayLength: 50,
    });

    dtGrid = coursesGrid.DataTable({
        stateSave: true,
        order: [
            [3, 'desc']
        ],
        ajax: {
            url: dataSource,

        },
        'columnDefs': [
            {
                targets: 0,
                width: '20px',
            },
        ],
        columns: [
            {
                data: 'id'
            },
            {
                data: 'title',
            },
            {
                data: null ,
                render: function (data, rows) {
                    console.log(data);
                    if(data.application != null){
                    var a='<a href="' + dataViewUserUrl + '/' + data.application + '">'+ data.student +'</a>';
                }else{
                        var a =data.student;
                    }
                    return a;
                },
            },
            {
                data:'total'
            },
            {
                data:'remaining'
            },
            {
                data:'created_at'
            },
            {
                data: 'id',
                render: function (data, rows) {
                    return renderViewButton(data) + "   " + renderPrintButton(data);
                },

            },

        ]
    });

    var renderViewButton = function (data) {
        return '<a class="btn btn-primary" ' +
            'href="' + dataViewUrl + data + '/">View</a>';
    };
    var renderPrintButton = function (data) {
        return '<a class="btn btn-primary" ' +
            'href="' + printUrl + data + '/">print</a>';
    };
});

function deleteItem(id) {
    if (confirm("Are you sure that you want to delete this course?")) {
        $.ajax({
            url: dataEditUrl + '/' + id + '/destroy',
            method: 'get',
            success: function () {
                dtGrid.ajax.reload();
            }
        });
    }
}
