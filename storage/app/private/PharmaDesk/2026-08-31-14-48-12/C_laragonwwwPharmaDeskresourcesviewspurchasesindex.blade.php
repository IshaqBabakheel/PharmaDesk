@extends('layouts.app')

@section('title', 'Purchases')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                <i class="fas fa-shopping-cart text-primary me-2"></i>
                Purchases
            </h2>

            <p class="text-muted mb-0">

                Manage purchase invoices.

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

                        Purchases

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    {{-- Statistics --}}
    @include('purchases.partials.stats')

    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            {{-- Filter buttons for showing trashed suppliers --}}
            @include('components.filter-buttons')
            {{-- create button --}}
            @can('purchases.create')
                <a href="{{ route('purchases.create') }}" class="btn btn-primary float-end">
                    <i class="fas fa-plus me-1"></i>
                    Add Purchase
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Purchase List

            </h5>

            <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">

                <i class="fas fa-rotate"></i>

                Refresh

            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="purchaseTable" class="table table-bordered table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Purchase No</th>

                            <th>Date</th>

                            <th>Supplier</th>

                            <th>Invoice</th>

                            <th>Total</th>

                            <th>Payment</th>

                            <th>Status</th>

                            {{-- Dynamic column header that changes based on filter --}}
                            <th id="userColumnHeader">Created By</th>

                            <th width="100">

                                Actions

                            </th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

@endsection

@include('purchases.partials.payment-modal')

@push('scripts')
    <script>
        $(document).on('click', '.update-payment-btn', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let paid = $(this).data('paid');
            let total = $(this).data('total');
            $('#paymentPurchase').val(name);
            $('#paymentGrandTotal').val(parseFloat(total).toFixed(2));
            $('#paymentAmount').val(parseFloat(paid).toFixed(2));
            $('#updatePaymentForm').attr('action', '/purchases/' + id + '/payment');

            $('#updatePaymentModal').modal('show');

        });

        $(document).on('submit', '#updatePaymentForm', function(e) {
            e.preventDefault();
            
            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize();
            
            // Show loading state
            Swal.fire({
                title: 'Updating Payment...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: url,
                type: 'POST', // Use POST (Laravel will handle PATCH via _method)
                data: formData,
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        toastr.success('Payment updated successfully!');
                        $('#updatePaymentModal').modal('hide');
                        
                        // Get the DataTable instance by ID
                        let table = $('#purchaseTable').DataTable();
                        if (table) {
                            table.ajax.reload();
                        }
                        
                    } else {
                        toastr.error(response.message || 'Failed to update payment');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMessages = Object.values(errors).flat().join('\n');
                            toastr.error(errorMessages);
                        }
                    } else if (xhr.status === 403) {
                        toastr.error('You do not have permission to update payment.');
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'Failed to update payment.');
                    }
                }
            });
        });

        $(function() {

            let table = $('#purchaseTable').DataTable({

                processing: true,

                serverSide: true,

                ajax: {
                    url: "{{ route('purchases.datatable') }}",
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
                        data: 'purchase_number'
                    },

                    {
                        data: 'purchase_date'
                    },

                    {
                        data: 'supplier'
                    },

                    {
                        data: 'invoice_number'
                    },

                    {
                        data: 'grand_total'
                    },

                    {
                        data: 'payment_status'
                    },

                    {
                        data: 'status'
                    },

                    {
                        data: 'created_by',
                        name: 'creator.name'
                    },

                    {
                        data: 'action',
                        searchable: false,
                        orderable: false
                    },

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

                    title: 'Delete Purchase?',

                    text: 'The purchase will be moved to trash.',

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
                let name = $(form).data('name') || 'this Purchase';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore Purchase?',
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
                                toastr.success('Purchase restored successfully!', 'Restored');
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
                let name = $(form).data('name') || 'this purchase';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete Purchase?',
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
                                toastr.success('Purchase permanently deleted!', 'Deleted');
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

            $(document).on('submit', '.complete-form', function(e) {

                e.preventDefault();

                let form = this;
                let name = $(form).data('name') || 'this Purchase';

                Swal.fire({
                    title: 'Complete Purchase?',
                    html: `Are you sure you want to complete <strong>${name}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Complete',
                    confirmButtonColor: '#198754',
                    cancelButtonText: 'Cancel'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

            $(document).on('submit', '.cancel-form', function(e) {

                e.preventDefault();

                let form = this;
                let name = $(form).data('name') || 'this Purchase';

                Swal.fire({
                    title: 'Cancel Purchase?',
                    html: `Are you sure you want to cancel <strong>${name}</strong>?<br><span class="text-danger">Stock will be restored.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel Purchase',
                    confirmButtonColor: '#dc3545',
                    cancelButtonText: 'Keep Purchase'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endpush
