{{-- @extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fas fa-user-shield text-warning me-2"></i>

            Edit Permission

        </h2>

        <p class="text-muted mb-0">

            Update permissions information.

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

                    <a href="{{ route('permissions.index') }}">

                        Permissions

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
    action="{{ route('permissions.update',$permission) }}"
    method="POST"
>

    @method('PUT')

    @include('permissions.partials.form')

</form>

@endsection --}}


@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-key text-warning me-2"></i>

                Edit Permission

            </h2>

            <p class="text-muted mb-0">

                Update permission information.

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

                        Administration

                    </li>

                    <li class="breadcrumb-item">

                        <a href="{{ route('permissions.index') }}">

                            Permissions

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Edit

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    <div class="card">

        <div class="card-header">

            <strong>

                Permission Information

            </strong>

        </div>

        <div class="card-body">

            <form
                action="{{ route('permissions.update',$permission) }}"
                method="POST"
            >

                @csrf

                @method('PUT')

                @include('permissions.partials.form')

            </form>

        </div>

    </div>

</div>

@endsection