@extends('layouts.app')

@section('title', 'Sales Report Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-receipt text-primary me-2"></i>
                {{ $sale->invoice_number }}
            </h3>

            <p class="text-muted mb-0">
                {{ $sale->sale_date?->format('d M Y') }}
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('sales.show', $sale) }}"
                class="btn btn-primary"
            >
                <i class="fas fa-eye me-1"></i>
                Sale
            </a>

            <a
                href="{{ route('sales-reports.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Customer
                    </small>

                    <div class="fw-semibold">
                        {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Grand Total
                    </small>

                    <div class="fs-5 fw-bold">
                        Rs.
                        {{ number_format(
                            $sale->grand_total,
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Paid
                    </small>

                    <div class="fs-5 fw-bold text-success">
                        Rs.
                        {{ number_format(
                            $sale->paid_amount,
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Due
                    </small>

                    <div class="fs-5 fw-bold text-danger">
                        Rs.
                        {{ number_format(
                            $sale->due_amount,
                            2
                        ) }}
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-pills me-2 text-primary"></i>
                Sold Medicines
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Quantity</th>
                            <th>Free</th>
                            <th>Selling Price</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($items as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $item->medicine?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->purchaseItem?->batch_number ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format(
                                        $item->quantity
                                    ) }}
                                </td>

                                <td>
                                    {{ number_format(
                                        $item->free_quantity
                                    ) }}
                                </td>

                                <td class="text-end">
                                    Rs.
                                    {{ number_format(
                                        $item->selling_price,
                                        2
                                    ) }}
                                </td>

                                <td class="text-end">
                                    Rs.
                                    {{ number_format(
                                        $item->discount,
                                        2
                                    ) }}
                                </td>

                                <td class="text-end">
                                    Rs.
                                    {{ number_format(
                                        $item->tax,
                                        2
                                    ) }}
                                </td>

                                <td class="text-end fw-semibold">
                                    Rs.
                                    {{ number_format(
                                        $item->total,
                                        2
                                    ) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="9"
                                    class="text-center text-muted py-4"
                                >
                                    No sale items found.
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