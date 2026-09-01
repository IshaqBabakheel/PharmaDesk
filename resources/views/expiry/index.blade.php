@extends('layouts.app')

@section('title', 'Expiry Management')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">

                <i class="fas fa-calendar-xmark text-danger me-2"></i>

                Expiry Management

            </h3>

            <p class="text-muted mb-0">

                Monitor medicines by batch expiry date.

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

                        Expiry Management

                    </li>

                </ol>

            </nav>

        </div>

    </div>


    {{-- Success --}}
    @if (session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-circle-check me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error --}}
    @if (session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-circle-exclamation me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Statistics --}}
    @include('expiry.partials.stats')


    {{-- Filters --}}
    @include('expiry.partials.filters')


    {{-- Expiry Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="fas fa-list me-2 text-primary"></i>

                Expiry Records

            </h5>

        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle w-100" id="expiryTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Expiry Date</th>
                            <th>Available</th>
                            <th>Days Remaining</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection


@push('scripts')
    @include('expiry.javascript')
@endpush