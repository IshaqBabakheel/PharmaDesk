@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-file-invoice text-primary me-2"></i>
                Sale Details
            </h2>

            <p class="text-muted mb-0">
                Invoice {{ $sale->invoice_number }}
            </p>
        </div>

        <div class="d-flex gap-2">

            @if ($sale->isDraft())
                @can('sales.edit', $sale)
                    <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Edit
                    </a>
                @endcan
            @endif

            <button type="button" class="btn btn-dark" onclick="window.print()">
                <i class="fas fa-print me-1"></i>
                Print
            </button>

            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            {{ $sale->invoice_number }}
                        </h5>

                        <small class="text-muted">
                            {{ $sale->sale_date?->format('d M Y') }}
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        {!! $sale->status_badge !!}
                        {!! $sale->payment_status_badge !!}
                    </div>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <small class="text-muted d-block">Customer</small>

                            <strong>
                                {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Doctor</small>

                            <strong>
                                {{ $sale->doctor_name ?: '-' }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Created By</small>

                            <strong>
                                {{ $sale->creator?->name ?? '-' }}
                            </strong>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Created At</small>

                            <strong>
                                {{ $sale->created_at?->format('d M Y h:i A') }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-pills text-primary me-2"></i>
                        Sold Medicines
                    </h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th>#</th>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th>Expiry</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                    <th>Total</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($sale->items as $index => $item)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <strong>
                                                {{ $item->medicine?->name ?? '-' }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $item->batch_number ?: '-' }}
                                        </td>

                                        <td>
                                            {{ $item->expiry_date?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $item->quantity }}

                                            @if ($item->free_quantity > 0)
                                                <small class="text-success">
                                                    +{{ $item->free_quantity }} Free
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            {{ number_format($item->selling_price, 2) }}
                                        </td>

                                        <td>
                                            {{ number_format($item->discount, 2) }}
                                        </td>

                                        <td>
                                            {{ number_format($item->tax, 2) }}
                                        </td>

                                        <td class="fw-bold">
                                            {{ number_format($item->total, 2) }}
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">
                    <h5 class="mb-0">Sale Summary</h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>{{ number_format($sale->subtotal, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount</span>
                        <strong>{{ number_format($sale->discount, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax</span>
                        <strong>{{ number_format($sale->tax, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <strong>{{ number_format($sale->shipping, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Other Charges</span>
                        <strong>{{ number_format($sale->other_charges, 2) }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Grand Total</span>
                        <strong class="text-primary fs-5">
                            {{ number_format($sale->grand_total, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Paid</span>
                        <strong class="text-success">
                            {{ number_format($sale->paid_amount, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Due</span>
                        <strong class="text-danger">
                            {{ number_format($sale->due_amount, 2) }}
                        </strong>
                    </div>

                </div>

            </div>

            @if ($sale->notes)
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-note-sticky me-2"></i>
                            Notes
                        </h5>
                    </div>

                    <div class="card-body">
                        {{ $sale->notes }}
                    </div>

                </div>
            @endif

            @if ($sale->isDraft())
                @can('sales.complete', $sale)
                    <form action="{{ route('sales.complete', $sale) }}" method="POST" class="complete-sale-form mb-2">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check-circle me-1"></i>
                            Complete Sale
                        </button>
                    </form>
                @endcan
            @endif

            @if (!$sale->isCancelled())
                @can('sales.cancel', $sale)
                    <form action="{{ route('sales.cancel', $sale) }}" method="POST" class="cancel-sale-form">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-ban me-1"></i>
                            Cancel Sale
                        </button>
                    </form>
                @endcan
            @endif

        </div>

    </div>

    @include('sales.partials.payment-modal')

@endsection

@push('scripts')
    <script>
        $(document).on('submit', '.complete-sale-form', function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Complete Sale?',
                text: 'The sale will be completed and stock will be deducted.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Complete',
                confirmButtonColor: '#198754'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $(document).on('submit', '.cancel-sale-form', function(e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Cancel Sale?',
                text: 'The sale will be cancelled and stock will be restored where applicable.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel Sale',
                confirmButtonColor: '#dc3545'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
