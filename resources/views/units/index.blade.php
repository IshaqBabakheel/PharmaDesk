@extends('layouts.app')

@section('title', 'Units')

@section('content')

    {{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="fas fa-weight-scale text-primary me-2"></i>
            Units
        </h2>
        <p class="text-muted mb-0">
            Manage measurement units for medicines (mg, ml, gm, etc.).
        </p>
    </div>
    <div>
        <nav>
            <ol class="breadcrumb justify-content-end mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    Master Data
                </li>
                <li class="breadcrumb-item active">
                    Units
                </li>
            </ol>
        </nav>
    </div>
</div>

    {{-- main content --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                All Your Units

            </h4>

            <a href="{{ route('units.create') }}" class="btn btn-primary">

                <i class="fas fa-plus"></i>

                Add Unit

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

                <table id="medicineTypeTable" class="table table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Name</th>

                            <th>Description</th>

                            <th>Created By</th>

                            <th>Status</th>


                            <th width="150">

                                Action

                            </th>

                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>{{ $unit->description }}</td>
                                <td>{{ $unit->creator?->name ?? '-' }}</td>
                                <td>
                                    @if ($unit->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('units.destroy', $unit) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf

                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
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
