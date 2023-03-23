<div class="panel panel-default panel-body hide" id="new-membership-container">
    <div class="row">
        <form role="form" id="new-membership-form"
            data-action="{{ route('portal.memberships.store') }}">
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Membership</label>
                    <input class="form-control" name="membership_name">
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>
