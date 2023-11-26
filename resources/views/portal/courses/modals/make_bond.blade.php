<div class="modal fade" id="make_bond" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">make Bond</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <form method="post" enctype="multipart/form-data" id="make_course_bond" action="{{ route('portal.bond.store') }}" >
                {{ csrf_field() }}
                <div class="panel-heading">
                    Bond Informations
                </div>
                @if ($errors->any())
                    <div class="">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <div class="panel-body newStudentContainer">
                    <div class="col-lg-6 ">
                        <div class="form-group course_make_bond_invoice">
                            <input type="hidden" name="invoice_id" value="">
                            <input type="hidden" name="student_course_bond" value="1">
                            <input type="hidden" name="student_course_id" value="">
                            <label>invoice: </label> <span></span>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Amount</label> <span class="required">*</span>
                            <input class="form-control" value="{{ old('amount') }}" name="amount">
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6" style="display: none">
                        <div class="form-group total">
                            <label>Total: </label> <span></span>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                    <div class="col-lg-6 " style="display: none">
                        <div class="form-group remaining">
                            <label>Remaining: </label> <span></span>
                            <!-- <p class="help-block">Example block-level help text here.</p> -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="make_course_bond btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
