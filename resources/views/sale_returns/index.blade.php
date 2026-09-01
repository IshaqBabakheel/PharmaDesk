@extends('layouts.app')

@section('title', 'Sale Returns')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">

                    <i class="fas fa-rotate-left text-primary"></i>

                    Sale Returns

                </h3>

                <p class="text-muted mb-0">

                    Manage all sale return invoices.

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

                            Sale Returns

                        </li>

                    </ol>

                </nav>

            </div>

        </div>


        {{-- Statistics --}}
        @include('sale_returns.partials.stats')


        <div class="card shadow-sm mb-4">

            <div class="card-body">

                {{-- Filter buttons --}}
                @include('components.filter-buttons')


                {{-- Create button --}}
                @can('sale-returns.create')

                    <a href="{{ route('sale-returns.create') }}"
                        class="btn btn-primary float-end">

                        <i class="fas fa-plus me-1"></i>

                        Add Sale Return

                    </a>

                @endcan

            </div>

        </div>


        {{-- Listing Card --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        Sale Return List

                    </h5>


                    <button
                        class="btn btn-outline-success btn-sm"
                        onclick="table.ajax.reload();">

                        <i class="fas fa-rotate"></i>

                        Refresh

                    </button>

                </div>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table
                        id="saleReturnTable"
                        class="table table-bordered table-hover align-middle w-100">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Return No</th>

                                <th>Invoice No</th>

                                <th>Customer</th>

                                <th>Date</th>

                                <th>Grand Total</th>

                                <th>Status</th>

                                <th id="userColumnHeader">
                                    Created By
                                </th>

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

    let table = $('#saleReturnTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,


        ajax: {
            url: "{{ route('sale-returns.datatable') }}",
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
                data: 'invoice_number',
                name: 'sale.invoice_number'
            },


            {
                data: 'customer',
                name: 'customer.name'
            },


            {
                data: 'return_date',
                name: 'return_date'
            },


            {
                data: 'total',
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
                name: 'creators.name'
            },


            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }

        ]

    });


    // Filter buttons
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


    // Delete handler
    $(document).on('submit', '.delete-form', function(e) {

        e.preventDefault();

        let form = this;


        Swal.fire({

            title: 'Delete Sale Return?',

            text: 'The sale return will be moved to trash.',

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
    // $(document).on('submit', '.restore-form', function(e) {
    //     e.preventDefault();

    //     let form = this;
    //     let name = $(form).data('name') || 'This Sale Return';
    //     let actionUrl = $(form).attr('action');

    //     Swal.fire({

    //         title: 'Restore Sale Return?',
    //         html:`Are you sure you want to restore <strong>${name}</strong>?`,
    //         icon: 'question',
    //         showCancelButton: true,
    //         confirmButtonText: 'Restore',
    //         confirmButtonColor: '#28a745',
    //         cancelButtonText: 'Cancel'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: actionUrl,
    //                 type: 'POST',
    //                 data: {
    //                     _token:'{{ csrf_token() }}',
    //                     _method: 'PATCH'
    //                 },

    //                 success: function(response) {
    //                     toastr.success(
    //                         'Sale Return restored successfully!', 'Restored'
    //                     );
    //                     table.ajax.reload();
    //                 },


    //                 error: function(xhr) {
    //                     toastr.error(
    //                         xhr.responseJSON?.message ||
    //                         'Failed to restore sale return.', 'Error'
    //                     );
    //                 }

    //             });

    //         }

    //     });

    // });
    $(document).on('submit', '.restore-form', function(e) {

        e.preventDefault();

        let form = this;

        let name =
            $(form).data('name') || 'This Sale Return';

        let actionUrl =
            $(form).attr('action');


        Swal.fire({

            title: 'Restore Sale Return?',

            html:
                `Are you sure you want to restore <strong>${name}</strong>?`,

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: 'Restore',

            confirmButtonColor: '#28a745',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            if (!result.isConfirmed) {
                return;
            }


            $.ajax({

                url: actionUrl,

                type: 'POST',

                data: {

                    _token: '{{ csrf_token() }}',

                    _method: 'PATCH'

                },

                success: function(response) {

                    if (!response.success) {

                        toastr.error(
                            response.message ||
                            'Failed to restore sale return.',
                            'Error'
                        );

                        return;
                    }


                    toastr.success(
                        response.message ||
                        'Sale Return restored successfully!',
                        'Restored'
                    );


                    table.ajax.reload(null, false);

                },

                error: function(xhr) {

                    toastr.error(

                        xhr.responseJSON?.message ||
                        'Failed to restore sale return.',

                        'Error'

                    );

                }

            });

        });

    });


    // Force Delete handler
    $(document).on('submit', '.force-delete-form', function(e) {

        e.preventDefault();

        let form = this;

        let name =
            $(form).data('name') || 'this Sale Return';

        let actionUrl =
            $(form).attr('action');


        Swal.fire({

            title: 'Permanently Delete Sale Return?',

            html:
                `Are you sure you want to permanently delete <strong>${name}</strong>?<br>
                <span class="text-danger">
                    <strong>This action cannot be undone!</strong>
                </span>`,

            icon: 'error',

            showCancelButton: true,

            confirmButtonText:
                'Yes, Permanently Delete',

            confirmButtonColor: '#dc3545',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({

                    url: actionUrl,

                    type: 'POST',

                    data: {

                        _token:
                            '{{ csrf_token() }}',

                        _method: 'DELETE'

                    },


                    success: function(response) {

                        toastr.success(
                            'Sale Return permanently deleted!',
                            'Deleted'
                        );

                        table.ajax.reload();

                    },


                    error: function(xhr) {

                        let errorMessage =
                            xhr.responseJSON?.message ||
                            'Failed to permanently delete sale return.';

                        toastr.error(
                            errorMessage,
                            'Error'
                        );

                    }

                });

            }

        });

    });

    // complete sale return 
    $(document).on('submit', '.complete-form', function(e) {

        e.preventDefault();

        let form = this;

        let name =
            $(form).data('name') ||
            'this sale return';

        Swal.fire({

            title: 'Complete Sale Return?',

            html:
                `Are you sure you want to complete ` +
                `<strong>${name}</strong>?<br>` +
                `<span class="text-success">` +
                `The returned stock will be restored to inventory.` +
                `</span>`,

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

    // cancel sale return
    $(document).on('submit', '.cancel-form', function(e) {

        e.preventDefault();

        let form = this;

        let name =
            $(form).data('name') ||
            'this sale return';

        Swal.fire({

            title: 'Cancel Sale Return?',

            html:
                `Are you sure you want to cancel ` +
                `<strong>${name}</strong>?<br>` +
                `<span class="text-danger">` +
                `This action cannot be undone.` +
                `</span>`,

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Yes, Cancel Return',

            confirmButtonColor: '#dc3545',

            cancelButtonText: 'Keep Return'

        }).then((result) => {

            if (result.isConfirmed) {

                form.submit();
            }

        });

    });

</script>

@endpush