@extends('layouts.app')

@section('title', 'Edit Medicine Category')

@section('content')

    <div class="container-fluid">
        {{-- Page Header --}}
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-2">

            <div>
                <h2 class="fw-bold mb-1 me">
                    <i class="fas fa-capsules text-primary"></i>
                    Medicine Categories
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
                        Categories
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Categories
                    </li>

                </ol>
            </nav>
        </div>
        <p class="text-muted mb-4">
            Manage medicine categories such as Capsules, Syrups, Injections, and Creams.
        </p>

        {{-- main content --}}
        <div class="card">

            <div class="card-header">
                <h4>Edit Medicine Category</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('medicine-categories.update', $medicineCategory) }}" method="POST">

                    @csrf

                    @method('PUT')

                    <div class="mb-3">

                        <label>Name</label>

                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $medicineCategory->name) }}">

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Description</label>

                        <textarea name="description" class="form-control" rows="4">{{ old('description', $medicineCategory->description) }}</textarea>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <label>Status</label>

                            <select name="status" class="form-select">

                                <option value="1" @selected(old('status', $medicineCategory->status) == 1)>
                                    Active
                                </option>

                                <option value="0" @selected(old('status', $medicineCategory->status) == 0)>
                                    Inactive
                                </option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label>Sort Order</label>

                            <input type="number" name="sort_order"
                                value="{{ old('sort_order', $medicineCategory->sort_order) }}" class="form-control">

                        </div>

                    </div>

                    <hr>

                    <button class="btn btn-primary">
                        Update Category
                    </button>

                    <a href="{{ route('medicine-categories.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

@endsection
