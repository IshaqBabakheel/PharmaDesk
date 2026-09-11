@extends('layouts.app')

@section('title', 'Add Medicine Category')

@section('content')

<div class="container-fluid">
    {{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="fas fa-plus-circle text-success me-2"></i>
            Create New Category
        </h2>
        <p class="text-muted mb-0">
            Add a new medicine category to the system.
        </p>
    </div>
    <div>
        <nav>
            <ol class="breadcrumb justify-content-end mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('medicine-categories.index') }}">Categories</a>
                </li>
                <li class="breadcrumb-item active">
                    Create
                </li>
            </ol>
        </nav>
    </div>
</div>

    {{-- main content --}}
    <div class="card">

        <div class="card-header">
            <h4>Add Medicine Category</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('medicine-categories.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input
                        type="text"
                        name="name"
                        placeholder="Enter Medicine Category Name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                    >

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        placeholder="Optional description"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="row">

                    <div class="col-md-6">

                        <label>Status</label>

                        <select
                            name="status"
                            class="form-select"
                        >
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>

                    </div>

                    <div class="col-md-6">

                        <label>Sort Order</label>

                        <input
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order',0) }}"
                            class="form-control"
                        >

                    </div>

                </div>

                <hr>

                <button class="btn btn-primary">
                    Save Category
                </button>

                <a
                    href="{{ route('medicine-categories.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection