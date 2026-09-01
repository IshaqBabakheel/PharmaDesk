@extends('layouts.app')

@section('title', 'Purchase Returns')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    <i class="fas fa-undo-alt text-primary me-2"></i>
                    Purchase Returns
                </h2>

                <p class="text-muted mb-0">

                    Manage all purchase return invoices.

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

                        <li class="breadcrumb-item active">

                            Purchase Returns

                        </li>

                    </ol>

                </nav>

            </div>



        </div>

        {{-- Statistics --}}
        @include('purchase_returns.partials.stats')

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                {{-- Filter buttons for showing trashed suppliers --}}
                @include('components.filter-buttons')
                {{-- create button --}}
                @can('purchase-returns.create')
                    <a href="{{ route('purchase-returns.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus me-1"></i>
                        Add Purchase Return
                    </a>
                @endcan
            </div>
        </div>

        {{-- Listing Card --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        Purchase Return List

                    </h5>

                    <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">

                        <i class="fas fa-rotate"></i>

                        Refresh

                    </button>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="purchaseReturnTable" class="table table-bordered table-hover align-middle w-100">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Return No</th>

                                <th>Purchase No</th>

                                <th>Supplier</th>

                                <th>Date</th>

                                <th>Grand Total</th>

                                <th>Status</th>

                                {{-- Dynamic column header that changes based on filter --}}
                                <th id="userColumnHeader">Created By</th>

                                <th width="140">

                                    Action

                                </th>

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
        let table = $('#purchaseReturnTable').DataTable({

            processing: true,

            serverSide: true,

            responsive: true,

            autoWidth: false,

            ajax: {
                url: "{{ route('purchase-returns.datatable') }}",
                data: function(d) {
                    d.filter = $('.filter-btn.active').data('filter') || 'all';
                }
            },

            order: [
                [1, 'desc']
            ],

            columns: [

                {
                    data: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'return_number',
                    name: 'return_number'
                },

                {
                    data: 'purchase_number',
                    name: 'purchase.purchase_number'
                },

                {
                    data: 'supplier',
                    name: 'supplier.name'
                },

                {
                    data: 'return_date',
                    name: 'return_date'
                },

                {
                    data: 'grand_total',
                    name: 'grand_total',
                    className: 'text-end'
                },

                {
                    data: 'status',
                    name: 'status',
                    searchable: false
                },

                {
                    data: 'created_by',
                    name: 'creator.name'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
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
                title: 'Delete Purchase Return?',
                text: 'The purchase return will be moved to trash.',
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
            let name = $(form).data('name') || 'This Purchase Return';
            let actionUrl = $(form).attr('action');

            Swal.fire({
                title: 'Restore Purchase Return?',
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
                            toastr.success('Purchase Return restored successfully!', 'Restored');
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message ||
                                'Failed to restore purchase return.', 'Error');
                        }
                    });
                }
            });
        });

        // Force Delete handler
        $(document).on('submit', '.force-delete-form', function(e) {
            e.preventDefault();
            let form = this;
            let name = $(form).data('name') || 'this Purchase Return';
            let actionUrl = $(form).attr('action');

            Swal.fire({
                title: 'Permanently Delete Purchase Return?',
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
                            toastr.success('Purchase Return permanently deleted!', 'Deleted');
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            let errorMessage = xhr.responseJSON?.message ||
                                'Failed to permanently delete purchase return.';
                            toastr.error(errorMessage, 'Error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
