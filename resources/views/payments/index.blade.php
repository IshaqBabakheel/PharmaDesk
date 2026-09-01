@extends('layouts.app')

@section('title', 'Payments')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="fas fa-money-bill-wave text-primary me-2"></i>
                    Payments
                </h3>
                <p class="text-muted mb-0">
                    Manage customer receipts and supplier payments.
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

                            Payments

                        </li>

                    </ol>

                </nav>

            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-circle-check me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-circle-exclamation me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- filters --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                @include('components.filter-buttons')

                <div class="row float-end me-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">
                            <i class="fas fa-list me-1"></i> All
                        </button>
                        <button type="button" class="btn btn-outline-success filter-btn" data-filter="receipt">
                            <i class="fas fa-check-circle me-1"></i> Receipts
                        </button>
                        <button type="button" class="btn btn-outline-warning filter-btn" data-filter="payment">
                            <i class="fas fa-trash me-1"></i> Supplier Payments
                        </button>
                    </div>    
                </div>

            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Payment Records
                </h5>
                @can('payments.create')
                    <a href="{{ route('payments.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus me-1"></i>
                        New Payments
                    </a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle w-100" id="paymentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Payment #</th>
                                <th>Type</th>
                                <th>Reference</th>
                                <th>Party</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            const table = $('#paymentsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('payments.datatable') }}",
                    data: function(d) {
                        d.filter = $('.filter-btn.active').data('filter') || 'all';
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'payment_number',
                        name: 'payment_number'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'reference',
                        name: 'reference',
                        orderable: false
                    },
                    {
                        data: 'party',
                        name: 'party',
                        orderable: false
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'method',
                        name: 'method'
                    },
                    {
                        data: 'payment_date',
                        name: 'payment_date'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [7, 'desc']
                ],
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
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
        });
    </script>
@endpush
