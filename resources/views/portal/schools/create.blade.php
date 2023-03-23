<div class="panel panel-default panel-body hide" id="new-school-container">
    <div class="row">
        <form role="form" id="new-school-form"
            data-action="{{ route('portal.schools.store') }}">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>School Name</label>
                    <input class="form-control" name="name">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>School Address</label>
                    <input class="form-control" name="address">
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Add School</button>
            </div>
        </form>
    </div>
</div>
