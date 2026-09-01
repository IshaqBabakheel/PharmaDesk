
@extends('layouts.app')

@section('title', 'Financial Report')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="mb-1">
            <i class="fas fa-chart-pie text-primary me-2"></i>
            Financial Report
        </h3>

        <p class="text-muted mb-0">
            Review sales, purchases, returns, payments and cash flow.
        </p>

    </div>

    @include('financial_reports.partials.stats')

    @include('financial_reports.partials.filters')


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-money-bill-transfer me-2 text-primary"></i>
                Financial Transactions
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="financialReportTable"
                    class="table table-bordered table-hover align-middle w-100"
                >

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Description</th>
                            <th>In</th>
                            <th>Out</th>
                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>
$(function () {

    function typeBadge(type) {

        switch (type) {

            case 'Sale':
            case 'Receipt':

                return `
                    <span class="badge bg-success">
                        ${type}
                    </span>
                `;

            case 'Purchase':

                return `
                    <span class="badge bg-primary">
                        Purchase
                    </span>
                `;

            case 'Sale Return':

                return `
                    <span class="badge bg-warning text-dark">
                        Sale Return
                    </span>
                `;

            case 'Purchase Return':

                return `
                    <span class="badge bg-info text-dark">
                        Purchase Return
                    </span>
                `;

            case 'Supplier Payment':

                return `
                    <span class="badge bg-danger">
                        Supplier Payment
                    </span>
                `;

            default:

                return `
                    <span class="badge bg-secondary">
                        ${type}
                    </span>
                `;
        }
    }


    const table = $('#financialReportTable').DataTable({

        processing: true,
        serverSide: false,
        responsive: true,

        ajax: {

            url:
                "{{ route('financial-reports.datatable') }}",

            data: function (d) {

                d.date_from =
                    $('#dateFrom').val();

                d.date_to =
                    $('#dateTo').val();

            },

            dataSrc: function (json) {

                loadFinancialSummary();

                return json.data;

            }

        },

        columns: [

            {
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'date'
            },

            {
                data: 'type',

                render: function (data) {
                    return typeBadge(data);
                }
            },

            {
                data: 'reference'
            },

            {
                data: 'description'
            },

            {
                data: 'in',
                className:
                    'text-end text-success fw-semibold'
            },

            {
                data: 'out',
                className:
                    'text-end text-danger fw-semibold'
            }

        ],

        order: [
            [1, 'desc']
        ],

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ]

    });


    $('#dateFrom, #dateTo').on(
        'change',
        function () {
            table.ajax.reload();
        }
    );


    $('#resetFinancialFilters').on(
        'click',
        function () {

            $('#dateFrom').val('');
            $('#dateTo').val('');

            table.ajax.reload();

        }
    );


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


    function loadFinancialSummary() {

        $.ajax({

            url:
                "{{ route('financial-reports.summary') }}",

            type: 'GET',

            data: {

                date_from:
                    $('#dateFrom').val(),

                date_to:
                    $('#dateTo').val()

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


                $('#statNetSales').text(
                    money(summary.net_sales)
                );


                $('#statNetPurchases').text(
                    money(summary.net_purchases)
                );


                $('#statReceipts').text(
                    money(summary.receipts)
                );


                $('#statSupplierPayments').text(
                    money(summary.supplier_payments)
                );


                $('#statCustomerDue').text(
                    money(summary.customer_due)
                );


                $('#statSupplierDue').text(
                    money(summary.supplier_due)
                );


                const cashFlow =
                    Number(
                        summary.net_cash_flow || 0
                    );

                const cashFlowElement =
                    $('#statCashFlow');


                cashFlowElement.text(
                    money(cashFlow)
                );


                cashFlowElement
                    .removeClass(
                        'text-success text-danger text-secondary'
                    )
                    .addClass(
                        cashFlow >= 0
                            ? 'text-success'
                            : 'text-danger'
                    );

            },

            error: function () {

                toastr.error(
                    'Unable to load financial summary.'
                );

            }

        });

    }


    loadFinancialSummary();

});
</script>

@endpush
