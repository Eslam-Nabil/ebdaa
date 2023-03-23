<div class="modal fade" id="marketingSummary"
    tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Marketing Summary</h5>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="errors"></div>
            <table class="table table-striped table-bordered">
                
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Applications</th>
                        <th>Paid</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($marketingSummary as $summary)
                    <tr>
                        <td>
                            <a href="{{ route('portal.marketingSummary.monthly', $startDate) }}">{{ $summary['name'] }}</a>
                        </td>
                        <td>{{ $summary['students'] }}</td>
                        <td>{{ $summary['paid'] }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>