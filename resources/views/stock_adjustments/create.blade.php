@extends('layouts.app')

@section('title', 'Create Stock Adjustment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-sliders text-primary me-2"></i>
                Create Stock Adjustment
            </h3>

            <p class="text-muted mb-0">
                Adjust physical stock quantities.
            </p>

        </div>

        <div>

            <a
                href="{{ route('stock-adjustments.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- General Error --}}
    @if (session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <form
        action="{{ route('stock-adjustments.store') }}"
        method="POST"
        id="stockAdjustmentForm"
    >

        @csrf

        @include(
            'stock_adjustments.partials.form',
            [
                'isEdit' => false,
                'stockAdjustment' => null,
                'medicines' => $medicines,
            ]
        )

    </form>

</div>

@endsection