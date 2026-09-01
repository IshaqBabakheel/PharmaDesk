@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-user-shield text-primary me-2"></i>

                Roles

            </h2>

            <p class="text-muted mb-0">

                Manage user roles and access permissions.

            </p>

        </div>

        <div>

            <nav>

                <ol class="breadcrumb justify-content-end mb-2">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home') }}">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        User Management

                    </li>

                    <li class="breadcrumb-item active">

                        Roles

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    @include('roles.partials.stats')

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            {{-- Filter buttons for showing trashed suppliers --}}
            @include('components.filter-buttons')
            {{-- create button --}}
            @can('roles.create')
                <a href="{{ route('roles.create') }}" class="btn btn-primary float-end">
                    <i class="fas fa-plus me-1"></i>
                    Add Role
                </a>
            @endcan
        </div>
    </div>

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                All Roles

            </h5>

            <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">

                <i class="fas fa-rotate"></i>

                Refresh

            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped align-middle" id="rolesTable">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Role</th>

                            <th>Permissions</th>

                            <th>Users</th>

                            <th>Guard</th>

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

            $('#rolesTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                ajax: {
                    url: "{{ route('roles.datatable') }}",
                    data: function(d) {
                        d.filter = $('.filter-btn.active').data('filter') || 'all';
                    }
                },

                columns: [

                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'permissions_count',
                        name: 'permissions_count',
                        searchable: false
                    },

                    {
                        data: 'users_count',
                        name: 'users_count',
                        searchable: false
                    },

                    {
                        data: 'guard_name',
                        name: 'guard_name'
                    },

                    {
                        data: 'created_by',
                        name: 'creator.name'
                    },

                    {
                        data: 'action',
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
                    title: 'Delete Role?',
                    text: 'The role will be moved to trash.',
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
                let name = $(form).data('name') || 'This Role';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore Role?',
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
                                toastr.success('Role restored successfully!',
                                    'Restored');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message ||
                                    'Failed to restore role.', 'Error');
                            }
                        });
                    }
                });
            });

            // Force Delete handler
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this Role';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete Role?',
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
                                toastr.success('Role permanently deleted!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message ||
                                    'Failed to permanently delete user.';
                                toastr.error(errorMessage, 'Error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
