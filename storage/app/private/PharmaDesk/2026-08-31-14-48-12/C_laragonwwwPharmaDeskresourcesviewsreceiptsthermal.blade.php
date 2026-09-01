@php
    $settings = $settings ?? [];
    $currency = $settings['currency'] ?? 'PKR';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $sale->invoice_number }}</title>

    <style>
        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
            width: 80mm;
            min-width: 80mm;
            max-width: 80mm;
            background: #fff;
            color: #000;
            font-family: "Courier New", monospace;
            font-size: 11px;
            line-height: 1.35;
        }


        body {
            overflow-x: hidden;
        }


        .thermal-receipt {
            width: 80mm;
            max-width: 80mm;
            margin: 0;
            padding: 3mm;
            background: #fff;
        }


        .center {
            text-align: center;
        }


        .right {
            text-align: right;
        }


        .header {
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }


        .store-name {
            font-size: 16px;
            font-weight: 700;
        }


        .store-info {
            font-size: 10px;
        }


        .title {
            margin-top: 7px;
            font-weight: 700;
            font-size: 12px;
        }


        .meta {
            margin-top: 6px;
            font-size: 10px;
        }


        .customer {
            padding: 7px 0;
            border-bottom: 1px dashed #000;
            font-size: 10px;
        }


        .items {
            padding: 8px 0;
            border-bottom: 1px dashed #000;
        }


        .item {
            padding-bottom: 7px;
            margin-bottom: 7px;
            border-bottom: 1px dotted #999;
        }


        .item:last-child {
            border-bottom: 0;
            margin-bottom: 0;
            padding-bottom: 0;
        }


        .item-name {
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 2px;
        }


        .item-details {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 5px;
            font-size: 9px;
        }


        .item-price-line {
            display: grid;
            grid-template-columns: 1fr 16mm 18mm;
            gap: 3px;
            align-items: center;
            font-size: 10px;
        }


        .item-price-line .qty-price {
            text-align: left;
        }


        .item-price-line .discount {
            text-align: right;
        }


        .item-price-line .total {
            text-align: right;
            font-weight: 700;
        }


        .free {
            font-size: 9px;
        }


        .summary {
            padding: 8px 0;
            border-bottom: 1px dashed #000;
        }


        .summary-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            padding: 2px 0;
            font-size: 10px;
        }


        .summary-row span:last-child {
            text-align: right;
            white-space: nowrap;
        }


        .grand-total {
            margin-top: 4px;
            padding-top: 5px;
            border-top: 1px solid #000;
            font-size: 13px;
            font-weight: 700;
        }


        .due {
            font-weight: 700;
        }


        .payments {
            padding: 8px 0;
            border-bottom: 1px dashed #000;
        }


        .payment {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
            font-size: 9px;
            padding: 2px 0;
        }


        .payment-right {
            text-align: right;
            white-space: nowrap;
        }


        .footer {
            padding-top: 9px;
            text-align: center;
            font-size: 9px;
        }


        .print-button {
            position: fixed;
            top: 15px;
            right: 15px;
            border: 0;
            border-radius: 4px;
            padding: 8px 12px;
            background: #0d6efd;
            color: #fff;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }


        @media print {

            html,
            body {
                width: 80mm;
                min-width: 80mm;
                max-width: 80mm;
                margin: 0;
                padding: 0;
            }


            .thermal-receipt {
                width: 80mm;
                max-width: 80mm;
                margin: 0;
                padding: 3mm;
            }


            .print-button {
                display: none !important;
            }


            @page {
                size: 80mm auto;
                margin: 0;
            }

        }
    </style>

</head>


<body>

    <button type="button" class="print-button" onclick="window.print()">
        Print
    </button>


    <div class="thermal-receipt">

        {{-- Header --}}
        <div class="header">

            <div class="store-name">
                {{ $settings['store_name'] ?? 'PharmaDesk' }}
            </div>


            @if (!empty($settings['owner_name']))
                <div class="store-info">
                    {{ $settings['owner_name'] }}
                </div>
            @endif


            @if (!empty($settings['address']))
                <div class="store-info">
                    {{ $settings['address'] }}
                </div>
            @endif


            @if (!empty($settings['phone']))
                <div class="store-info">
                    Tel: {{ $settings['phone'] }}
                </div>
            @endif


            @if (!empty($settings['email']))
                <div class="store-info">
                    {{ $settings['email'] }}
                </div>
            @endif


            <div class="title">
                SALE RECEIPT
            </div>


            <div class="meta">

                Invoice:
                {{ $sale->invoice_number }}

                <br>

                Date:
                {{ $sale->sale_date?->format('d M Y') }}

            </div>

        </div>


        {{-- Customer --}}
        <div class="customer">

            <strong>Customer:</strong>

            {{ $customer?->name ?? 'Walk-in Customer' }}


            @if ($customer?->phone)
                <br>

                <strong>Phone:</strong>

                {{ $customer->phone }}
            @endif

        </div>


        {{-- Items --}}
        <div class="items">

            @foreach ($items as $item)
                @php

                    $batch = $item->purchaseItem?->batch_number ?? ($item->batch_number ?? '-');

                    $quantity = (int) $item->quantity;

                    $freeQuantity = (int) ($item->free_quantity ?? 0);

                    $price = (float) $item->selling_price;

                    $discount = (float) $item->discount;

                    $total = (float) $item->total;
                @endphp


                <div class="item">

                    <div class="item-name">

                        {{ $item->medicine?->name ?? '-' }}

                    </div>


                    <div class="item-details">

                        <span>
                            Batch: {{ $batch }}
                        </span>

                        <span class="right">

                            Qty:
                            {{ $quantity }}

                        </span>

                    </div>


                    <div class="item-price-line">

                        <span class="qty-price">

                            {{ $quantity }}
                            ×
                            {{ number_format($price, 2) }}

                        </span>


                        <span class="discount">

                            -{{ number_format($discount, 2) }}

                        </span>


                        <span class="total">

                            {{ number_format($total, 2) }}

                        </span>

                    </div>


                    @if ($freeQuantity > 0)
                        <div class="free">

                            Free:
                            {{ $freeQuantity }}

                        </div>
                    @endif

                </div>
            @endforeach

        </div>


        {{-- Summary --}}
        <div class="summary">

            <div class="summary-row">

                <span>
                    Subtotal
                </span>

                <span>
                    {{ $currency }}
                    {{ number_format((float) $sale->subtotal, 2) }}
                </span>

            </div>


            <div class="summary-row">

                <span>
                    Discount
                </span>

                <span>
                    -
                    {{ $currency }}
                    {{ number_format((float) $sale->discount, 2) }}
                </span>

            </div>


            <div class="summary-row">

                <span>
                    Tax
                </span>

                <span>
                    {{ $currency }}
                    {{ number_format((float) $sale->tax, 2) }}
                </span>

            </div>


            @if ((float) $sale->shipping > 0)
                <div class="summary-row">

                    <span>
                        Shipping
                    </span>

                    <span>
                        {{ $currency }}
                        {{ number_format((float) $sale->shipping, 2) }}
                    </span>

                </div>
            @endif


            @if ((float) $sale->other_charges > 0)
                <div class="summary-row">

                    <span>
                        Other Charges
                    </span>

                    <span>
                        {{ $currency }}
                        {{ number_format((float) $sale->other_charges, 2) }}
                    </span>

                </div>
            @endif


            <div class="summary-row grand-total">

                <span>
                    TOTAL
                </span>

                <span>
                    {{ $currency }}
                    {{ number_format((float) $sale->grand_total, 2) }}
                </span>

            </div>


            <div class="summary-row">

                <span>
                    Paid
                </span>

                <span>
                    {{ $currency }}
                    {{ number_format((float) $sale->paid_amount, 2) }}
                </span>

            </div>


            <div class="summary-row due">

                <span>
                    Due
                </span>

                <span>
                    {{ $currency }}
                    {{ number_format((float) $sale->due_amount, 2) }}
                </span>

            </div>

        </div>


        {{-- Payments --}}
        @if ($payments->count())

            <div class="payments">

                @foreach ($payments as $payment)
                    <div class="payment">

                        <span>

                            {{ $payment->payment_number }}

                            <br>

                            {{ ucwords(str_replace('_', ' ', $payment->method)) }}

                        </span>


                        <span class="payment-right">

                            {{ $currency }}

                            {{ number_format((float) $payment->amount, 2) }}

                        </span>

                    </div>
                @endforeach

            </div>

        @endif


        {{-- Footer --}}
        <div class="footer">

            Thank you for your business.

        </div>

    </div>


    <script>
        window.addEventListener(
            'load',
            function() {

                setTimeout(
                    function() {
                        window.print();
                    },
                    300
                );

            }
        );

        window.addEventListener('afterprint', function() {

            setTimeout(function() {
                window.close();
            }, 300);

        });
    </script>

</body>

</html>
