@extends('layouts.app')

@section('title','Purchase Details')

@section('content')

<div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

    <div>

        <h2 class="fw-bold">

            Purchase Details

        </h2>

    </div>

    <div>

        <a
            href="{{ route('purchases.index') }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>

</div>

<div class="card shadow-sm border-0 mb-4">

    <div class="card-header">

        Purchase Information

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3">

                <strong>Purchase #</strong>

                <br>

                {{ $purchase->purchase_number }}

            </div>

            <div class="col-md-3">

                <strong>Supplier</strong>

                <br>

                {{ $purchase->supplier->name }}

            </div>

            <div class="col-md-3">

                <strong>Date</strong>

                <br>

                {{ $purchase->purchase_date->format('d M Y') }}

            </div>

            <div class="col-md-3">

                <strong>Status</strong>

                <br>

                {{ $purchase->status }}

            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-header">

        Purchased Medicines

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

            <tr>

                <th>#</th>

                <th>Medicine</th>

                <th>Batch</th>

                <th>Expiry</th>

                <th>Qty</th>

                <th>Free</th>

                <th>Purchase Price</th>

                <th>Selling Price</th>

                <th>Total</th>

            </tr>

            </thead>

            <tbody>

            @foreach($purchase->items as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->medicine->name }}</td>

                    <td>{{ $item->batch_number }}</td>

                    <td>{{ optional($item->expiry_date)->format('d M Y') }}</td>

                    <td>{{ $item->quantity }}</td>

                    <td>{{ $item->free_quantity }}</td>

                    <td>{{ number_format($item->purchase_price,2) }}</td>

                    <td>{{ number_format($item->selling_price,2) }}</td>

                    <td>{{ number_format($item->total,2) }}</td>

                </tr>

            @endforeach

            </tbody>

            <tfoot>

            <tr>

                <th colspan="8" class="text-end">

                    Grand Total

                </th>

                <th>

                    {{ number_format($purchase->grand_total,2) }}

                </th>

            </tr>

            </tfoot>

        </table>

    </div>

</div>

@endsection