@extends('layouts.app')

@section('title', 'Purchase Report')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="mb-1">
            <i class="fas fa-cart-shopping text-primary me-2"></i>
            Purchase Report
        </h3>

        <p class="text-muted mb-0">
            Analyze purchases, supplier balances and purchased quantities.
        </p>

    </div>

    @include('purchase_reports.partials.stats')

    @include('purchase_reports.partials.filters')


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Purchases
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="purchaseReportTable"
                    class="table table-bordered table-hover align-middle w-100"
                >

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Purchase</th>
                            <th>Date</th>
                            <th>Supplier</th>
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

            case 'Paid':
            case 'paid':
                return `
                    <span class="badge bg-success">
                        Paid
                    </span>
                `;

            case 'Partially Paid':
            case 'partial':
            case 'Partially':
                return `
                    <span class="badge bg-warning text-dark">
                        Partially Paid
                    </span>
                `;

            default:
                return `
                    <span class="badge bg-danger">
                        Unpaid
                    </span>
                `;
        }
    }


    function statusBadge(status) {

        switch (status) {

            case 'Completed':
            case 'completed':
                return `
                    <span class="badge bg-success">
                        Completed
                    </span>
                `;

            case 'Draft':
            case 'draft':
                return `
                    <span class="badge bg-warning text-dark">
                        Draft
                    </span>
                `;

            case 'Cancelled':
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


    const table = $('#purchaseReportTable').DataTable({

        processing: true,
        serverSide: false,
        responsive: true,

        ajax: {

            url:
                "{{ route('purchase-reports.datatable') }}",

            data: function (d) {

                d.date_from =
                    $('#dateFrom').val();

                d.date_to =
                    $('#dateTo').val();

                d.supplier_id =
                    $('#supplierFilter').val();

                d.payment_status =
                    $('#paymentStatusFilter').val();

                d.status =
                    $('#purchaseStatusFilter').val();

            },

            dataSrc: function (json) {

                loadPurchaseStatistics();

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
                data: 'purchase_number',

                render: function (data, type, row) {

                    if (type !== 'display') {
                        return data;
                    }

                    const url =
                        "{{ route(
                            'purchase-reports.show',
                            ['purchaseId' => '__ID__']
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
                data: 'purchase_date'
            },

            {
                data: 'supplier'
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
                            'purchase-reports.show',
                            ['purchaseId' => '__ID__']
                        ) }}"
                        .replace(
                            '__ID__',
                            row.id
                        );

                    return `
                        <a
                            href="${url}"
                            class="btn btn-sm btn-info"
                            title="View Purchase"
                        >
                            <i class="fas fa-eye"></i>
                        </a>
                    `;

                }
            }

        ],

        order: [
            [2, 'desc']
        ],

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ]

    });


    $('#dateFrom, #dateTo, #supplierFilter, #paymentStatusFilter, #purchaseStatusFilter')
        .on('change', function () {

            table.ajax.reload();

        });


    $('#resetPurchaseReport').on('click', function () {

        $('#dateFrom').val('');
        $('#dateTo').val('');
        $('#supplierFilter').val('');
        $('#paymentStatusFilter').val('');
        $('#purchaseStatusFilter').val('');

        table.ajax.reload();

    });


    function loadPurchaseStatistics() {

        $.ajax({

            url:
                "{{ route('purchase-reports.statistics') }}",

            type: 'GET',

            data: {

                date_from:
                    $('#dateFrom').val(),

                date_to:
                    $('#dateTo').val(),

                supplier_id:
                    $('#supplierFilter').val(),

                payment_status:
                    $('#paymentStatusFilter').val(),

                status:
                    $('#purchaseStatusFilter').val()

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


                $('#statPurchaseCount').text(
                    Number(
                        stats.purchase_count || 0
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
                        stats.quantity_purchased || 0
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