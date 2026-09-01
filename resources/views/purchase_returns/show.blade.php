@extends('layouts.app')

@section('title','Purchase Return Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                <i class="fas fa-rotate-left text-primary me-2"></i>

                Purchase Return Details

            </h3>

            <p class="text-muted mb-0">

                View complete purchase return information.

            </p>

        </div>

        <div>

            @can('purchase-returns.edit')

            <a
                href="{{ route('purchase-returns.edit',$purchaseReturn) }}"
                class="btn btn-warning"
            >

                <i class="fas fa-edit me-2"></i>

                Edit

            </a>

            @endcan

            <a
                href="{{ route('purchase-returns.index') }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-arrow-left me-2"></i>

                Back

            </a>

        </div>

    </div>

    <div class="row">

        {{-- Left Side --}}
        <div class="col-lg-8">

            {{-- General Information --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        Return Information

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <strong>Return Number</strong>

                            <div>

                                {{ $purchaseReturn->return_number }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Purchase Number</strong>

                            <div>

                                {{ $purchaseReturn->purchase->purchase_number }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Supplier</strong>

                            <div>

                                {{ $purchaseReturn->supplier->name }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Return Date</strong>

                            <div>

                                {{ \Carbon\Carbon::parse($purchaseReturn->return_date)->format('d M, Y') }}

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Status</strong>

                            <div>

                                @if($purchaseReturn->status=='Completed')

                                    <span class="badge bg-success">

                                        Completed

                                    </span>

                                @elseif($purchaseReturn->status=='Draft')

                                    <span class="badge bg-warning">

                                        Draft

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Cancelled

                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Reason</strong>

                            <div>

                                {{ $purchaseReturn->reason ?: '-' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Medicines --}}
            <div class="card shadow-sm border-0">

                <div class="card-header">

                    <h5 class="mb-0">

                        Returned Medicines

                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Medicine</th>

                                <th>Batch</th>

                                <th>Expiry</th>

                                <th class="text-center">

                                    Qty

                                </th>

                                <th class="text-end">

                                    Price

                                </th>

                                <th class="text-end">

                                    Total

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($purchaseReturn->items as $item)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    {{ $item->medicine->name }}

                                </td>

                                <td>

                                    {{ $item->batch_number }}

                                </td>

                                <td>

                                    {{ optional($item->expiry_date)->format('d M Y') }}

                                </td>

                                <td class="text-center">

                                    {{ number_format($item->quantity) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format($item->purchase_price,2) }}

                                </td>

                                <td class="text-end fw-bold">

                                    {{ number_format($item->total,2) }}

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- Right Side --}}
        <div class="col-lg-4">

            {{-- Financial Summary --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        Financial Summary

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tr>

                            <th>

                                Subtotal

                            </th>

                            <td class="text-end">

                                {{ number_format($purchaseReturn->subtotal,2) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Discount

                            </th>

                            <td class="text-end">

                                {{ number_format($purchaseReturn->discount,2) }}

                                {{ $purchaseReturn->discount_type=='Percentage' ? '%' : '' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Tax

                            </th>

                            <td class="text-end">

                                {{ number_format($purchaseReturn->tax,2) }}

                                {{ $purchaseReturn->tax_type=='Percentage' ? '%' : '' }}

                            </td>

                        </tr>

                        <tr class="border-top">

                            <th>

                                Grand Total

                            </th>

                            <th class="text-end text-primary fs-5">

                                {{ number_format($purchaseReturn->grand_total,2) }}

                            </th>

                        </tr>

                    </table>

                </div>

            </div>

            {{-- Notes --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header">

                    <h5 class="mb-0">

                        Notes

                    </h5>

                </div>

                <div class="card-body">

                    {!! nl2br(e($purchaseReturn->notes ?: 'No notes available.')) !!}

                </div>

            </div>

            {{-- Audit --}}
            <div class="card shadow-sm border-0">

                <div class="card-header">

                    <h5 class="mb-0">

                        Audit Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-sm table-borderless mb-0">

                        <tr>

                            <th>

                                Created By

                            </th>

                            <td>

                                {{ optional($purchaseReturn->creator)->name }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Updated By

                            </th>

                            <td>

                                {{ optional($purchaseReturn->updater)->name ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Created At

                            </th>

                            <td>

                                {{ $purchaseReturn->created_at->format('d M Y h:i A') }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Updated At

                            </th>

                            <td>

                                {{ $purchaseReturn->updated_at->format('d M Y h:i A') }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection