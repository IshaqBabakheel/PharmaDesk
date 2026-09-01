@extends('layouts.app')

@section('title', 'Sale Return ' . $saleReturn->return_number)

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">

                    <i class="fas fa-file-invoice-dollar text-primary"></i>

                    Sale Return

                </h3>

                <p class="text-muted mb-0">

                    {{ $saleReturn->return_number }}

                </p>

            </div>


            <div>

                <a
                    href="{{ route('sale-returns.index') }}"
                    class="btn btn-secondary"
                >

                    <i class="fas fa-arrow-left me-1"></i>

                    Back

                </a>


                @can('sale-returns.update')

                    <a
                        href="{{ route('sale-returns.edit', $saleReturn) }}"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-pen-to-square me-1"></i>

                        Edit

                    </a>

                @endcan

            </div>

        </div>


        {{-- Return Header --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <small class="text-muted">
                            Return Number
                        </small>

                        <h5 class="mb-0">
                            {{ $saleReturn->return_number }}
                        </h5>

                    </div>


                    <div class="col-md-3 mb-3">

                        <small class="text-muted">
                            Original Invoice
                        </small>

                        <h5 class="mb-0">

                            {{ $saleReturn->sale?->invoice_number ?? '-' }}

                        </h5>

                    </div>


                    <div class="col-md-2 mb-3">

                        <small class="text-muted">
                            Return Date
                        </small>

                        <h5 class="mb-0">

                            {{ $saleReturn->return_date?->format('d M Y') ?? '-' }}

                        </h5>

                    </div>


                    <div class="col-md-2 mb-3">

                        <small class="text-muted">
                            Customer
                        </small>

                        <h5 class="mb-0">

                            {{ $saleReturn->customer?->name ?? 'Walk-in Customer' }}

                        </h5>

                    </div>


                    <div class="col-md-2 mb-3">

                        <small class="text-muted">
                            Status
                        </small>

                        <div>

                            @if ($saleReturn->status === 'completed')

                                <span class="badge bg-success">
                                    Completed
                                </span>

                            @elseif ($saleReturn->status === 'draft')

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

                </div>

            </div>

        </div>


        {{-- Return Items --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-pills me-1 text-primary"></i>

                    Returned Items

                </h5>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Medicine</th>

                                <th>Batch</th>

                                <th>Expiry</th>

                                <th>Return Qty</th>

                                <th>Free Qty</th>

                                <th>Selling Price</th>

                                <th>Discount</th>

                                <th>Tax</th>

                                <th>Total</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($saleReturn->items as $index => $item)

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

                                        {{ $item->expiry_date
                                            ? \Carbon\Carbon::parse($item->expiry_date)->format('d M Y')
                                            : '-'
                                        }}

                                    </td>


                                    <td>

                                        {{ number_format($item->quantity) }}

                                    </td>


                                    <td>

                                        {{ number_format($item->free_quantity) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float) $item->selling_price,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float) $item->discount,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            (float) $item->tax,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end fw-bold">

                                        {{ number_format(
                                            (float) $item->total,
                                            2
                                        ) }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="10"
                                        class="text-center text-muted py-4"
                                    >

                                        No return items found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Bottom Section --}}
        <div class="row">

            {{-- Notes --}}
            <div class="col-lg-7 mb-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            <i class="fas fa-note-sticky me-1 text-primary"></i>

                            Notes

                        </h5>

                    </div>


                    <div class="card-body">

                        @if ($saleReturn->notes)

                            <p class="mb-0">

                                {{ $saleReturn->notes }}

                            </p>

                        @else

                            <span class="text-muted">

                                No notes added.

                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Summary --}}
            <div class="col-lg-5 mb-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Return Summary
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                Subtotal
                            </span>

                            <strong>

                                {{ number_format(
                                    (float) $saleReturn->subtotal,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                Discount
                            </span>

                            <strong class="text-danger">

                                -
                                {{ number_format(
                                    (float) $saleReturn->discount,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                Tax
                            </span>

                            <strong>

                                {{ number_format(
                                    (float) $saleReturn->tax,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                Other Charges
                            </span>

                            <strong>

                                {{ number_format(
                                    (float) $saleReturn->other_charges,
                                    2
                                ) }}

                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong class="fs-5">

                                Grand Total

                            </strong>

                            <strong class="fs-5 text-danger">

                                {{ number_format(
                                    (float) $saleReturn->grand_total,
                                    2
                                ) }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Audit Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row text-muted small">

                    <div class="col-md-3">

                        <strong>
                            Created By
                        </strong>

                        <br>

                        {{ $saleReturn->creator?->name ?? '-' }}

                    </div>


                    <div class="col-md-3">

                        <strong>
                            Created At
                        </strong>

                        <br>

                        {{ $saleReturn->created_at?->format('d M Y h:i A') ?? '-' }}

                    </div>


                    <div class="col-md-3">

                        <strong>
                            Updated By
                        </strong>

                        <br>

                        {{ $saleReturn->updater?->name ?? '-' }}

                    </div>


                    <div class="col-md-3">

                        <strong>
                            Updated At
                        </strong>

                        <br>

                        {{ $saleReturn->updated_at?->format('d M Y h:i A') ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection