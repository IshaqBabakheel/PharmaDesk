@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-start mb-4">

            <div>
                <h3 class="mb-1">
                    <i class="fas fa-money-bill-wave text-primary me-2"></i>
                    Payment Details
                </h3>

                <p class="text-muted mb-0">
                    {{ $payment->payment_number }}
                </p>
            </div>

            <div class="d-flex gap-2">

                @can('payments.edit')
                    @if (!$payment->trashed())
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>
                            Edit
                        </a>
                    @endif
                @endcan

                <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back
                </a>

            </div>

        </div>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">Payment Information</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <small class="text-muted">Payment Number</small>
                            <div class="fw-semibold">
                                {{ $payment->payment_number }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Type</small>
                            <div>
                                @if ($payment->isReceipt())
                                    <span class="badge bg-success">
                                        Receipt
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        Supplier Payment
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Amount</small>
                            <div class="fs-4 fw-bold">
                                Rs. {{ number_format($payment->amount, 2) }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Method</small>
                            <div>
                                {{ ucwords(str_replace('_', ' ', $payment->method)) }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Payment Date</small>
                            <div>
                                {{ $payment->payment_date?->format('d M Y') ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Reference Number</small>
                            <div>
                                {{ $payment->reference_number ?: '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Created By</small>
                            <div>
                                {{ $payment->creator?->name ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <small class="text-muted">Notes</small>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($payment->notes ?: 'No notes.')) !!}
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">Reference</h5>
                    </div>

                    <div class="card-body">

                        @if ($payment->sale)
                            <div class="row g-3">

                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Invoice
                                    </small>

                                    <div>

                                        <a href="{{ route('sales.show', $payment->sale) }}" class="fw-semibold">
                                            {{ $payment->sale->invoice_number }}
                                        </a>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Customer
                                    </small>

                                    <div>
                                        {{ $payment->customer?->name ?? 'Walk-in Customer' }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Sale Total
                                    </small>

                                    <div>
                                        Rs.
                                        {{ number_format($payment->sale->grand_total, 2) }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Paid
                                    </small>

                                    <div>
                                        Rs.
                                        {{ number_format($payment->sale->paid_amount, 2) }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Due
                                    </small>

                                    <div class="text-danger fw-semibold">
                                        Rs.
                                        {{ number_format($payment->sale->due_amount, 2) }}
                                    </div>

                                </div>

                            </div>
                        @elseif ($payment->purchase)
                            <div class="row g-3">

                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Purchase
                                    </small>

                                    <div>

                                        <a href="{{ route('purchases.show', $payment->purchase) }}" class="fw-semibold">
                                            {{ $payment->purchase->purchase_number }}
                                        </a>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Supplier
                                    </small>

                                    <div>
                                        {{ $payment->supplier?->name ?? '-' }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Purchase Total
                                    </small>

                                    <div>
                                        Rs.
                                        {{ number_format($payment->purchase->grand_total, 2) }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Paid
                                    </small>

                                    <div>
                                        Rs.
                                        {{ number_format($payment->purchase->paid_amount, 2) }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Due
                                    </small>

                                    <div class="text-danger fw-semibold">
                                        Rs.
                                        {{ number_format($payment->purchase->due_amount, 2) }}
                                    </div>

                                </div>

                            </div>
                        @elseif ($payment->expense)
                            <div class="row g-3">

                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Expense
                                    </small>

                                    <div>

                                        <a href="{{ route('expenses.show', $payment->expense) }}" class="fw-semibold">
                                            {{ $payment->expense->expense_number }}
                                        </a>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Category
                                    </small>

                                    <div>
                                        {{ $payment->expense->category?->name ?? '-' }}
                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Title
                                    </small>

                                    <div>
                                        {{ $payment->expense->title }}
                                    </div>

                                </div>


                                <div class="col-md-3">

                                    <small class="text-muted">
                                        Expense Total
                                    </small>

                                    <div>
                                        Rs.
                                        {{ number_format($payment->expense->amount, 2) }}
                                    </div>

                                </div>


                                <div class="col-md-3">

                                    <small class="text-muted">
                                        Paid
                                    </small>

                                    <div class="text-success fw-semibold">
                                        Rs.
                                        {{ number_format($payment->expense->paid_amount, 2) }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Due
                                    </small>

                                    <div class="text-danger fw-semibold">
                                        Rs.
                                        {{ number_format($payment->expense->due_amount, 2) }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Payment Status
                                    </small>

                                    <div>
                                        {{ $payment->expense->payment_status }}
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted">
                                        Expense Date
                                    </small>

                                    <div>
                                        {{ $payment->expense->expense_date?->format('d M Y') ?? '-' }}
                                    </div>

                                </div>

                            </div>
                        @else
                            <div class="text-muted">
                                No sale, purchase, or expense reference is attached.
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
