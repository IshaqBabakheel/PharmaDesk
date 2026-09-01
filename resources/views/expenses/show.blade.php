@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-receipt text-primary me-2"></i>
                {{ $expense->expense_number }}
            </h3>

            <p class="text-muted mb-0">
                {{ $expense->expense_date?->format('d M Y') }}
            </p>

        </div>


        <div class="d-flex gap-2">

            @can('expenses.edit')

                @if (!$expense->isCancelled())

                    <a
                        href="{{ route(
                            'expenses.edit',
                            $expense
                        ) }}"
                        class="btn btn-warning"
                    >
                        <i class="fas fa-edit me-1"></i>
                        Edit
                    </a>

                @endif

            @endcan


            <a
                href="{{ route('expenses.index') }}"
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
                        Category
                    </small>

                    <div class="fw-semibold">
                        {{ $expense->category?->name ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Amount
                    </small>

                    <div class="fs-5 fw-bold">
                        Rs.
                        {{ number_format(
                            $expense->amount,
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
                            $expense->paid_amount,
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
                            $expense->due_amount,
                            2
                        ) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Expense Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted">
                                Title
                            </small>

                            <div class="fw-semibold">
                                {{ $expense->title }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Payment Method
                            </small>

                            <div>
                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $expense->payment_method
                                    )
                                ) }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Payment Status
                            </small>

                            <div>

                                @if ($expense->payment_status === 'Paid')

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif (
                                    $expense->payment_status === 'Partially Paid'
                                )

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


                        <div class="col-md-6">

                            <small class="text-muted">
                                Status
                            </small>

                            <div>

                                @if ($expense->isCompleted())

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @elseif ($expense->isDraft())

                                    <span class="badge bg-warning text-dark">
                                        Draft
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Cancelled
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Reference Number
                            </small>

                            <div>
                                {{ $expense->reference_number ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Created By
                            </small>

                            <div>
                                {{ $expense->creator?->name ?? '-' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <small class="text-muted">
                                Description
                            </small>

                            <div class="mt-1">
                                {!! nl2br(
                                    e(
                                        $expense->description
                                        ?: 'No description.'
                                    )
                                ) !!}
                            </div>

                        </div>


                        <div class="col-12">

                            <small class="text-muted">
                                Notes
                            </small>

                            <div class="border rounded p-3 bg-light mt-1">
                                {!! nl2br(
                                    e(
                                        $expense->notes
                                        ?: 'No notes.'
                                    )
                                ) !!}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Payment History
                    </h5>

                </div>

                <div class="card-body">

                    @forelse ($expense->payments as $payment)

                        <div class="border-bottom pb-3 mb-3">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    {{ $payment->payment_number }}
                                </strong>

                                <span class="text-success fw-semibold">
                                    Rs.
                                    {{ number_format(
                                        $payment->amount,
                                        2
                                    ) }}
                                </span>

                            </div>

                            <small class="text-muted">
                                {{ $payment->payment_date?->format('d M Y') }}
                                ·
                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $payment->method
                                    )
                                ) }}
                            </small>

                        </div>

                    @empty

                        <div class="text-muted text-center py-3">
                            No payment records found.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection