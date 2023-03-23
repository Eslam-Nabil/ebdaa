var dtGrid;
var applicationGrid = $('#applicationsGrid');
var dataSource = applicationGrid.parent().data('source');
var dataEditUrl = applicationGrid.parent().data('edit');

$(document).ready(function () {
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        // dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        dom: '<"row"<"col-sm-6"l><"col-sm-6 text-right"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<div class="col-md-6"><span>Filter:</span> _INPUT_</div>',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<div class="col-md-6"><span>Show:</span> _MENU_</div>',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        lengthMenu: [[50, 100, -1], [50, 100, "All"]],
        displayLength: 50,
    });

    dtGrid = applicationGrid.DataTable({
        serverSide: true,
        processing: true,
        stateSave: true,
        ajax: {
            url: dataSource,
            method: 'POST'
        },
        buttons: [
            function () {
                if (user.group_id == 1) {
                    return {
                        extend: 'collection',
                        text: '<i class="fa fa-print"></i>',
                        className: 'btn btn-default btn-export',
                        buttons: [
                            /*{
                                extend: 'copyHtml5',
                                text: 'Copy',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },*/
                            {
                                extend: 'csvHtml5',
                                text: 'CSV',
                                fieldSeparator: ',',
                                extension: '.csv',
                                bom: "true",
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            /*{
                                extend: 'excelHtml5',
                                text: 'Excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                text: 'PDF'
                            }*/
                        ]
                    };
                }
            },
            {
                extend: 'colvis',
                text: '<span class="fa fa-align-justify"></span>',
                columns: [0,1,2,3,4,5,6,7,8,9,10]
            },
        ],
        columns: [
            {data: 'id'},
            {
                data: 'student.name',
                render: function (data, type, row) {
                    return data + ' ' + row['student']['s2p'][0]['father']['name']
                }
            },
            {data: 'student.s2p.1.mother.name'},
            {data: 'student.phone'},
            {data: 'student.s2p.0.father.phone_1'},
            {data: 'student.s2p.1.mother.phone_1'},
            {data: 'student.dob'},
            {data: 'student.address_1'},
            {data: 'student.school.name'},
            {data: 'student.grade'},
            {
                data: 'id',
                render: function (data, row) {
                    return renderViewButton(data) + ' ' + renderEditButton(data) +
                        ' ' + renderDeleteButton(data);
                }
            },
        ],
        columnDefs: [
            {
                targets: 0,
                width: '20px'
            },
            {
                targets: [3, 4, 5, 6, 7, 8, 9],
                visible: false
            },
        ]
    });

    $('select').select2({
        minimumResultsForSearch: 10
    });

    var renderViewButton = function(data) {
        return '<a class="btn btn-primary btn-xs" ' +
            'href="' + dataEditUrl + '/' + data + '">View</a>';
    };

    var renderEditButton = function(data) {
        return '<a class="btn btn-primary btn-xs" ' +
            'href="' + dataEditUrl + '/' + data + '/edit">Edit</a>';
    };

    var renderDeleteButton = function(data) {
        if (user.group_id == 1) {
            return '<a class="btn btn-danger btn-xs" ' +
                'onclick="deleteApplication(' + data + ')">Delete</a>';
        }

        return '';
    };

    $('.filter').click(function () {

        var $formData = $('#filterForm').serialize();

        dtGrid.on('preXhr.dt', function (e, settings, data) {
            return $.extend(data, {filter: $formData});
        });

        dtGrid.ajax.reload();
    });
});

function deleteApplication(id) {
    if (confirm("Are you sure that you want to delete this application?")) {
        $.ajax({
            url: dataEditUrl + '/' + id + '/destroy',
            method: 'get',
            success: function () {
                dtGrid.ajax.reload();
            }
        });
    }
}