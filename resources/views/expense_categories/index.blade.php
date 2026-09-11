@extends('layouts.app')

@section('title', 'Expense Categories')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">
                    <i class="fas fa-tags text-primary me-2"></i>
                    Expense Categories
                </h3>

                <p class="text-muted mb-0">
                    Manage categories used for business expenses.
                </p>
            </div>

            @can('expense-categories.create')
                <a href="{{ route('expense-categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Add Category
                </a>
            @endcan

        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                {{-- Filter buttons for showing trashed records --}}
                @include('components.filter-buttons')
                {{-- create button --}}
                @can('expense-categories.create')
                    <a href="{{ route('expense-categories.create') }}" class="btn btn-primary float-end">
                        <i class="fas fa-plus me-1"></i>
                        Add Category
                    </a>
                @endcan
            </div>
        </div>

        <div class="card shadow-sm border-0">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    Expense Categories List

                </h5>

                <button class="btn btn-outline-success btn-sm"onclick="table.ajax.reload();">

                    <i class="fas fa-rotate"></i>

                    Refresh

                </button>

            </div>
            <div class="card-body">

                {{-- <div class="row mb-3">

                <div class="col-md-4">

                    <label for="categoryFilter" class="form-label">
                        Records
                    </label>

                    <select id="categoryFilter" class="form-select">
                        <option value="active">
                            Active
                        </option>

                        <option value="all">
                            All
                        </option>

                        <option value="trashed">
                            Trashed
                        </option>
                    </select>

                </div>

            </div> --}}


                <div class="table-responsive">

                    <table id="expenseCategoriesTable" class="table table-bordered table-hover align-middle w-100">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created</th>
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

            const table = $('#expenseCategoriesTable').DataTable({

                    processing: true,
                    serverSide: true,
                    responsive: true,

                    ajax: {
                        url: "{{ route('expense-categories.datatable') }}",

                        data: function(d) {
                            d.filter = $('.filter-btn.active').data('filter') || 'all';
                        }
                    },

                    columns: [

                        {
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },

                        {
                            data: 'name'
                        },

                        {
                            data: 'description',
                            render: function(data) {
                                return data || '-';
                            }
                        },

                        {
                            data: 'status',
                            orderable: false
                        },

                        {
                            data: 'created_at',

                            render: function(data) {

                                if (!data) {
                                    return '-';
                                }

                                return new Date(
                                    data
                                ).toLocaleDateString(
                                    'en-GB'
                                );
                            }
                        },

                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }

                    ],

                    order: [
                        [1, 'asc']
                    ],

                    pageLength: 25
                });


            $('#btnFilter').click(function() {

                table.ajax.reload();

            });

            $('#btnReset').click(function() {
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

        });
    </script>
@endpush
