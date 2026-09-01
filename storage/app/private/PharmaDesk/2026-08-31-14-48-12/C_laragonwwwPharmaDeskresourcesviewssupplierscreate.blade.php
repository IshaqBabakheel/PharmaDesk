@extends('layouts.app')

@section('content')

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-truck-field text-primary me-2"></i>

                Create Supplier

            </h2>

            <p class="text-muted mb-0">

                Register a new supplier for purchasing medicines and inventory.

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

                    <li class="breadcrumb-item">

                        <a href="{{ route('suppliers.index') }}">

                            Suppliers

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Create

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">

                Supplier Information

            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('suppliers.store') }}"
                method="POST"
            >

                @include('suppliers.partials.form')

            </form>

        </div>

    </div>

@endsection