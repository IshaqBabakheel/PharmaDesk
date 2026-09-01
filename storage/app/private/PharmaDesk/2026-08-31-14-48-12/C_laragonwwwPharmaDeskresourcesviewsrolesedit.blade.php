@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fas fa-user-shield text-warning me-2"></i>

            Edit Role

        </h2>

        <p class="text-muted mb-0">

            Update role information and permissions.

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

                    Edit

                </li>

            </ol>

        </nav>

    </div>

</div>

<form
    action="{{ route('roles.update',$role) }}"
    method="POST"
>

    @method('PUT')

    @include('roles.partials.form')

</form>

@endsection