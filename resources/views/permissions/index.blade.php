{{-- @extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-user-shield text-primary me-2"></i>

                Permissions

            </h2>

            <p class="text-muted mb-0">

                Manage system permissions and access control.

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

                        Permissions

                    </li>

                </ol>

            </nav>


        </div>


    </div>


    @include('permissions.partials.stats')


    <div class="card shadow-sm">


        <div class="card-header d-flex justify-content-between align-items-center">


            <h5 class="mb-0">

                All Permissions

            </h5>



            @can('permissions.create')
                <a href="{{ route('permissions.create') }}" class="btn btn-primary">


                    <i class="fas fa-plus me-1"></i>

                    Add Permission


                </a>
            @endcan


        </div>



        <div class="card-body">


            <div class="table-responsive">


                <table class="table table-striped align-middle" id="permissionsTable">


                    <thead>


                        <tr>

                            <th>#</th>

                            <th>Module</th>

                            <th>Action</th>

                            <th>Guard</th>

                            <th>Roles</th>

                            <th width="150">
                                Actions
                            </th>

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


            $('#permissionsTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,


                ajax: "{{ route('permissions.datatable') }}",


                columns: [


                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },


                    {
                        data: 'module',
                        name: 'name'
                    },


                    {
                        data: 'permission_action',
                        name: 'name'
                    },


                    {
                        data: 'guard_name',
                        name: 'guard_name'
                    },


                    {
                        data: 'roles_count',
                        name: 'roles_count',
                        searchable: false
                    },


                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }


                ]


            });



        });





        $(document).on('submit', '.delete-form', function(e) {


            e.preventDefault();


            let form = this;



            Swal.fire({

                title: 'Delete Permission?',

                text: 'This action cannot be undone.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#dc3545',

                confirmButtonText: 'Delete'


            }).then((result) => {


                if (result.isConfirmed) {

                    form.submit();

                }


            });



        });
    </script>
@endpush --}}


@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}

        <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">

                    <i class="fas fa-key text-primary me-2"></i>

                    Permissions

                </h2>

                <p class="text-muted mb-0">

                    Manage application permissions.

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

                            Administration

                        </li>

                        <li class="breadcrumb-item active">

                            Permissions

                        </li>

                    </ol>

                </nav>

            </div>

        </div>

        @include('permissions.partials.stats')

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                {{-- Filter buttons --}}
                @include('components.filter-buttons')
                {{-- create button --}}
                @can('permissions.create')
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus me-1"></i>
                        Add Permission
                    </a>
                @endcan
            </div>
        </div>

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    Permission List

                </h5>

                <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">
                    <i class="fas fa-rotate"></i>
                    Refresh
                </button>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="permissionTable" class="table table-striped table-hover align-middle w-100">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Module</th>

                                <th>Permission</th>

                                <th>Guard</th>

                                <th>Roles</th>

                                <th id="userColumnHeader">Created By</th>

                                <th width="120">Actions</th>

                            </tr>

                        </thead>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            let table = $('#permissionTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                autoWidth: false,

                ajax: {
                    url: "{{ route('permissions.datatable') }}",
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

                        data: 'module',

                        name: 'name'

                    },

                    {

                        data: 'permission_action',

                        name: 'name'

                    },

                    {

                        data: 'guard_name',

                        name: 'guard_name'

                    },

                    {

                        data: 'roles_count',

                        name: 'roles_count',

                        searchable: false,

                        orderable: false

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
                    title: 'Delete Permission?',
                    text: 'The permission will be moved to trash.',
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
                let name = $(form).data('name') || 'This Permission';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore Permission?',
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
                                toastr.success('Permission restored successfully!', 'Restored');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed to restore permission.', 'Error');
                            }
                        });
                    }
                });
            });

            // Force Delete handler
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this Permission';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete Permission?',
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
                                toastr.success('Permission permanently deleted!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message || 'Failed to permanently delete permissin.';
                                toastr.error(errorMessage, 'Error');
                            }
                        });
                    }
                });
            });


        });
    </script>
@endpush
