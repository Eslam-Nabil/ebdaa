$(document).ready(function () {

    var notifications = function () {
        $.ajax({
            url: $('#notifications').data('source'),
            method: 'GET',
            success: function (response) {
                var html = '';

                for (notification in response) {
                    html += '<li>';
                    html += '<a href="#">';
                    html += '<div>';
                    html += '<i class="fa fa-upload fa-fw"></i> Server Rebooted';
                    html += '<span class="pull-right text-muted small">4 minutes ago</span>';
                    html += '</div>';
                    html += '</a>';
                    html += '</li>';
                }

                $('#notifications').html(html);
            }
        });
    };

    // setTimeout(notifications, 300);

    // setInterval(notifications, 35000);
});

function logmeOut(url, redirect) {
    $.ajax({
        url: url,
        method: 'POST',
        success: function() {
            window.location.href = redirect;
        }
    });
}
