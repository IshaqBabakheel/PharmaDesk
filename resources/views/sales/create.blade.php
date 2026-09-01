@extends('layouts.app')

@section('title', 'Create Sale')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-cart-plus text-primary me-2"></i>
                Create Sale
            </h2>

            <p class="text-muted mb-0">
                Create a new medicine sale.
            </p>
        </div>

        <div>
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
    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
        @csrf

        @include('sales.partials.form')

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body d-flex justify-content-end gap-2">

                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

                @can('sales.create')
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Save Sale
                    </button>
                @endcan

            </div>
        </div>
    </form>

@endsection
