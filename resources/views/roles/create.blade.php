@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fas fa-user-shield text-primary me-2"></i>

            Create Role

        </h2>

        <p class="text-muted mb-0">

            Create a new system role and assign permissions.

        </p>

    </div>

    <div>

        <nav>

            <ol class="breadcrumb justify-content-end mb-0">

                <li class="breadcrumb-item">

                    <a href="{{ route('home') }}">

                        Dashboard

                    </a>

                </li>

                <li class="breadcrumb-item">

                    User Management

                </li>

                <li class="breadcrumb-item">

                    <a href="{{ route('roles.index') }}">

                        Roles

                    </a>

                </li>

                <li class="breadcrumb-item active">

                    Create

                </li>

            </ol>

        </nav>

    </div>

</div>

<form
    action="{{ route('roles.store') }}"
    method="POST"
>

    @include('roles.partials.form')

</form>

@endsection