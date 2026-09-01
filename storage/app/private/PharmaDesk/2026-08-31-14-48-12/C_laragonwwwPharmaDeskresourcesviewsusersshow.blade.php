@extends('layouts.app')

@section('content')

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-user text-primary me-2"></i>

                User Details

            </h2>

            <p class="text-muted mb-0">

                Complete information about this user.

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

                        Details

                    </li>

                </ol>

            </nav>

        </div>

    </div>


    <div class="row">

        {{-- Left Side --}}
        <div class="col-lg-4">

            <div class="card shadow-sm">

                <div class="card-body text-center">

                    @if($user->profile_photo)

                        <img
                            src="{{ asset('storage/'.$user->profile_photo) }}"
                            class="rounded-circle border shadow-sm mb-3"
                            width="150"
                            height="150"
                            style="object-fit:cover;"
                        >

                    @else

                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff&size=150"
                            class="rounded-circle border shadow-sm mb-3"
                        >

                    @endif


                    <h4 class="fw-bold">

                        {{ $user->name }}

                    </h4>

                    <p class="text-muted">

                        {{ $user->email }}

                    </p>


                    @if($user->status)

                        <span class="badge bg-success">

                            Active

                        </span>

                    @else

                        <span class="badge bg-danger">

                            Inactive

                        </span>

                    @endif

                    <hr>

                    <h6 class="fw-bold">

                        Assigned Roles

                    </h6>

                    @forelse($user->roles as $role)

                        <span class="badge bg-primary me-1 mb-1">

                            {{ $role->name }}

                        </span>

                    @empty

                        <span class="text-muted">

                            No Role Assigned

                        </span>

                    @endforelse

                </div>

            </div>

        </div>



        {{-- Right Side --}}
        <div class="col-lg-8">

            {{-- Personal Information --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <strong>

                        Personal Information

                    </strong>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <strong>Name</strong>

                            <p>{{ $user->name }}</p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Email</strong>

                            <p>{{ $user->email }}</p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Phone</strong>

                            <p>{{ $user->phone ?: '-' }}</p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Gender</strong>

                            <p>{{ $user->gender ?: '-' }}</p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Date of Birth</strong>

                            <p>{{ $user->date_of_birth ?: '-' }}</p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>City</strong>

                            <p>{{ $user->city ?: '-' }}</p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Country</strong>

                            <p>{{ $user->country ?: '-' }}</p>

                        </div>

                        <div class="col-md-12">

                            <strong>Address</strong>

                            <p>{{ $user->address ?: '-' }}</p>

                        </div>

                    </div>

                </div>

            </div>



            {{-- Audit Information --}}
            <div class="card shadow-sm">

                <div class="card-header">

                    <strong>

                        Audit Information

                    </strong>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <strong>Created By</strong>

                            <p>

                                {{ optional($user->creator)->name ?? '-' }}

                            </p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Created At</strong>

                            <p>

                                {{ $user->created_at?->format('d M Y h:i A') }}

                            </p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Updated By</strong>

                            <p>

                                {{ optional($user->updater)->name ?? '-' }}

                            </p>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Updated At</strong>

                            <p>

                                {{ $user->updated_at?->format('d M Y h:i A') }}

                            </p>

                        </div>

                        <div class="col-md-6">

                            <strong>Deleted By</strong>

                            <p>

                                {{ optional($user->deleter)->name ?? '-' }}

                            </p>

                        </div>

                        <div class="col-md-6">

                            <strong>Deleted At</strong>

                            <p>

                                {{ $user->deleted_at?->format('d M Y h:i A') ?? '-' }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">

    <a
        href="{{ route('users.edit', $user) }}"
        class="btn btn-warning"
    >

        <i class="fas fa-edit me-1"></i>

        Edit

    </a>

    <a
        href="{{ route('users.index') }}"
        class="btn btn-secondary"
    >

        Back

    </a>

</div>

@endsection