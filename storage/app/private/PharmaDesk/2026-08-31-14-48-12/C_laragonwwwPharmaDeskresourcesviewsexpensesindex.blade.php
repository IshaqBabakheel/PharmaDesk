@extends('layouts.app')

@section('title', 'Expenses')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                <i class="fas fa-receipt text-primary me-2"></i>
                Expenses
            </h3>

            <p class="text-muted mb-0">
                Manage operating expenses and their payment status.
            </p>

        </div>

        <div class="d-flex gap-2">

            @can('expense-categories.view')
                <a
                    href="{{ route('expense-categories.index') }}"
                    class="btn btn-outline-primary"
                >
                    <i class="fas fa-tags me-1"></i>
                    Categories
                </a>
            @endcan

            @can('expenses.create')
                <a
                    href="{{ route('expenses.create') }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-plus me-1"></i>
                    Add Expense
                </a>
            @endcan

        </div>

    </div>


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif


    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif


    @include('expenses.partials.stats')


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="row g-3 mb-3">

                <div class="col-md-3">

                    <label
                        for="expenseFilter"
                        class="form-label"
                    >
                        Records
                    </label>

                    <select
                        id="expenseFilter"
                        class="form-select"
                    >
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


                <div class="col-md-3">

                    <label
                        for="expenseStatusFilter"
                        class="form-label"
                    >
                        Status
                    </label>

                    <select
                        id="expenseStatusFilter"
                        class="form-select"
                    >

                        <option value="">
                            All Statuses
                        </option>

                        <option value="Completed">
                            Completed
                        </option>

                        <option value="Draft">
                            Draft
                        </option>

                        <option value="Cancelled">
                            Cancelled
                        </option>

                    </select>

                </div>


                <div class="col-md-3">

                    <select id="expenseCategoryFilter" class="form-select">
                        <option value="">
                            All Categories
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                </div>

            </div>


            <div class="table-responsive">

                <table
                    id="expensesTable"
                    class="table table-bordered table-hover align-middle w-100"
                >

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Expense #</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Payment Status</th>
                            <th>Status</th>
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
$(function () {
    
    const table =
        $('#expensesTable').DataTable({

            processing: true,
            serverSide: true,
            responsive: true,

            ajax: {

                url:
                    "{{ route('expenses.datatable') }}",

                data: function (d) {

                    d.filter =
                        $('#expenseFilter').val();

                    d.status =
                        $('#expenseStatusFilter').val();

                    d.expense_category_id =
                        $('#expenseCategoryFilter').val();

                }

            },

            columns: [

                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'expense_number'
                },

                {
                    data: 'expense_date'
                },

                {
                    data: 'title'
                },

                {
                    data: 'category'
                },

                {
                    data: 'amount',
                    className: 'text-end fw-semibold'
                },

                {
                    data: 'paid_amount',
                    className: 'text-end text-success'
                },

                {
                    data: 'due_amount',
                    className: 'text-end text-danger'
                },

                {
                    data: 'payment_status'
                },

                {
                    data: 'status'
                },

                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }

            ],

            order: [
                [2, 'desc']
            ],

            pageLength: 25

        });

    loadExpenseStats();
    $('#expenseFilter, #expenseStatusFilter')
        .on('change', function () {

            table.ajax.reload();
            loadExpenseStats();

        });


    $('#expenseCategoryFilter').on('input', function () {

            clearTimeout(
                window.expenseFilterTimer
            );

            window.expenseFilterTimer =
                setTimeout(
                    function () {

                        table.ajax.reload();
                        loadExpenseStats();
                    },
                    350
                );

        });


    function loadExpenseStats() {

        $.ajax({

            url:
                "{{ route('expenses.statistics') }}",

            type: 'GET',

            data: {

                filter:
                    $('#expenseFilter').val(),

                status:
                    $('#expenseStatusFilter').val(),

                expense_category_id:
                    $('#expenseCategoryFilter').val()

            },

            success: function (response) {

                if (
                    !response.success ||
                    !response.statistics
                ) {
                    return;
                }

                const stats =
                    response.statistics;


                $('#statExpenseCount').text(
                    Number(
                        stats.count || 0
                    ).toLocaleString()
                );


                $('#statExpenseAmount').text(
                    'Rs. ' +
                    Number(
                        stats.amount || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );


                $('#statExpensePaid').text(
                    'Rs. ' +
                    Number(
                        stats.paid_amount || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );


                $('#statExpenseDue').text(
                    'Rs. ' +
                    Number(
                        stats.due_amount || 0
                    ).toLocaleString(
                        'en-PK',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    )
                );

            },

            error: function (xhr) {

                console.error(
                    'Expense statistics error:',
                    xhr.responseJSON
                );

            }

        });

    }

});
</script>

@endpush