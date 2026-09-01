@extends('layouts.app')

@section('title', 'Edit Sale')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-edit text-warning me-2"></i>
                Edit Sale
            </h2>

            <p class="text-muted mb-0">
                Update sale invoice and medicines.
            </p>
        </div>

        <div>
            <a href="{{ route('sales.show', $sale) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i>
                View Sale
            </a>

            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>
        </div>

    </div>

    @if ($sale->isCompleted())
        <div class="alert alert-warning">
            <i class="fas fa-triangle-exclamation me-2"></i>
            This sale is completed. Editing a completed sale should only be allowed if your business rules permit it.
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.update', $sale) }}" method="POST" id="saleForm">
        @csrf
        @method('PUT')

        @include('sales.partials.form', ['sale' => $sale])

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body d-flex justify-content-end gap-2">

                <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">
                    Cancel
                </a>

                @can('sales.edit', $sale)
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Sale
                    </button>
                @endcan

            </div>
        </div>
    </form>

@endsection
