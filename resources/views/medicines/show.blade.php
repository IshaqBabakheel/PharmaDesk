@extends('layouts.app')

@section('content')

{{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="fas fa-eye text-info me-2"></i>
            Medicine Details
        </h2>
        <p class="text-muted mb-0">
            View complete medicine information and inventory status.
        </p>
    </div>
    <div>
        <nav>
            <ol class="breadcrumb justify-content-end mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('medicines.index') }}">Medicines</a>
                </li>
                <li class="breadcrumb-item active">
                    Details
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">

    <div class="col-lg-4">

        <div class="card shadow-sm mb-4">

            <div class="card-body text-center">

                <img
                    src="{{ $medicine->image ? Storage::url($medicine->image) : asset('images/no-image.png') }}"
                    class="img-fluid rounded border mb-3"
                    style="max-height:220px;"
                >

                <h4 class="fw-bold">

                    {{ $medicine->name }}

                </h4>

                <p class="text-muted">

                    {{ $medicine->strength }}

                </p>

                <span class="badge {{ $medicine->status ? 'bg-success' : 'bg-danger' }}">

                    {{ $medicine->status ? 'Active' : 'Inactive' }}

                </span>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">

                    Basic Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <strong>SKU</strong>

                        <p>{{ $medicine->sku }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Medicine Code</strong>

                        <p>{{ $medicine->medicine_code }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Barcode</strong>

                        <p>{{ $medicine->barcode }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Category</strong>

                        <p>{{ $medicine->category?->name }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Type</strong>

                        <p>{{ $medicine->type?->name }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Manufacturer</strong>

                        <p>{{ $medicine->manufacturer?->name }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Unit</strong>

                        <p>{{ $medicine->unit?->name }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Generic Name</strong>

                        <p>{{ $medicine->generic_name ?: '-' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Purchase Price</strong>

                        <p>{{ number_format($medicine->purchase_price,2) }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Selling Price</strong>

                        <p>{{ number_format($medicine->selling_price,2) }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Current Stock</strong>

                        <p>{{ $medicine->current_stock }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Reorder Level</strong>

                        <p>{{ $medicine->reorder_level }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Expiry Required</strong>

                        <p>{{ $medicine->has_expiry ? 'Yes' : 'No' }}</p>

                    </div>

                    <div class="col-md-6 mb-3">

                        <strong>Created By</strong>

                        <p>{{ $medicine->creator?->name }}</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="d-flex justify-content-end gap-2 mt-4">

    <a
        href="{{ route('medicines.edit', $medicine) }}"
        class="btn btn-warning"
    >

        <i class="fas fa-edit me-1"></i>

        Edit

    </a>

    <a
        href="{{ route('medicines.index') }}"
        class="btn btn-secondary"
    >

        Back

    </a>

</div>

@endsection