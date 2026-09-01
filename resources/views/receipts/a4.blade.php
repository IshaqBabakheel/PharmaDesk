@php
    $settings = $settings ?? [];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $sale->invoice_number }} - Receipt
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f5f5;
            color: #212529;
            font-family: Arial, sans-serif;
        }

        .receipt-page {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 18mm;
            background: #fff;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #212529;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .receipt-logo {
            max-width: 100px;
            max-height: 70px;
            margin-bottom: 8px;
        }

        .receipt-header h1 {
            margin: 0 0 5px;
            font-size: 28px;
        }

        .receipt-title {
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .receipt-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .customer-section {
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 9px;
            font-size: 13px;
        }

        .items-table th {
            background: #f1f1f1;
        }

        .items-table small {
            display: block;
            color: #666;
        }

        .text-end {
            text-align: right;
        }

        .totals {
            width: 360px;
            margin-left: auto;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
            border-bottom: 1px solid #eee;
        }

        .grand-total {
            font-size: 17px;
            font-weight: bold;
            border-top: 2px solid #212529;
            border-bottom: 2px solid #212529;
            margin-top: 5px;
        }

        .due-row {
            font-weight: bold;
            color: #dc3545;
        }

        .payment-section {
            margin-top: 30px;
        }

        .payment-section h4 {
            margin-bottom: 10px;
        }

        .payment-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .payment-row small {
            display: block;
            color: #666;
        }

        .receipt-footer {
            margin-top: 50px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 12px;
            color: #666;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 16px;
            border: 0;
            border-radius: 5px;
            background: #0d6efd;
            color: #fff;
            cursor: pointer;
        }

        @media print {

            body {
                background: #fff;
            }

            .receipt-page {
                margin: 0;
                width: 100%;
                min-height: auto;
                padding: 0;
            }

            .print-button {
                display: none;
            }

        }

    </style>

</head>


<body>

<button
    class="print-button"
    onclick="window.print()"
>
    Print Receipt
</button>


<div class="receipt-page">

    @include(
        'receipts.partials.header'
    )


    @include(
        'receipts.partials.customer'
    )


    @include(
        'receipts.partials.items'
    )


    @include(
        'receipts.partials.totals'
    )


    @include(
        'receipts.partials.payments'
    )


    <div class="receipt-footer">

        @if (!empty($settings['receipt_footer']))
            {{ $settings['receipt_footer'] }}
        @else
            Thank you for your business.
        @endif

    </div>

</div>


<script>
window.addEventListener(
    'afterprint',
    function () {
        console.log(
            'Receipt printing completed.'
        );
    }
);
</script>

</body>

</html>