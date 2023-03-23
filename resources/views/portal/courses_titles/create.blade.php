<div class="panel panel-default panel-body hide" id="new-courseTitle-container">
    <div class="row">
        <form role="form" id="new-courseTitle-form"
            data-action="{{ route('portal.courseTitle.store') }}">
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" name="course_title">
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>
