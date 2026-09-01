@extends('layouts.app')
@push('css')
<style>
    /* Active state for filter buttons */
.btn-group .filter-btn.active {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

.btn-group .filter-btn.active.btn-outline-success {
    background-color: #198754;
    color: #fff;
    border-color: #198754;
}

.btn-group .filter-btn.active.btn-outline-warning {
    background-color: #ffc107;
    color: #000;
    border-color: #ffc107;
}
</style>
@endpush
@section('content')
    {{-- ============================================== --}}
    {{-- Page Header --}}
    {{-- ============================================== --}}

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-truck-field text-primary me-2"></i>

                Suppliers

            </h2>

            <p class="text-muted mb-0">

                Manage supplier companies, contact information and purchasing partners.

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

                        Purchases

                    </li>

                    <li class="breadcrumb-item active">

                        Suppliers

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    @include('suppliers.partials.stats')

    {{-- Filter buttons for showing trashed suppliers --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">
                    <i class="fas fa-list me-1"></i> All
                </button>
                <button type="button" class="btn btn-outline-success filter-btn" data-filter="active">
                    <i class="fas fa-check-circle me-1"></i> Active
                </button>
                <button type="button" class="btn btn-outline-warning filter-btn" data-filter="trashed">
                    <i class="fas fa-trash me-1"></i> Trashed
                </button>
            </div>
            
            @can('suppliers.create')
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary float-end">
                    <i class="fas fa-plus me-1"></i>
                    Add Supplier
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Suppliers List

            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="suppliersTable" class="table table-bordered table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Code</th>

                            <th>Name</th>

                            <th>Contact Person</th>

                            <th>Phone</th>

                            <th>City</th>

                            <th>Opening Balance</th>

                            <th>Status</th>

                            {{-- Dynamic column header that changes based on filter --}}
                            <th id="userColumnHeader">Created By</th>

                            <th width="120">

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

            let table = $('#suppliersTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                autoWidth: false,

                ajax: {
                    url: "{{ route('suppliers.datatable') }}",
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
                        searchable: false,
                        orderable: false
                    },

                    {
                        data: 'supplier_code',
                        name: 'supplier_code'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'contact_person',
                        name: 'contact_person'
                    },

                    {
                        data: 'phone',
                        name: 'phone'
                    },

                    {
                        data: 'city',
                        name: 'city'
                    },

                    {
                        data: 'opening_balance',
                        name: 'opening_balance'
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

            // Delete handler (Soft Delete)
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this supplier';

                Swal.fire({
                    title: 'Delete Supplier?',
                    html: `Are you sure you want to delete <strong>${name}</strong>?<br><span class="text-muted">The supplier will be moved to trash.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#dc3545',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                // Show success toast
                                toastr.success('Supplier moved to trash successfully!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed to delete supplier.', 'Error');
                            }
                        });
                    }
                });
            });

            // Restore handler
            $(document).on('submit', '.restore-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this supplier';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore Supplier?',
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
                                toastr.success('Supplier restored successfully!', 'Restored');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed to restore supplier.', 'Error');
                            }
                        });
                    }
                });
            });

            // Force Delete handler
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this supplier';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete Supplier?',
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
                                toastr.success('Supplier permanently deleted!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message || 'Failed to permanently delete supplier.';
                                toastr.error(errorMessage, 'Error');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush