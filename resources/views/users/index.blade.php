@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-users text-primary me-2"></i>
                Users
            </h2>

            <p class="text-muted mb-0">
                Manage system users, assign roles, and control access.
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

                    <li class="breadcrumb-item active">

                        Users

                    </li>

                </ol>

            </nav>

        </div>

    </div>


    @include('users.partials.stats')

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            {{-- Filter buttons for showing trashed suppliers --}}
            @include('components.filter-buttons')
            {{-- create button --}}
            @can('users.create')
                <a href="{{ route('users.create') }}" class="btn btn-primary float-end">
                    <i class="fas fa-plus me-1"></i>
                    Add User
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                All Users

            </h5>

            <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">
                <i class="fas fa-rotate"></i>
                Refresh
            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle" id="usersTable" width="100%">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Photo</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th>Phone</th>

                            <th>Roles</th>

                            <th>Status</th>

                            {{-- Dynamic column header that changes based on filter --}}
                            <th id="userColumnHeader">Created By</th>

                            <th>Actions</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(function() {

            let table = $('#usersTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                autoWidth: false,

                ajax: {
                    url: "{{ route('users.datatable') }}",
                    data: function(d) {
                        d.filter = $('.filter-btn.active').data('filter') || 'all';
                    }
                },

                columns: [

                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },

                    {
                        data: 'photo',
                        name: 'photo',
                        searchable: false,
                        orderable: false
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'phone',
                        name: 'phone'
                    },

                    {
                        data: 'roles',
                        name: 'roles',
                        searchable: false,
                        orderable: false
                    },

                    {
                        data: 'status',
                        name: 'status'
                    },

                    {
                        data: 'created_by',
                        name: 'creator.name'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }

                ]

            });


            // Filter buttons click handler
            $('.filter-btn').click(function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                
                let filter = $(this).data('filter');
                let columnHeader = $('#userColumnHeader');
                
                if (filter === 'trashed') {
                    columnHeader.text('Deleted By');
                } else {
                    columnHeader.text('Created By');
                }
                table.ajax.reload();
            });

            // delete handler
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                let form = this;

                Swal.fire({
                    title: 'Delete User?',
                    text: 'The user will be moved to trash.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Restore handler
            $(document).on('submit', '.restore-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'This User';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore User?',
                    html: `Are you sure you want to restore <strong>${name}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Restore',
                    confirmButtonColor: '#28a745',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: actionUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'PATCH'
                            },
                            success: function(response) {
                                // Show success toast
                                toastr.success('User restored successfully!', 'Restored');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed to restore user.', 'Error');
                            }
                        });
                    }
                });
            });

            // Force Delete handler
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this User';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete User?',
                    html: `Are you sure you want to permanently delete <strong>${name}</strong>?<br><span class="text-danger"><strong>This action cannot be undone!</strong></span>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Permanently Delete',
                    confirmButtonColor: '#dc3545',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: actionUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                // Show success toast
                                toastr.success('User permanently deleted!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message || 'Failed to permanently delete user.';
                                toastr.error(errorMessage, 'Error');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
