<div class="card">
    <div class="card-body mx-4">
        <div class="container">
            <p class="my-5 mx-5" style="font-size: 30px;">Thank for your purchase</p>
            <div class="row">
                <ul class="list-unstyled">
                    <li class="text-black">{{ $invoice->student->name }}</li>
                    <li class="text-muted mt-1"><span class="text-black">Invoice ID:</span> {{ $invoice->id }}</li>
                    <li class="text-black mt-1">Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </li>
                </ul>
                <hr>
                <div class="col-xl-10">
                    <p>Invoice For:
                        {{ $invoice->income->isCourse == 1 ? $invoice->course->title->title : $invoice->income->title }}
                    </p>
                </div>
                <hr>

                @if ($invoice->course->tournament == 1)
                    @if ($invoice->course->cost)
                        <div class="col-xl-10">
                            <p>Course Cost:{{ $invoice->course->cost }}</p>
                        </div>
                    @endif
                    @if ($invoice->course->cost_1 != null )
                        <div class="col-xl-10">
                            <p>Course Registeration:
                                {{ $invoice->course->cost_1 }}</p>
                        </div>
                    @endif
                    @if ($invoice->course->cost_2 != null)
                        <div class="col-xl-10">
                            <p>Course T-shirts: {{ $invoice->course->cost_2 }}</p>
                        </div>
                    @endif
                    @if ($invoice->course->cost_3 != null)
                        <div class="col-xl-10">
                            <p>Course Poster: {{ $invoice->course->cost_3 }}</p>
                        </div>
                    @endif
                    @if ($invoice->course->cost_4 != null)
                        <div class="col-xl-10">
                            <p>Course Transportation:
                                {{ $invoice->course->cost_4 }}</p>
                        </div>
                    @endif
                    @if ($invoice->course->cost_5 != null)
                        <div class="col-xl-10">
                            <p>Course Others: {{ $invoice->course->cost_5 }}</p>
                        </div>
                    @endif
                @endif
                <div class="col-xl-2">
                    <p class="float-end">Total: {{ $invoice->total + ( $invoice->course->participants->where('invoice',$invoice->id)->first()->discount != null ? str_replace("%",'',$invoice->course->participants->where('invoice',$invoice->id)->first()->discount)  : 0 ) }}
                    </p>
                </div>
                @if ($invoice->course->participants->where('invoice',$invoice->id)->first()->discount != null ||
                $invoice->course->participants->where('invoice',$invoice->id)->first()->discount != 0 )
                    <div class="col-xl-2">
                        <p class="float-end">Discount: {{ $invoice->course->participants->where('invoice',$invoice->id)->first()->discount }}
                        </p>
                    </div>
                    <div class="col-xl-2">
                        <p class="float-end">Total After Discount: {{ $invoice->total }}
                        </p>
                    </div>
                    <hr>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-2">
                    <p class="float-end">Paid: {{ $invoice->total - $invoice->remaining }}
                    </p>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-xl-2">
                    <p class="float-end">Remaining: {{ $invoice->remaining }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
