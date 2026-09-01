<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            <i class="fas fa-boxes-stacked text-primary me-2"></i>

            Adjustment Items

        </h5>


        <button type="button" class="btn btn-primary btn-sm" id="addAdjustmentItem">

            <i class="fas fa-plus me-1"></i>

            Add Medicine

        </button>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0" id="adjustmentItemsTable">

                <thead class="table-light">

                    <tr>

                        <th width="20%">
                            Medicine
                        </th>

                        <th width="16%">
                            Batch
                        </th>

                        <th width="12%">
                            Expiry
                        </th>

                        <th width="12%">
                            Available
                        </th>

                        <th width="12%">
                            Quantity
                        </th>

                        <th width="12%">
                            Purchase Price
                        </th>

                        <th width="12%">
                            Selling Price
                        </th>

                        <th width="6%">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody id="adjustmentItemsBody">

                    @if (isset($stockAdjustment) && $stockAdjustment->items->count())

                        @foreach ($stockAdjustment->items as $index => $item)
                            @include('stock_adjustments.partials.adjustment-item-row', [
                                'index' => $index,
                                'item' => $item,
                                'medicines' => $medicines,
                                'stockAdjustment' => $stockAdjustment,
                            ])
                        @endforeach
                    @else
                        @include('stock_adjustments.partials.adjustment-item-row', [
                            'index' => 0,
                            'item' => null,
                            'medicines' => $medicines,
                            'stockAdjustment' => null,
                        ])

                    @endif

                </tbody>

            </table>

        </div>

    </div>

</div>


<input type="hidden"
    id="adjustmentItemIndex"value="{{ isset($stockAdjustment) ? $stockAdjustment->items->count() : 1 }}">


@push('scripts')
    @include('stock_adjustments.partials.javascript')
@endpush
