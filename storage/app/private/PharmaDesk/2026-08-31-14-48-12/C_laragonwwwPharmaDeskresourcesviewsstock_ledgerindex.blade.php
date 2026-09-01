@extends('layouts.app')

@section('title', 'Stock Ledger')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                <i class="fas fa-book text-primary me-2"></i>

                Stock Ledger

            </h3>

            <p class="text-muted mb-0">
                Track stock movement and running batch balances.
            </p>

        </div>

    </div>


    {{-- Filters --}}
    @include('stock_ledger.partials.filters')


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Stock Movement History
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="stockLedgerTable"
                    class="table table-bordered table-hover align-middle w-100"
                >

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Medicine</th>

                            <th>Batch</th>

                            <th>Transaction</th>

                            <th>Reference</th>

                            <th class="text-end">
                                In
                            </th>

                            <th class="text-end">
                                Out
                            </th>

                            <th class="text-end">
                                Balance
                            </th>

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

    function transactionBadge(type) {

        switch (type) {

            case 'Purchase':
            case 'Opening Stock':

                return `
                    <span class="badge bg-success">
                        ${type}
                    </span>
                `;

            case 'Sale Return':

                return `
                    <span class="badge bg-info text-dark">
                        Sale Return
                    </span>
                `;

            case 'Sale':
            case 'Purchase Return':

                return `
                    <span class="badge bg-danger">
                        ${type}
                    </span>
                `;

            case 'Stock Adjustment':

                return `
                    <span class="badge bg-warning text-dark">
                        Stock Adjustment
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


    const table =
        $('#stockLedgerTable').DataTable({

            processing: true,
            serverSide: false,
            responsive: true,

            ajax: {

                url:
                    "{{ route('stock-ledger.datatable') }}",

                data: function (d) {

                    d.medicine_id =
                        $('#medicineFilter').val();

                    d.batch_number =
                        $('#batchFilter').val();

                    d.date_from =
                        $('#dateFrom').val();

                    d.date_to =
                        $('#dateTo').val();

                    d.transaction =
                        $('#transactionFilter').val();

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
                    data: 'medicine',

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        if (
                            type !== 'display'
                        ) {
                            return data;
                        }

                        const url =
                            "{{ route(
                                'stock-ledger.medicine',
                                ['medicineId' => '__ID__']
                            ) }}"
                            .replace(
                                '__ID__',
                                row.medicine_id
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
                    data: 'batch_number'
                },

                {
                    data: 'transaction',

                    render: function (data) {
                        return transactionBadge(data);
                    }

                },

                {
                    data: 'reference',

                    defaultContent: '-'
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
                },

                {
                    data: 'balance',

                    className:
                        'text-end fw-bold'
                }

            ],

            order: [
                [1, 'asc']
            ],

            pageLength: 25,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ]

        });


    $('#applyLedgerFilters')
        .on('click', function () {

            table.ajax.reload();

        });


    $('#resetLedgerFilters')
        .on('click', function () {

            $('#medicineFilter').val('');
            $('#batchFilter').val('');
            $('#dateFrom').val('');
            $('#dateTo').val('');
            $('#transactionFilter').val('all');

            table.ajax.reload();

        });


    $('#medicineFilter')
        .on('change', function () {

            table.ajax.reload();

        });


});
</script>

@endpush