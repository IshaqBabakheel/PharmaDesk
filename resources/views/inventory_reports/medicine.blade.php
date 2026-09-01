@extends('layouts.app')

@section('title', 'Inventory Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-pills text-primary me-2"></i>
                {{ $medicine->name }}
            </h3>

            <p class="text-muted mb-0">
                Inventory and batch details.
            </p>

        </div>

        <a
            href="{{ route('inventory-reports.index') }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <small class="text-muted">
                        Category
                    </small>

                    <div class="fw-semibold">
                        {{ $medicine->category?->name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Type
                    </small>

                    <div class="fw-semibold">
                        {{ $medicine->type?->name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Unit
                    </small>

                    <div class="fw-semibold">
                        {{ $medicine->unit?->name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <small class="text-muted">
                        Manufacturer
                    </small>

                    <div class="fw-semibold">
                        {{ $medicine->manufacturer?->name ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Stock
                    </small>

                    <div class="fs-4 fw-bold text-primary">
                        {{ number_format($summary['stock']) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Batches
                    </small>

                    <div class="fs-4 fw-bold">
                        {{ number_format($summary['batches']) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Stock Cost
                    </small>

                    <div class="fs-5 fw-bold text-success">
                        Rs.
                        {{ number_format(
                            $summary['stock_cost'],
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Retail Value
                    </small>

                    <div class="fs-5 fw-bold text-info">
                        Rs.
                        {{ number_format(
                            $summary['retail_value'],
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Potential Profit
                    </small>

                    <div class="fs-5 fw-bold text-success">
                        Rs.
                        {{ number_format(
                            $summary['potential_profit'],
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Expiring Batches
                    </small>

                    <div class="fs-4 fw-bold text-warning">
                        {{ number_format(
                            $summary['near_expiry_batches']
                        ) }}
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-boxes-stacked me-2 text-primary"></i>
                Available Batches
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                >

                    <thead class="table-light">

                        <tr>
                            <th>Batch</th>
                            <th>Source</th>
                            <th>Purchase</th>
                            <th>Expiry</th>
                            <th>Days Left</th>
                            <th>Available</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Stock Cost</th>
                            <th>Retail Value</th>
                            <th>Potential Profit</th>
                            <th>Stock Ledger</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($batches as $batch)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $batch['batch_number'] ?? '-' }}
                                </td>

                                <td>
                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $batch['source']
                                        )
                                    ) }}
                                </td>

                                <td>
                                    {{ $batch['purchase_number'] ?? '-' }}
                                </td>

                                <td>
                                    @if ($batch['expiry_date'])

                                        {{ \Carbon\Carbon::parse(
                                            $batch['expiry_date']
                                        )->format('d M Y') }}

                                    @else
                                        -
                                    @endif
                                </td>

                                <td>

                                    @if (
                                        $batch['days_remaining'] !== null
                                    )

                                        @if (
                                            $batch['expiry_status'] === 'expired'
                                        )

                                            <span class="badge bg-danger">
                                                Expired
                                            </span>

                                        @elseif (
                                            $batch['expiry_status'] === 'critical'
                                        )

                                            <span class="badge bg-danger">
                                                {{ $batch['days_remaining'] }}
                                                days
                                            </span>

                                        @elseif (
                                            $batch['expiry_status'] === 'warning'
                                        )

                                            <span class="badge bg-warning text-dark">
                                                {{ $batch['days_remaining'] }}
                                                days
                                            </span>

                                        @elseif (
                                            $batch['expiry_status'] === 'upcoming'
                                        )

                                            <span class="badge bg-info">
                                                {{ $batch['days_remaining'] }}
                                                days
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                {{ $batch['days_remaining'] }}
                                                days
                                            </span>

                                        @endif

                                    @else
                                        -
                                    @endif

                                </td>

                                <td class="fw-bold">
                                    {{ number_format(
                                        $batch['available_quantity']
                                    ) }}
                                </td>

                                <td class="text-end">
                                    Rs.
                                    {{ number_format(
                                        $batch['purchase_price'],
                                        2
                                    ) }}
                                </td>

                                <td class="text-end">
                                    Rs.
                                    {{ number_format(
                                        $batch['selling_price'],
                                        2
                                    ) }}
                                </td>

                                <td class="text-end">
                                    Rs.
                                    {{ number_format(
                                        $batch['stock_cost'],
                                        2
                                    ) }}
                                </td>

                                <td class="text-end">
                                    Rs.
                                    {{ number_format(
                                        $batch['retail_value'],
                                        2
                                    ) }}
                                </td>

                                <td class="text-end text-success fw-semibold">
                                    Rs.
                                    {{ number_format(
                                        $batch['potential_profit'],
                                        2
                                    ) }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route(
                                            'stock-ledger.batch',
                                            [
                                                'purchaseItem' =>
                                                    $batch['purchase_item_id']
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-info"
                                        title="View Stock Ledger"
                                    >
                                        <i class="fas fa-book"></i>
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="text-center text-muted py-5"
                                >
                                    No available batches found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection