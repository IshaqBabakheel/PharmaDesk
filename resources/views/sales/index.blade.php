@extends('layouts.app')
@push('css')
@endpush
@section('title', 'Sales')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                <i class="fas fa-cash-register text-primary me-2"></i>
                Sales
            </h2>

            <p class="text-muted mb-0">

                Manage Sales invoices.

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

                        Sales

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    @include('sales.partials.stats')

    <div class="card shadow-sm mb-4">
        {{-- Filter buttons --}}
        <div class="card-body">
            @include('components.filter-buttons')
            {{-- careate --}}
            @can('sales.create')
                <a href="{{ route('sales.create') }}" class="btn btn-primary float-end">
                    <i class="fas fa-plus me-1"></i>
                    Add Sales
                </a>
            @endcan
        </div>
    </div>

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Sales List

            </h5>

            <button class="btn btn-outline-success btn-sm" onclick="table.ajax.reload();">

                <i class="fas fa-rotate"></i>

                Refresh

            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="salesTable" class="table table-bordered table-hover align-middle w-100">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Customer</th>

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

@include('sales.partials.payment-modal')

@push('scripts')
    <script>
        $(document).on('click', '.update-payment-btn', function() {

            let id = $(this).data('id');
            let name = $(this).data('name');
            let paid = $(this).data('paid');
            let total = $(this).data('total');

            $('#paymentInvoice').val(name);
            $('#paymentGrandTotal').val(parseFloat(total).toFixed(2));
            $('#paymentAmount').val(parseFloat(paid).toFixed(2));

            $('#updatePaymentForm').attr('action', '/sales/' + id + '/payment');

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
                        let table = $('#salesTable').DataTable();
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

            let table = $('#salesTable').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('sales.datatable') }}",
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
                        data: 'sale_date'
                    },

                    {
                        data: 'customer'
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


            $(document).on('submit', '.delete-form', function(e) {

                e.preventDefault();

                let form = this;

                Swal.fire({

                    title: 'Delete Sales?',

                    text: 'The sales will be moved to trash.',

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
                let name = $(form).data('name') || 'this Sales';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore Sales?',
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
                                toastr.success('Sales restored successfully!',
                                    'Restored');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message ||
                                    'Failed to restore Sales.', 'Error');
                            }
                        });
                    }
                });
            });

            // Force Delete handler
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this Sales';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete Sales?',
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
                                toastr.success('Sales permanently deleted!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message ||
                                    'Failed to permanently delete sales.';
                                toastr.error(errorMessage, 'Error');
                            }
                        });
                    }
                });
            });


            $(document).on('submit', '.complete-form', function(e) {

                e.preventDefault();

                let form = this;
                let name = $(form).data('name') || 'this sale';

                Swal.fire({
                    title: 'Complete Sale?',
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
                let name = $(form).data('name') || 'this sale';

                Swal.fire({
                    title: 'Cancel Sale?',
                    html: `Are you sure you want to cancel <strong>${name}</strong>?<br><span class="text-danger">Stock will be restored.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel Sale',
                    confirmButtonColor: '#dc3545',
                    cancelButtonText: 'Keep Sale'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endpush
