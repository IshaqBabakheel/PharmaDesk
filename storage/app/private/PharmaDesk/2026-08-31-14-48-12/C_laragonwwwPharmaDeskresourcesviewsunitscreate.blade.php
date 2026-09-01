@extends('layouts.app')

@section('content')
{{-- Page Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-2">

        <div>
            <h2 class="fw-bold mb-1 me">
                <i class="fas fa-capsules text-primary"></i>
                Units
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
                    Units
                </li>

                <li class="breadcrumb-item active" aria-current="page">
                    Create Units
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
        <h4>Add Unit</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('units.store') }}" method="POST">

            @csrf

            @include('units.form')

        </form>

    </div>

</div>

@endsection