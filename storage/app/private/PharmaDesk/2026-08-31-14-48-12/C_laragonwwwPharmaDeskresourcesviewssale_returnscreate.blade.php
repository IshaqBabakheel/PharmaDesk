@extends('layouts.app')

@section('title', 'Create Sale Return')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="fas fa-rotate-left text-primary"></i>
                    Create Sale Return
                </h3>
                <p class="text-muted mb-0">
                    Create a return against a completed sale.
                </p>
            </div>
            <div>
                <nav>
                    <ol class="breadcrumb justify-content-end mb-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('sale-returns.index') }}">Sale Returns</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
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

        @include('sale_returns.partials.form', [
            'formAction' => route('sale-returns.store'),
            'formMethod' => null,
            'isEdit' => false,
            'saleReturn' => null,
            'saleItems' => $saleItems ?? [],
            'selectedSale' => $selectedSale ?? null,
            'lockedSale' => $lockedSale ?? false,
        ])
    </div>
@endsection

@push('scripts')
    @include('sale_returns.partials.javascript')
@endpush