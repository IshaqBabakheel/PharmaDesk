@extends('layouts.app')


@section('content')
    <div class="container-fluid">
        <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    <i class="fas fa-users text-primary me-2"></i>
                    Customers
                </h2>

                <p class="text-muted mb-0">

                    Manage Customers.

                </p>

            </div>

            <div>

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb justify-content-end mb-2">

                        <li class="breadcrumb-item">

                            <a href="{{ route('home') }}">

                                Dashboard

                            </a>

                        </li>

                        <li class="breadcrumb-item">

                            Customers

                        </li>

                    </ol>

                </nav>

            </div>

        </div>

        @include('customers.partials.stats')

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                {{-- Filter buttons --}}
                @include('components.filter-buttons')
                {{-- create button --}}
                @can('customers.create')
                    <a href="{{ route('customers.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus me-1"></i>
                        Add Customer
                    </a>
                @endcan
            </div>
        </div>

        <div class="card border-0 shadow-sm">


            <div class="card-header d-flex justify-content-between">


                <h5>
                    Customers
                </h5>



                <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">

                    <i class="fas fa-rotate"></i>

                    Refresh

                </button>


            </div>




            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="customersTable">


                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Name</th>

                                <th>Phone</th>

                                <th>Type</th>

                                <th>Total Sales</th>

                                <th>Due</th>

                                {{-- Dynamic column header that changes based on filter --}}
                                <th id="userColumnHeader">Created By</th>

                                <th>Action</th>

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

            let table = $('#customersTable').DataTable({

                processing: true,

                serverSide: true,

                ajax: {
                    url: "{{ route('customers.datatable') }}",
                    data: function(d) {
                        d.filter = $('.filter-btn.active').data('filter') || 'all';
                    }
                },

                order: [
                    [2, 'asc']
                ],

                columns: [


                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false
                    },


                    {
                        data: 'name',
                        name: 'name'
                    },


                    {
                        data: 'phone',
                        name: 'phone'
                    },



                    {
                        data: 'type',
                        name: 'type'
                    },


                    {
                        data: 'sales',
                        name: 'sales'
                    },


                    {
                        data: 'due',
                        name: 'due'
                    },



                    {
                        data: 'created_by',
                        name: 'creator.name'
                    },


                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    }


                ],


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
                    title: 'Delete Customer?',
                    text: 'The customer will be moved to trash.',
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
                let name = $(form).data('name') || 'This Customer';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore Customer?',
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
                                toastr.success('Customer restored successfully!', 'Restored');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed to restore customer.', 'Error');
                            }
                        });
                    }
                });
            });

            // Force Delete handler
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this Customer';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete Customer?',
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
                                toastr.success('Customer permanently deleted!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message || 'Failed to permanently delete customer.';
                                toastr.error(errorMessage, 'Error');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
