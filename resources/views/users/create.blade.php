@extends('layouts.app')

@section('content')

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-user-plus text-primary me-2"></i>

                Create User

            </h2>

            <p class="text-muted mb-0">

                Add a new user and assign roles.

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

                        Administration

                    </li>

                    <li class="breadcrumb-item">

                        <a href="{{ route('users.index') }}">

                            Users

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

                User Information

            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('users.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @include('users.partials.form')

            </form>

        </div>

    </div>

@endsection