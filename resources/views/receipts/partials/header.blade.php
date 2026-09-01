<div class="receipt-header">

    <h1>
        {{ $settings['store_name'] ?? config('app.name', 'PharmaDesk') }}
    </h1>

    @if (!empty($settings['owner_name']))
        <div>
            {{ $settings['owner_name'] }}
        </div>
    @endif

    @if (!empty($settings['address']))
        <div>
            {{ $settings['address'] }}
        </div>
    @endif

    @if (!empty($settings['phone']))
        <div>
            Tel: {{ $settings['phone'] }}
        </div>
    @endif

    @if (!empty($settings['email']))
        <div>
            {{ $settings['email'] }}
        </div>
    @endif

    <div class="receipt-title">
        SALE RECEIPT
    </div>

    <div class="receipt-meta">

        <div>
            <strong>Invoice:</strong>
            {{ $sale->invoice_number }}
        </div>

        <div>
            <strong>Date:</strong>
            {{ $sale->sale_date?->format('d M Y') }}
        </div>

    </div>

</div>