$(document).ready(function () {

	$('#newAttendance').on('show.bs.modal', function (e) {
		$('.selectAttendanceDay').select2({
			width: '100%',
			minimumResultsForSearch: 5
		});

		$('.selectParticipants').select2({
			closeOnSelect: false,
			width: '100%'
		});

		$('.selectParticipants').val(0).change();
		$(e.currentTarget).find('.attendance_note').val('');

		$('.selectAttendanceDay').on('select2:select', function (event) {
			var data = $('.selectAttendanceDay').find(':selected').attr('data-id');
			$('#time_to_course_id').val(data);
	    });

	});

	$('#editAttendance').on('show.bs.modal', function (e) {
		
		var $id = $(e.relatedTarget).attr('data-id');
		var $note = $(e.relatedTarget).attr('data-note');
		var $attendances = $(e.relatedTarget).attr('data-attendances');

		var $attendances = JSON.parse($attendances);

		$('.selectParticipants').select2({
			closeOnSelect: false,
			width: '100%'
		});

		$('.selectParticipants').val($attendances).change();

		$(e.currentTarget).find('#attendance_note').val($note);
		$(e.currentTarget).find('#attendance_id').val($id);

	});

	$('.addAttendance').click(function (e) {
		e.preventDefault();

		var data = $('#newAttendanceForm').serialize();

		$.ajax({
			url: links['newAttendance'],
			data: data,
			method: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.status) {
					window.location.reload();
				}
			}
		});
	});

	$('.updateAttendance').click(function (e) {
		e.preventDefault();

		var data = $('#editAttendanceForm').serialize();

		$.ajax({
			url: links['editAttendance'],
			data: data,
			method: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.status) {
					window.location.reload();
				}
			}
		});
	});
});