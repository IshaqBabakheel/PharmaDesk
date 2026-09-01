@extends('layouts.app')

@section('title', 'Edit Purchase')
@push('css')
    <style>
        #purchaseItemsTable input {
            min-width: 90px;
        }

        #purchaseItemsTable .batch {
            min-width: 90px;
        }

        #purchaseItemsTable .medicine-select {
            min-width: 150px;
        }
    </style>
@endpush
@section('content')

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-pen-to-square text-warning me-2"></i>

                Edit Purchase

            </h2>

            <p class="text-muted mb-0">

                Update purchase information.

            </p>

        </div>

        <div>

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb justify-content-end mb-2">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home') }}">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        Purchases

                    </li>

                    <li class="breadcrumb-item active">

                        Edit

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        <div class="card-body">

            <form action="{{ route('purchases.update', $purchase) }}" method="POST">
                @method('PUT')
                @include('purchases.partials.form')
            </form>

        </div>

    </div>
@endsection
