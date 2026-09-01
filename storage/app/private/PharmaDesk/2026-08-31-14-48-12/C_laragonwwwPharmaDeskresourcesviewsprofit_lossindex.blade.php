@extends('layouts.app')

@section('title', 'Profit & Loss')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                <i class="fas fa-chart-line text-primary me-2"></i>

                Profit & Loss

            </h3>

            <p class="text-muted mb-0">

                Analyze sales profitability, cost of goods sold,
                operating expenses and net profit.

            </p>

        </div>

    </div>


    {{-- Filters --}}
    @include('profit_loss.partials.filters')


    {{-- Statistics --}}
    @include('profit_loss.partials.stats')


    {{-- Monthly Breakdown --}}
    @include('profit_loss.partials.monthly')

</div>

@endsection


@push('scripts')

<script>

$(function () {

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function money(value) {

        return 'Rs. ' +
            Number(value || 0).toLocaleString(
                'en-PK',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
    }


    function number(value) {

        return Number(value || 0).toLocaleString(
            'en-PK',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    function percent(value) {

        return Number(value || 0).toFixed(2) + '%';

    }


    function profitClass(value) {

        return Number(value || 0) >= 0
            ? 'text-success fw-semibold'
            : 'text-danger fw-semibold';

    }


    function profitValue(value) {

        const amount = Number(value || 0);

        return `
            <span class="${profitClass(amount)}">
                ${money(amount)}
            </span>
        `;

    }


    /*
    |--------------------------------------------------------------------------
    | Load Summary
    |--------------------------------------------------------------------------
    */

    function loadSummary() {

        $.ajax({

            url:
                "{{ route('profit-loss.summary') }}",

            type: 'GET',

            data: {

                date_from:
                    $('#dateFrom').val(),

                date_to:
                    $('#dateTo').val(),

                customer_id:
                    $('#customerFilter').val()

            },

            success: function (response) {

                if (
                    !response.success ||
                    !response.summary
                ) {
                    return;
                }


                const summary =
                    response.summary;


                /*
                |--------------------------------------------------------------------------
                | Main Statistics
                |--------------------------------------------------------------------------
                */

                $('#statNetSales')
                    .text(
                        money(
                            summary.net_sales
                        )
                    );


                $('#statCogs')
                    .text(
                        money(
                            summary.cogs
                        )
                    );


                $('#statGrossProfit')
                    .text(
                        money(
                            summary.gross_profit
                        )
                    );


                $('#statExpenses')
                    .text(
                        money(
                            summary.operating_expenses
                        )
                    );


                $('#statNetProfit')
                    .text(
                        money(
                            summary.net_profit
                        )
                    );


                $('#statMargin')
                    .text(
                        percent(
                            summary.gross_margin
                        )
                    );


                $('#statNetMargin')
                    .text(
                        percent(
                            summary.net_margin
                        )
                    );


                $('#statSalesRevenue')
                    .text(
                        money(
                            summary.sales_revenue
                        )
                    );


                $('#statDiscount')
                    .text(
                        money(
                            summary.sales_discount
                        )
                    );


                $('#statReturns')
                    .text(
                        money(
                            summary.sale_returns
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | Profit/Loss Styling
                |--------------------------------------------------------------------------
                */

                const netProfit =
                    Number(
                        summary.net_profit || 0
                    );


                $('#statNetProfit')
                    .removeClass(
                        'text-success text-danger'
                    )
                    .addClass(
                        netProfit >= 0
                            ? 'text-success'
                            : 'text-danger'
                    );


                const grossProfit =
                    Number(
                        summary.gross_profit || 0
                    );


                $('#statGrossProfit')
                    .removeClass(
                        'text-success text-danger'
                    )
                    .addClass(
                        grossProfit >= 0
                            ? 'text-success'
                            : 'text-danger'
                    );

            },

            error: function (xhr) {

                console.error(
                    'Profit & Loss summary error:',
                    xhr.responseText
                );

                toastr.error(
                    'Unable to load Profit & Loss summary.'
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Monthly Table
    |--------------------------------------------------------------------------
    */

    const monthlyTable =
        $('#monthlyProfitLossTable').DataTable({

            processing: true,

            serverSide: false,

            responsive: true,

            searching: false,

            lengthChange: false,

            pageLength: 12,

            ajax: {

                url:
                    "{{ route('profit-loss.monthly') }}",

                type: 'GET',

                data: function (d) {

                    d.date_from =
                        $('#dateFrom').val();

                    d.date_to =
                        $('#dateTo').val();

                    d.customer_id =
                        $('#customerFilter').val();

                },

                dataSrc: function (json) {

                    const rows =
                        json.data || [];

                    calculateMonthlyTotals(rows);

                    return rows;

                }

            },

            columns: [

                {
                    data: 'month',

                    render: function (
                        data,
                        type
                    ) {

                        if (
                            type !== 'display'
                        ) {
                            return data;
                        }

                        if (!data) {
                            return '-';
                        }

                        const parts =
                            data.split('-');

                        if (
                            parts.length !== 2
                        ) {
                            return data;
                        }

                        const date =
                            new Date(
                                Number(parts[0]),
                                Number(parts[1]) - 1,
                                1
                            );

                        return date.toLocaleDateString(
                            'en-US',
                            {
                                month: 'long',
                                year: 'numeric'
                            }
                        );

                    }

                },


                {
                    data: 'sales',

                    className: 'text-end',

                    render: function (data) {
                        return money(data);
                    }

                },


                {
                    data: 'returns',

                    className: 'text-end text-danger',

                    render: function (data) {
                        return money(data);
                    }

                },


                {
                    data: 'discount',

                    className: 'text-end text-warning',

                    render: function (data) {
                        return money(data);
                    }

                },


                {
                    data: 'net_sales',

                    className: 'text-end fw-semibold',

                    render: function (data) {
                        return money(data);
                    }

                },


                {
                    data: 'cogs',

                    className: 'text-end text-danger',

                    render: function (data) {
                        return money(data);
                    }

                },


                {
                    data: 'gross_profit',

                    className: 'text-end',

                    render: function (data) {
                        return profitValue(data);
                    }

                },


                {
                    data: 'operating_expenses',

                    className: 'text-end text-danger',

                    render: function (data) {
                        return money(data);
                    }

                },


                {
                    data: 'net_profit',

                    className: 'text-end',

                    render: function (data) {
                        return profitValue(data);
                    }

                },


                {
                    data: 'net_margin',

                    className: 'text-end',

                    render: function (data) {
                        return percent(data);
                    }

                }

            ],

            order: [

                [0, 'asc']

            ]

        });


    /*
    |--------------------------------------------------------------------------
    | Monthly Totals
    |--------------------------------------------------------------------------
    */

    function calculateMonthlyTotals(rows) {

        let sales = 0;

        let returns = 0;

        let discount = 0;

        let netSales = 0;

        let cogs = 0;

        let grossProfit = 0;

        let expenses = 0;

        let netProfit = 0;


        rows.forEach(function (row) {

            sales +=
                Number(row.sales || 0);

            returns +=
                Number(row.returns || 0);

            discount +=
                Number(row.discount || 0);

            netSales +=
                Number(row.net_sales || 0);

            cogs +=
                Number(row.cogs || 0);

            grossProfit +=
                Number(row.gross_profit || 0);

            expenses +=
                Number(
                    row.operating_expenses || 0
                );

            netProfit +=
                Number(
                    row.net_profit || 0
                );

        });


        /*
        |--------------------------------------------------------------------------
        | Total Net Margin
        |--------------------------------------------------------------------------
        */

        const totalNetMargin =
            netSales > 0
                ? (netProfit / netSales) * 100
                : 0;


        $('#monthlyTotalSales')
            .text(
                money(sales)
            );


        $('#monthlyTotalReturns')
            .text(
                money(returns)
            );


        $('#monthlyTotalDiscount')
            .text(
                money(discount)
            );


        $('#monthlyTotalNetSales')
            .text(
                money(netSales)
            );


        $('#monthlyTotalCogs')
            .text(
                money(cogs)
            );


        $('#monthlyTotalGrossProfit')
            .text(
                money(grossProfit)
            );


        $('#monthlyTotalExpenses')
            .text(
                money(expenses)
            );


        $('#monthlyTotalNetProfit')
            .text(
                money(netProfit)
            );


        $('#monthlyTotalMargin')
            .text(
                percent(totalNetMargin)
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Reload Reports
    |--------------------------------------------------------------------------
    */

    function reloadProfitLoss() {

        loadSummary();

        monthlyTable.ajax.reload();

    }


    /*
    |--------------------------------------------------------------------------
    | Apply Filters
    |--------------------------------------------------------------------------
    */

    $('#applyProfitLossFilters')
        .on('click', function () {

            reloadProfitLoss();

        });


    /*
    |--------------------------------------------------------------------------
    | Customer Change
    |--------------------------------------------------------------------------
    */

    $('#customerFilter')
        .on('change', function () {

            reloadProfitLoss();

        });


    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    $('#resetProfitLoss')
        .on('click', function () {

            $('#dateFrom')
                .val('');

            $('#dateTo')
                .val('');

            $('#customerFilter')
                .val('');

            reloadProfitLoss();

        });


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    loadSummary();

});

</script>

@endpush