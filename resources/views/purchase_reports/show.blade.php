@extends('layouts.app')

@section('title', 'Purchase Report Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-cart-shopping text-primary me-2"></i>
                {{ $purchase->purchase_number }}
            </h3>

            <p class="text-muted mb-0">
                {{ $purchase->purchase_date?->format('d M Y') }}
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('purchases.show', $purchase) }}"
                class="btn btn-primary"
            >
                <i class="fas fa-eye me-1"></i>
                Purchase
            </a>

            <a
                href="{{ route('purchase-reports.index') }}"
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
                        Supplier
                    </small>

                    <div class="fw-semibold">
                        {{ $purchase->supplier?->name ?? '-' }}
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
                            $purchase->grand_total,
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
                            $purchase->paid_amount,
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
                            $purchase->due_amount,
                            2
                        ) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-file-invoice me-2 text-primary"></i>
                Purchase Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <small class="text-muted">
                        Purchase Number
                    </small>

                    <div class="fw-semibold">
                        {{ $purchase->purchase_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Invoice Number
                    </small>

                    <div class="fw-semibold">
                        {{ $purchase->invoice_number ?: '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Reference Number
                    </small>

                    <div class="fw-semibold">
                        {{ $purchase->reference_number ?: '-' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Payment Status
                    </small>

                    <div>

                        @if ($purchase->payment_status === 'Paid')

                            <span class="badge bg-success">
                                Paid
                            </span>

                        @elseif ($purchase->payment_status === 'Partially Paid')

                            <span class="badge bg-warning text-dark">
                                Partially Paid
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Unpaid
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div>

                        @if ($purchase->status === 'Completed')

                            <span class="badge bg-success">
                                Completed
                            </span>

                        @elseif ($purchase->status === 'Draft')

                            <span class="badge bg-warning text-dark">
                                Draft
                            </span>

                        @elseif ($purchase->status === 'Cancelled')

                            <span class="badge bg-danger">
                                Cancelled
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $purchase->status }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-pills me-2 text-primary"></i>
                Purchased Medicines
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
                            <th>Expiry</th>
                            <th>Quantity</th>
                            <th>Free</th>
                            <th>Purchase Price</th>
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
                                    {{ $item->batch_number ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->expiry_date?->format('d M Y') ?? '-' }}
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
                                        $item->purchase_price,
                                        2
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
                                    colspan="11"
                                    class="text-center text-muted py-5"
                                >
                                    No purchase items found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    @if ($purchase->notes)

        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Notes
                </h5>

            </div>

            <div class="card-body">
                {!! nl2br(e($purchase->notes)) !!}
            </div>

        </div>

    @endif

</div>

@endsection