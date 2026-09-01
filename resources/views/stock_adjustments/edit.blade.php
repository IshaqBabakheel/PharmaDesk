@extends('layouts.app')

@section('title', 'Edit Stock Adjustment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                <i class="fas fa-pen-to-square text-primary me-2"></i>

                Edit Stock Adjustment

            </h3>

            <p class="text-muted mb-0">

                Update adjustment
                <strong>
                    {{ $stockAdjustment->adjustment_number }}
                </strong>

            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'stock-adjustments.show',
                    $stockAdjustment
                ) }}"
                class="btn btn-info"
            >
                <i class="fas fa-eye me-1"></i>
                View
            </a>

            <a
                href="{{ route('stock-adjustments.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


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


    <form
        action="{{ route(
            'stock-adjustments.update',
            $stockAdjustment
        ) }}"
        method="POST"
        id="stockAdjustmentForm"
    >

        @csrf
        @method('PUT')


        @include(
            'stock_adjustments.partials.form',
            [
                'isEdit' => true,
                'stockAdjustment' => $stockAdjustment,
                'medicines' => $medicines,
            ]
        )

    </form>

</div>

@endsection