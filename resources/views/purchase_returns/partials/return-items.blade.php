<div class="card shadow-sm border-0">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            Return Medicines

        </h5>

        <button type="button" class="btn btn-primary btn-sm" id="addRow">

            <i class="fas fa-plus"></i>

            Add Medicine

        </button>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0" id="itemsTable">

                <thead class="table-light">

                    <tr>

                        <th width="18%">Medicine</th>

                        <th width="10%">Batch</th>

                        <th width="10%">Expiry</th>

                        <th width="8%">Purchased</th>

                        <th width="8%">Returned</th>

                        <th width="8%">Remaining</th>

                        <th width="8%">Return Qty</th>

                        <th width="10%">Price</th>

                        <th width="10%">Total</th>

                        <th width="5%"></th>

                    </tr>

                </thead>

                <tbody>

                    @if (isset($purchaseReturn))

                        @foreach ($purchaseReturn->items as $i => $item)
                            @include('purchase_returns.partials.return-item-row', [
                                'index' => $i,
                                'item' => $item,
                            ])
                        @endforeach

                    @endif

                </tbody>

            </table>

        </div>

    </div>

</div>

<input type="hidden" id="rowIndex" value="{{ isset($purchaseReturn) ? $purchaseReturn->items->count() : 0 }}">

@push('scripts')
@include('purchase_returns.partials.javascript')
@endpush