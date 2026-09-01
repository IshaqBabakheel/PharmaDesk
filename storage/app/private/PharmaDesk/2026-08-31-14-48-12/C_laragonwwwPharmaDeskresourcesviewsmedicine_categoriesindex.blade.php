@extends('layouts.app')

@section('title', 'Medicine Categories')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-2">

        <div>
            <h2 class="fw-bold mb-1 me">
                <i class="fas fa-tags text-primary"></i>
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

                <li class="breadcrumb-item active" aria-current="page">
                    Medicine Categories
                </li>

            </ol>
        </nav>
    </div>
    <p class="text-muted mb-4">
        Manage medicine categories such as Capsules, Syrups, Injections, and Creams.
    </p>

    {{-- main content --}}
    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">All your Medicine Categories</h4>
            </div>

            <a href="{{ route('medicine-categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Category
            </a>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th>Name</th>

                            <th>Description</th>

                            <th width="100">Order</th>

                            <th width="150">Created By</th>

                            <th width="150">Updated By</th>

                            <th width="100">Status</th>

                            <th width="150" class="text-center">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($medicine_categories as $cat)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $cat->name }}</strong>
                                </td>

                                <td>
                                    {{ $cat->description ?: '-' }}
                                </td>

                                <td>
                                    {{ $cat->sort_order }}
                                </td>

                                <td>
                                    {{ $cat->creator?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $cat->updater?->name ?? '-' }}
                                </td>

                                <td>

                                    @if ($cat->status)
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td class="text-center">

                                    <a href="{{ route('medicine-categories.edit', $cat) }}" class="btn btn-sm btn-warning"
                                        title="Edit">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    <form action="{{ route('medicine-categories.destroy', $cat) }}" method="POST"
                                        class="d-inline delete-form"
                                        >

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center text-muted py-4">

                                    No medicine categories found.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('submit', '.delete-form', function(e) {

            e.preventDefault();

            let form = this;

            Swal.fire({

                title: 'Delete Record?',

                text: 'This record will be moved to trash.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                confirmButtonText: 'Delete'

            }).then((result) => {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });
    </script>
@endpush
