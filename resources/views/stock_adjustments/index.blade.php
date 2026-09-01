@extends('layouts.app')

@section('title', 'Stock Adjustments')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">

                    <i class="fas fa-sliders text-primary me-2"></i>

                    Stock Adjustments

                </h3>

                <p class="text-muted mb-0">

                    Manage stock increases and decreases.

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

                        Stock Adjustments

                    </li>

                </ol>

            </nav>

        </div>

        </div>


        {{-- Session Success --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                <i class="fas fa-circle-check me-1"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif


        {{-- Session Error --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                <i class="fas fa-circle-exclamation me-1"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif


        <div class="card shadow-sm mb-4">
            {{-- Filter buttons --}}
            <div class="card-body">
                @include('components.filter-buttons')
                {{-- careate --}}
                @can('stock-adjustments.create')
                    <a href="{{ route('stock-adjustments.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus me-1"></i>
                        New Adjustment
                    </a>
                @endcan
            </div>
        </div>


        {{-- DataTable --}}
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-list me-2 text-primary"></i>

                    Adjustment Records

                </h5>

                <button class="btn btn-outline-success btn-sm" onclick="table.ajax.reload();">

                <i class="fas fa-rotate"></i>

                Refresh

            </button>

            </div>
            


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle w-100" id="stockAdjustmentsTable">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Adjustment #</th>

                                <th>Type</th>

                                <th>Date</th>

                                <th>Reason</th>

                                <th>Status</th>

                                <th>Created By</th>

                                <th width="100">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            let table = $('#stockAdjustmentsTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                ajax: {
                    url: "{{ route('stock-adjustments.datatable') }}",
                    data: function(d) {
                        d.filter = $('.filter-btn.active').data('filter') || 'all';
                    }
                },

                columns: [

                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'adjustment_number',
                        name: 'adjustment_number'
                    },

                    {
                        data: 'type',
                        name: 'type'
                    },

                    {
                        data: 'adjustment_date',
                        name: 'adjustment_date'
                    },

                    {
                        data: 'reason',
                        name: 'reason'
                    },

                    {
                        data: 'status',
                        name: 'status'
                    },

                    {
                        data: 'created_by',
                        name: 'created_by',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],

                order: [
                    [3, 'desc']
                ],

                pageLength: 25,

                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ]

            });


            /*
            |--------------------------------------------------------------------------
            | Filter
            |--------------------------------------------------------------------------
            */

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



            /*
            |--------------------------------------------------------------------------
            | Complete Confirmation
            |--------------------------------------------------------------------------
            */

            $(document).on('submit', '.complete-form', function(e) {
                e.preventDefault();

                const form = this;
                const name = $(form).data('name') || 'this stock adjustment';

                Swal.fire({
                    title: 'Complete Stock Adjustment?',

                    html: `Are you sure you want to complete
                    <strong>${name}</strong>?<br>
                    <span class="text-success">
                        The stock quantity will be updated.
                    </span>`,

                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Complete',
                    confirmButtonColor: '#198754',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | Cancel Confirmation
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'submit',
                '.cancel-form',
                function(e) {

                    e.preventDefault();


                    const form = this;

                    const name =
                        $(form).data('name') ||
                        'this stock adjustment';


                    Swal.fire({

                        title: 'Cancel Stock Adjustment?',

                        html: `Are you sure you want to cancel
                    <strong>${name}</strong>?`,

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonText: 'Yes, Cancel',

                        confirmButtonColor: '#dc3545',

                        cancelButtonText: 'Keep Adjustment'

                    }).then(function(result) {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    });

                }
            );

        });
    </script>
@endpush
