<div class="modal fade" id="editTime"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit time</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="POST" id="editTimeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Day</label>
                        <select class="selectTimes form-control modal-day"
                            name="time[day]">
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Start Time</label>
                        <select name="time[start_time]"
                                class="selectTimes form-control modal-start-time">
                            @foreach ($times as $time)
                            <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label >End Time</label>
                        <select name="time[end_time]"
                                class="selectTimes form-control modal-end-time">
                            @foreach ($times as $time)
                            <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="course_id" value="{{ $course['id'] }}" />
                <input type="hidden" name="time_id" id="modal-time-id" />
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="updateTime btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>