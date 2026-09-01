@extends('layouts.app')

@section('title', 'Manufacturer')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-2">

        <div>
            <h2 class="fw-bold mb-1 me">
                <i class="fas fa-industry text-primary"></i>
                Manufacturers
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
                    Manufacturers
                </li>

            </ol>
        </nav>
    </div>
    <p class="text-muted mb-4">
        Manage medicine types such as Prescription, Controlled Drugs, Herbal, and Supplements.
    </p>

    {{-- main content --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                All Your Manufacturers

            </h4>

            <a href="{{ route('manufacturers.create') }}" class="btn btn-primary">

                <i class="fas fa-plus"></i>

                Add Manufacturer

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

                            <th>Company</th>

                            <th>Contact Person</th>

                            <th>Phone</th>


                            <th>City</th>

                            
                            <th>Status</th>



                            <th width="150">

                                Action

                            </th>

                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($manufacturers as $manufacturer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $manufacturer->name }}</td>
                                <td>{{ $manufacturer->contact_person }}</td>
                                <td>{{ $manufacturer->phone }}</td>
                                <td>{{ $manufacturer->city }}</td>
                                <td>
                                    @if ($manufacturer->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('manufacturers.edit', $manufacturer) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('manufacturers.destroy', $manufacturer) }}" method="POST"
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
