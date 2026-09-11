@extends('layouts.app')

@section('content')

    {{-- Page Header --}}
    @include('suppliers.partials.page-header')

    {{-- main content --}}



<div class="row">

    {{-- Supplier Card --}}
    <div class="col-lg-4">

        <div class="card shadow-sm">

            <div class="card-body text-center">

                <div class="display-2 text-primary mb-3">

                    <i class="fas fa-truck-field"></i>

                </div>

                <h4 class="fw-bold">

                    {{ $supplier->name }}

                </h4>

                <p class="text-muted">

                    {{ $supplier->company_name ?: '-' }}

                </p>

                @if($supplier->status)

                    <span class="badge bg-success">

                        Active

                    </span>

                @else

                    <span class="badge bg-danger">

                        Inactive

                    </span>

                @endif

                <hr>

                <div>

                    <strong>Supplier Code</strong>

                    <div>

                        {{ $supplier->supplier_code }}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Details --}}
    <div class="col-lg-8">

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <strong>

                    Supplier Information

                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <strong>Contact Person</strong>

                        <p>{{ $supplier->contact_person ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Phone</strong>

                        <p>{{ $supplier->phone }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Alternate Phone</strong>

                        <p>{{ $supplier->alternate_phone ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Email</strong>

                        <p>{{ $supplier->email ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Website</strong>

                        <p>{{ $supplier->website ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>City</strong>

                        <p>{{ $supplier->city ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>State</strong>

                        <p>{{ $supplier->state ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Country</strong>

                        <p>{{ $supplier->country }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Postal Code</strong>

                        <p>{{ $supplier->postal_code ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>NTN</strong>

                        <p>{{ $supplier->ntn ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>STRN</strong>

                        <p>{{ $supplier->strn ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Opening Balance</strong>

                        <p>{{ number_format($supplier->opening_balance,2) }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Balance Type</strong>

                        <p>{{ $supplier->balance_type }}</p>

                    </div>

                    <div class="col-md-12">

                        <strong>Address</strong>

                        <p>{{ $supplier->address ?: '-' }}</p>

                    </div>

                    <div class="col-md-12">

                        <strong>Notes</strong>

                        <p>{{ $supplier->notes ?: '-' }}</p>

                    </div>

                </div>

            </div>

        </div>



        <div class="card shadow-sm">

            <div class="card-header">

                <strong>

                    Audit Information

                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <strong>Created By</strong>

                        <p>{{ $supplier->creator?->name ?? '-' }}</p>

                    </div>

                    <div class="col-md-6">

                        <strong>Created At</strong>

                        <p>{{ $supplier->created_at?->format('d M Y h:i A') }}</p>

                    </div>

                    <div class="col-md-6">

                        <strong>Updated By</strong>

                        <p>{{ $supplier->updater?->name ?? '-' }}</p>

                    </div>

                    <div class="col-md-6">

                        <strong>Updated At</strong>

                        <p>{{ $supplier->updated_at?->format('d M Y h:i A') }}</p>

                    </div>

                    <div class="col-md-6">

                        <strong>Deleted By</strong>

                        <p>{{ $supplier->deleter?->name ?? '-' }}</p>

                    </div>

                    <div class="col-md-6">

                        <strong>Deleted At</strong>

                        <p>{{ $supplier->deleted_at?->format('d M Y h:i A') ?? '-' }}</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<div class="d-flex justify-content-end gap-2 mt-4">

    <a
        href="{{ route('suppliers.edit', $supplier) }}"
        class="btn btn-warning"
    >

        <i class="fas fa-edit me-1"></i>

        Edit

    </a>

    <a
        href="{{ route('suppliers.index') }}"
        class="btn btn-secondary"
    >

        Back

    </a>

</div>

@endsection