@extends('layouts.app')

@section('content')

{{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-lg-center mb-0">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-user text-primary me-2"></i>

                Role Details

            </h2>

            <p class="text-muted mb-0">

                Complete information about this Role.

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

                            Roles

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Details

                    </li>

                </ol>

            </nav>

        </div>

    </div>
    <div class="d-flex justify-content-end gap-2 mb-2">

    <a
        href="{{ route('roles.edit', $role) }}"
        class="btn btn-warning"
    >

        <i class="fas fa-edit me-1"></i>

        Edit

    </a>

    <a
        href="{{ route('roles.index') }}"
        class="btn btn-secondary"
    >

        Back

    </a>

</div>

<div class="row mb-4">

    <div class="col-md-4">

        <div class="card">

            <div class="card-body">

                <h6>Role Name</h6>

                <h4>

                    {{ $role->name }}

                </h4>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card">

            <div class="card-body">

                <h6>Permissions</h6>

                <h4>

                    {{ $role->permissions->count() }}

                </h4>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card">

            <div class="card-body">

                <h6>Assigned Users</h6>

                <h4>

                    {{ $role->users->count() }}

                </h4>

            </div>

        </div>

    </div>

</div>

@foreach($permissions as $module=>$modulePermissions)

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between">

        <strong>

            {{ ucwords(str_replace('-',' ',$module)) }}

        </strong>

        <span class="badge bg-primary">

            {{ $modulePermissions->count() }}

        </span>

    </div>

    <div class="card-body">

        <div class="row">

            @foreach($modulePermissions as $permission)

            <div class="col-lg-3 col-md-4 mb-2">

                <span class="badge bg-success">

                    {{ ucwords(str_replace('-',' ',explode('.',$permission->name)[1])) }}

                </span>

            </div>

            @endforeach

        </div>

    </div>

</div>

@endforeach


@endsection