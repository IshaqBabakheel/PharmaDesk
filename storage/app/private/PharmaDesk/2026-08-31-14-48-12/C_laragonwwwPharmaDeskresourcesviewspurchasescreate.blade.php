@extends('layouts.app')
@push('css')
    <style>
        #purchaseItemsTable input{
            min-width:90px;
        }

        #purchaseItemsTable .batch{
            min-width:90px;
        }

        #purchaseItemsTable .medicine-select{
            min-width:150px;
        }
    </style>
@endpush
@section('title','Create Purchase')

@section('content')

<div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fas fa-cart-plus text-primary me-2"></i>

            Create Purchase

        </h2>

        <p class="text-muted mb-0">

            Record a new purchase from supplier.

        </p>

    </div>

    <div>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb justify-content-end mb-2">

                <li class="breadcrumb-item">

                    <a href="{{ route('home') }}">

                        <i class="fas fa-house me-1"></i>

                        Dashboard

                    </a>

                </li>

                <li class="breadcrumb-item">

                    Purchases

                </li>

                <li class="breadcrumb-item active">

                    Create Purchase

                </li>

            </ol>

        </nav>

    </div>

</div>

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">

    <div class="card-body">

        <form
            action="{{ route('purchases.store') }}"
            method="POST"
        >

            @include('purchases.partials.form')

        </form>

    </div>

</div>
</div>

@endsection