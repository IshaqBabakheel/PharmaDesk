@extends('layouts.app')

@section('title', 'Stock Adjustment Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h3 class="mb-1">

                <i class="fas fa-sliders text-primary me-2"></i>

                Stock Adjustment

                <strong>
                    {{ $stockAdjustment->adjustment_number }}
                </strong>

            </h3>

            <p class="text-muted mb-0">

                View stock adjustment details and affected batches.

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('stock-adjustments.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>


            @if ($stockAdjustment->isDraft())

                @can('stock-adjustments.edit')

                    <a
                        href="{{ route(
                            'stock-adjustments.edit',
                            $stockAdjustment
                        ) }}"
                        class="btn btn-warning"
                    >

                        <i class="fas fa-edit me-1"></i>

                        Edit

                    </a>

                @endcan

            @endif

        </div>

    </div>


    {{-- Success --}}
    @if (session('success'))

        <div class="alert alert-success">

            <i class="fas fa-circle-check me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- Error --}}
    @if (session('error'))

        <div class="alert alert-danger">

            <i class="fas fa-circle-exclamation me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    <div class="row g-4">


        {{-- Adjustment Information --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fas fa-file-invoice text-primary me-2"></i>

                        Adjustment Information

                    </h5>

                </div>


                <div class="card-body">


                    {{-- Adjustment Number --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Adjustment Number
                        </label>

                        <div class="fw-semibold">
                            {{ $stockAdjustment->adjustment_number }}
                        </div>

                    </div>


                    {{-- Type --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Type
                        </label>

                        <div>

                            @if (
                                $stockAdjustment->type === 'increase'
                            )

                                <span class="badge bg-success">

                                    <i class="fas fa-arrow-up me-1"></i>

                                    Increase Stock

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    <i class="fas fa-arrow-down me-1"></i>

                                    Decrease Stock

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Date --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Adjustment Date
                        </label>

                        <div class="fw-semibold">

                            {{ $stockAdjustment->adjustment_date?->format('d M Y') ?? '-' }}

                        </div>

                    </div>


                    {{-- Reason --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Reason
                        </label>

                        <div class="fw-semibold">

                            {{ $stockAdjustment->reason ?: '-' }}

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Status
                        </label>

                        <div>

                            @switch($stockAdjustment->status)

                                @case('completed')

                                    <span class="badge bg-success">

                                        <i class="fas fa-check-circle me-1"></i>

                                        Completed

                                    </span>

                                    @break


                                @case('cancelled')

                                    <span class="badge bg-danger">

                                        <i class="fas fa-ban me-1"></i>

                                        Cancelled

                                    </span>

                                    @break


                                @default

                                    <span class="badge bg-warning text-dark">

                                        <i class="fas fa-clock me-1"></i>

                                        Draft

                                    </span>

                            @endswitch

                        </div>

                    </div>


                    {{-- Stock State --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Stock Status
                        </label>

                        <div>

                            @if ($stockAdjustment->stock_applied)

                                <span class="badge bg-success">

                                    <i class="fas fa-database me-1"></i>

                                    Stock Applied

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    <i class="fas fa-database me-1"></i>

                                    Stock Not Applied

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Created By --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Created By
                        </label>

                        <div>

                            {{ $stockAdjustment->creator?->name ?? '-' }}

                        </div>

                    </div>


                    {{-- Updated By --}}
                    <div class="mb-3">

                        <label class="text-muted small">
                            Updated By
                        </label>

                        <div>

                            {{ $stockAdjustment->updater?->name ?? '-' }}

                        </div>

                    </div>


                    {{-- Notes --}}
                    <div>

                        <label class="text-muted small">
                            Notes
                        </label>

                        <div class="border rounded p-3 bg-light">

                            {!! nl2br(
                                e(
                                    $stockAdjustment->notes
                                    ?: 'No notes.'
                                )
                            ) !!}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Items --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            <i class="fas fa-boxes-stacked text-primary me-2"></i>

                            Adjustment Items

                        </h5>


                        <span class="badge bg-primary">

                            {{ $stockAdjustment->items->count() }}

                            {{ Str::plural(
                                'Item',
                                $stockAdjustment->items->count()
                            ) }}

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Medicine
                                    </th>

                                    <th>
                                        Batch
                                    </th>

                                    <th>
                                        Expiry
                                    </th>

                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Purchase Price
                                    </th>

                                    <th>
                                        Selling Price
                                    </th>

                                    <th>
                                        Notes
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse (
                                    $stockAdjustment->items
                                    as $index => $item
                                )

                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        <td>

                                            <strong>

                                                {{ $item->medicine?->name ?? '-' }}

                                            </strong>

                                        </td>


                                        <td>

                                            {{ $item->batch_number ?? '-' }}

                                        </td>


                                        <td>

                                            {{ $item->expiry_date?->format('d M Y') ?? '-' }}

                                        </td>


                                        <td>

                                            <span
                                                class="fw-semibold
                                                {{ $stockAdjustment->type === 'increase'
                                                    ? 'text-success'
                                                    : 'text-danger'
                                                }}"
                                            >

                                                @if (
                                                    $stockAdjustment->type === 'increase'
                                                )
                                                    +
                                                @else
                                                    -
                                                @endif

                                                {{ number_format(
                                                    $item->quantity
                                                ) }}

                                            </span>

                                        </td>


                                        <td>

                                            {{ number_format(
                                                (float) $item->purchase_price,
                                                2
                                            ) }}

                                        </td>


                                        <td>

                                            {{ number_format(
                                                (float) $item->selling_price,
                                                2
                                            ) }}

                                        </td>


                                        <td>

                                            {{ $item->notes ?: '-' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="text-center text-muted py-5"
                                        >

                                            <i class="fas fa-box-open fa-2x mb-2"></i>

                                            <div>
                                                No adjustment items found.
                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- Quick Summary --}}
            <div class="row g-3 mt-1">

                <div class="col-md-4">

                    <div class="card shadow-sm border-0">

                        <div class="card-body">

                            <div class="text-muted small">
                                Total Items
                            </div>

                            <div class="fs-4 fw-bold">

                                {{ $stockAdjustment->items->count() }}

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card shadow-sm border-0">

                        <div class="card-body">

                            <div class="text-muted small">
                                Total Quantity
                            </div>

                            <div class="fs-4 fw-bold">

                                {{ number_format(
                                    $stockAdjustment->items->sum('quantity')
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card shadow-sm border-0">

                        <div class="card-body">

                            <div class="text-muted small">
                                Stock State
                            </div>

                            <div class="fs-5 fw-bold">

                                @if ($stockAdjustment->stock_applied)

                                    <span class="text-success">
                                        Applied
                                    </span>

                                @else

                                    <span class="text-muted">
                                        Not Applied
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection