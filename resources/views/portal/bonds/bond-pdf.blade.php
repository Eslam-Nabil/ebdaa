<div class="card">
    <div class="card-body mx-4">
        <div class="container">
            <p class="my-5 mx-5" style="font-size: 30px;">Thank for your purchase</p>
            <div class="row">
                <ul class="list-unstyled">
                    <li class="text-black">{{ $bond->invoice->title }}</li>
                    <li class="text-muted mt-1"><span class="text-black">Invoice ID:</span> {{ $bond->invoice->id }}</li>
                    <li class="text-black mt-1">Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </li>
                </ul>
                <hr>
                <div class="col-xl-10">
                    <p>Bond For:
                        {{ $bond->invoice->income->isCourse == 1 ? $bond->invoice->course->title->title : $bond->invoice->income->title }}
                    </p>
                </div>
                <hr>
                <div class="col-xl-2">
                    <p class="float-end">amount: {{$bond->amount}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
