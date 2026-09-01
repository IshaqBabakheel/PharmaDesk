@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')

<div class="page-header d-flex justify-content-between align-items-lg-center mb-4">
    <div class="mb-4">

        <h3 class="mb-1">
            <i class="fas fa-chart-line text-primary me-2"></i>
            Sales Report
        </h3>

        <p class="text-muted mb-0">
            Analyze sales, revenue, payments and outstanding balances.
        </p>

    </div>

    <div>

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb justify-content-end mb-2">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home') }}">
                            <i class="fas fa-house me-1"></i>
                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        Sales Reports

                    </li>

                </ol>

            </nav>

        </div>
</div>
<div class="container-fluid">

    

    @include('sales_reports.partials.stats')

    @include('sales_reports.partials.filters')

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-receipt me-2 text-primary"></i>
                Sales
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="salesReportTable"
                    class="table table-bordered table-hover align-middle w-100"
                >

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Grand Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Payment Status</th>
                            <th>Status</th>
                            <th>Action</th>
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

    function paymentBadge(status) {

        switch (status) {

            case 'paid':
            case 'Paid':
                return `
                    <span class="badge bg-success">
                        Paid
                    </span>
                `;

            case 'partial':
            case 'Partially Paid':
                return `
                    <span class="badge bg-warning text-dark">
                        Partially Paid
                    </span>
                `;

            default:
                return `
                    <span class="badge bg-danger">
                        Due
                    </span>
                `;
        }
    }

    function statusBadge(status) {

        switch (status) {

            case 'completed':
                return `
                    <span class="badge bg-success">
                        Completed
                    </span>
                `;

            case 'draft':
                return `
                    <span class="badge bg-warning text-dark">
                        Draft
                    </span>
                `;

            case 'cancelled':
                return `
                    <span class="badge bg-danger">
                        Cancelled
                    </span>
                `;

            default:
                return `
                    <span class="badge bg-secondary">
                        ${status}
                    </span>
                `;
        }
    }

    const table = $('#salesReportTable').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,

        ajax: {
            url: "{{ route('sales-reports.datatable') }}",

            data: function (d) {
                d.date_from =
                    $('#dateFrom').val();

                d.date_to =
                    $('#dateTo').val();

                d.customer_id =
                    $('#customerFilter').val();

                d.payment_status =
                    $('#paymentStatusFilter').val();

                d.status =
                    $('#saleStatusFilter').val();
            },

            dataSrc: function (json) {
                loadSalesStatistics();
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
                data: 'invoice_number',
                render: function (data, type, row) {
                    if (type !== 'display') {
                        return data;
                    }

                    const url =
                        "{{ route(
                            'sales-reports.show',
                            ['saleId' => '__ID__']
                        ) }}"
                        .replace(
                            '__ID__',
                            row.id
                        );

                    return `
                        <a
                            href="${url}"
                            class="fw-semibold text-decoration-none"
                        >
                            ${data}
                        </a>
                    `;
                }
            },
            {
                data: 'sale_date'
            },
            {
                data: 'customer'
            },
            {
                data: 'grand_total',
                className: 'text-end fw-semibold'
            },
            {
                data: 'paid_amount',
                className: 'text-end text-success'
            },
            {
                data: 'due_amount',
                className: 'text-end text-danger'
            },
            {
                data: 'payment_status',
                render: function (data) {
                    return paymentBadge(data);
                }
            },
            {
                data: 'status',
                render: function (data) {
                    return statusBadge(data);
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    const url =
                        "{{ route(
                            'sales-reports.show',
                            ['saleId' => '__ID__']
                        ) }}"
                        .replace(
                            '__ID__',
                            row.id
                        );

                    return `
                        <a
                            href="${url}"
                            class="btn btn-sm btn-info"
                            title="View Sale"
                        >
                            <i class="fas fa-eye"></i>
                        </a>
                    `;
                }
            }
        ],

        order: [[2, 'desc']],

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ]
    });

    $('#dateFrom, #dateTo, #customerFilter, #paymentStatusFilter, #saleStatusFilter')
        .on('change', function () {
            table.ajax.reload();
        });

    $('#resetSalesReport').on('click', function () {
        $('#dateFrom').val('');
        $('#dateTo').val('');
        $('#customerFilter').val('');
        $('#paymentStatusFilter').val('');
        $('#saleStatusFilter').val('');

        table.ajax.reload();
    });

    function loadSalesStatistics() {

        $.ajax({
            url: "{{ route('sales-reports.statistics') }}",
            type: 'GET',

            data: {
                date_from:
                    $('#dateFrom').val(),

                date_to:
                    $('#dateTo').val(),

                customer_id:
                    $('#customerFilter').val(),

                payment_status:
                    $('#paymentStatusFilter').val(),

                status:
                    $('#saleStatusFilter').val()
            },

            success: function (response) {

                if (
                    !response.success ||
                    !response.statistics
                ) {
                    return;
                }

                const stats =
                    response.statistics;

                $('#statSalesCount').text(
                    Number(
                        stats.sales_count || 0
                    ).toLocaleString()
                );

                $('#statGrandTotal').text(
                    'Rs. ' +
                    Number(
                        stats.grand_total || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );

                $('#statPaid').text(
                    'Rs. ' +
                    Number(
                        stats.paid_amount || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );

                $('#statDue').text(
                    'Rs. ' +
                    Number(
                        stats.due_amount || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );

                $('#statDiscount').text(
                    'Rs. ' +
                    Number(
                        stats.discount || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );

                $('#statTax').text(
                    'Rs. ' +
                    Number(
                        stats.tax || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );

                $('#statQuantity').text(
                    Number(
                        stats.quantity_sold || 0
                    ).toLocaleString()
                );

                $('#statFreeQuantity').text(
                    Number(
                        stats.free_quantity || 0
                    ).toLocaleString()
                );
            }
        });
    }

});
</script>
@endpush