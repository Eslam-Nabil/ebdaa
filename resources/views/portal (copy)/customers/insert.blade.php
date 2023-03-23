<div class="panel panel-default panel-body hide" id="inline-new-user-container">
    <div class="row">
        <form role="form" id="inline-new-user-form" data-action="{{ route('portal.customers.create') }}">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>E-mail</label>
                    <input class="form-control" name="email">
                    <!-- <p class="help-block">Example block-level help text here.</p> -->
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" name="password" type="password">
                    <!-- <p class="help-block">Example block-level help text here.</p> -->
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Submit Button</button>
                <button type="reset" class="btn btn-warning">Reset Button</button>
            </div>
        </form>
    </div>
</div>
