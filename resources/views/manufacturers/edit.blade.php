@extends('layouts.app')

@section('title', 'Edit Manufacturers')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-2">

        <div>
            <h2 class="fw-bold mb-1 me">
                <i class="fas fa-capsules text-primary"></i>
                Manufacturers
            </h2>
        </div>

        <nav aria-label="breadcrumb" class="mt-3 mt-lg-0">
            <ol class="breadcrumb justify-content-lg-end mb-0">

                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <i class="fas fa-house me-1"></i>Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item" aria-current="page">
                    Manufacturers
                </li>

                <li class="breadcrumb-item active" aria-current="page">
                    Edit Manufacturers
                </li>

            </ol>
        </nav>
    </div>
    <p class="text-muted mb-4">
        Manage medicine types such as Prescription, Controlled Drugs, Herbal, and Supplements.
    </p>

    {{-- main content --}}
    <div class="card">

        <div class="card-header">

            <h4>

                Edit Manufacturer

            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('manufacturers.update', $manufacturer) }}" method="POST">

                @csrf

                @method('PUT')

                @include('manufacturers.form')

            </form>

        </div>

    </div>

@endsection
