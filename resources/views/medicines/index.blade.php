@extends('layouts.app')

@section('content')
    {{-- ============================================== --}}
{{-- Page Header --}}
{{-- ============================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="fas fa-capsules text-primary me-2"></i>
            Medicines
        </h2>
        <p class="text-muted mb-0">
            Manage medicines, pricing, stock levels and inventory.
        </p>
    </div>
    <div>
        <nav>
            <ol class="breadcrumb justify-content-end mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    Master Data
                </li>
                <li class="breadcrumb-item active">
                    Medicines
                </li>
            </ol>
        </nav>
    </div>
</div>



    @include('medicines.partials.stats')



    @include('medicines.partials.filters')


    <div class="card shadow-sm mb-4">
        <div class="card-body">
            {{-- Filter buttons for showing trashed records --}}
            @include('components.filter-buttons')
            {{-- create button --}}
            @can('medicines.create')
                <a href="{{ route('medicines.create') }}" class="btn btn-primary float-end">
                    <i class="fas fa-plus me-1"></i>
                    Add Medicine
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Medicine List

            </h5>

            <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">

                <i class="fas fa-rotate"></i>

                Refresh

            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle" id="medicineTable" width="100%">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Image</th>

                            <th>Medicine</th>

                            <th>SKU</th>

                            <th>Category</th>

                            <th>Manufacturer</th>

                            <th>Purchase</th>

                            <th>Sale</th>

                            <th>Stock</th>

                            {{-- Dynamic column header that changes based on filter --}}
                            <th id="userColumnHeader">Created By</th>

                            <th>Status</th>

                            <th width="80">

                                Action

                            </th>

                        </tr>

                    </thead>
                    <tbody></tbody>

                </table>

            </div>

        </div>

    </div>
@endsection



@push('scripts')
    <script>
        $(function() {

            table = $('#medicineTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                autoWidth: false,


                ajax: {
                    url: "{{ route('medicines.datatable') }}",
                    data: function(d) {
                        d.filter = $('.filter-btn.active').data('filter') || 'all';
                        d.search_text = $('#search').val();
                        d.category = $('#filter_category').val();
                        d.type = $('#filter_type').val();
                        d.manufacturer = $('#filter_manufacturer').val();
                        d.status = $('#filter_status').val();
                        d.stock = $('#filter_stock').val();
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

                        data: 'image',

                        name: 'image',

                        searchable: false,

                        orderable: false

                    },

                    {

                        data: 'name',

                        name: 'name'

                    },

                    {

                        data: 'sku',

                        name: 'sku'

                    },

                    {

                        data: 'category',

                        name: 'category.name'

                    },

                    {

                        data: 'manufacturer',

                        name: 'manufacturer.name'

                    },

                    {

                        data: 'purchase_price'

                    },

                    {

                        data: 'selling_price'

                    },

                    {

                        data: 'current_stock',

                        searchable: false

                    },

                    {

                        data: 'created_by',

                        name: 'creator.name'

                    },

                    {

                        data: 'status'

                    },

                    {

                        data: 'action',

                        searchable: false,

                        orderable: false

                    }

                ]

            });

            $('#btnFilter').click(function() {

                table.ajax.reload();

            });

            $('#btnReset').click(function() {

                $('#medicineFilterForm')[0].reset();

                table.ajax.reload();

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
                    title: 'Delete Medicine?',
                    text: 'The Medicine will be moved to trash.',
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
                let name = $(form).data('name') || 'This Medicine';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Restore Medicine?',
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
                                toastr.success('Medicine restored successfully!', 'Restored');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Failed to restore medicine.', 'Error');
                            }
                        });
                    }
                });
            });

            // Force Delete handler
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault();
                let form = this;
                let name = $(form).data('name') || 'this Medicine';
                let actionUrl = $(form).attr('action');

                Swal.fire({
                    title: 'Permanently Delete Medicine?',
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
                                toastr.success('Medicine permanently deleted!', 'Deleted');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = xhr.responseJSON?.message || 'Failed to permanently delete medicine.';
                                toastr.error(errorMessage, 'Error');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
