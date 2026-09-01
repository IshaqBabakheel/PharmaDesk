<div id="itemsEmpty" class="text-center text-muted py-5 {{ !empty($saleItems) ? 'd-none' : '' }}">
    <i class="fas fa-box-open fa-2x mb-2"></i>
    <p class="mb-0">Select a sale to load its items.</p>
</div>

<div id="itemsContainer" class="table-responsive {{ empty($saleItems) ? 'd-none' : '' }}">
    <table class="table table-bordered table-hover align-middle" id="returnItemsTable">
        <thead class="table-light">
            <tr>
                <th>Medicine</th>
                <th>Batch</th>
                <th>Expiry</th>
                <th>Sold Qty</th>
                <th>Returned Qty</th>
                <th>Return Qty</th>
                <th>Free Sold</th>
                <th>Free Returned</th>
                <th>Return Free</th>
                <th>Selling Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="returnItemsBody">
            @if (!empty($saleItems))
                @foreach ($saleItems as $index => $item)
                    @include('sale_returns.partials.return-item-row', [
                        'item' => $item,
                        'index' => $index,
                        'saleReturn' => $saleReturn ?? null,
                    ])
                @endforeach
            @endif
        </tbody>
    </table>
</div>