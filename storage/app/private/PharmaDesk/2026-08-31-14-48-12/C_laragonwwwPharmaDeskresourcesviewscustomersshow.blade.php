@extends('layouts.app')

@section('content')

<div class="container-fluid">

{{-- =========================================================
    PAGE HEADER
========================================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            Customer Details
        </h4>

        <p class="text-muted mb-0">
            View customer information, account summary and sales history.
        </p>
    </div>


    <div class="d-flex gap-2">

        <a href="{{ route('customers.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Back

        </a>


        @can('update', $customer)

            <a href="{{ route('customers.edit', $customer) }}"
               class="btn btn-primary">

                <i class="fas fa-edit"></i>
                Edit

            </a>

        @endcan

    </div>

</div>



{{-- =========================================================
    CUSTOMER SUMMARY CARDS
========================================================== --}}

<div class="row mb-4">


    {{-- Total Sales --}}
    <div class="col-md-3 mb-3">

        <div class="card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <p class="text-muted mb-1">
                            Total Sales
                        </p>

                        <h4 class="mb-0">

                            {{ number_format(
                                $customer->sales->sum('grand_total'),
                                2
                            ) }}

                        </h4>

                    </div>


                    <div class="text-primary">

                        <i class="fas fa-shopping-cart fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Total Paid --}}
    <div class="col-md-3 mb-3">

        <div class="card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <p class="text-muted mb-1">
                            Total Paid
                        </p>

                        <h4 class="mb-0">

                            {{ number_format(
                                $customer->sales->sum('paid_amount'),
                                2
                            ) }}

                        </h4>

                    </div>


                    <div class="text-success">

                        <i class="fas fa-money-bill-wave fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Total Due --}}
    <div class="col-md-3 mb-3">

        <div class="card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <p class="text-muted mb-1">
                            Total Due
                        </p>

                        <h4 class="mb-0 text-danger">

                            {{ number_format(
                                $customer->sales->sum('due_amount'),
                                2
                            ) }}

                        </h4>

                    </div>


                    <div class="text-danger">

                        <i class="fas fa-exclamation-circle fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Credit Limit --}}
    <div class="col-md-3 mb-3">

        <div class="card h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <p class="text-muted mb-1">
                            Credit Limit
                        </p>

                        <h4 class="mb-0">

                            {{ number_format(
                                $customer->credit_limit,
                                2
                            ) }}

                        </h4>

                    </div>


                    <div class="text-warning">

                        <i class="fas fa-credit-card fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


</div>



<div class="row">


    {{-- =====================================================
        CUSTOMER INFORMATION
    ====================================================== --}}

    <div class="col-lg-4 mb-4">

        <div class="card h-100">


            <div class="card-header">

                <h5 class="mb-0">
                    Customer Information
                </h5>

            </div>



            <div class="card-body">


                <div class="text-center mb-4">

                    <div class="mb-3">

                        <div class="rounded-circle bg-light d-inline-flex
                                    align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">

                            <i class="fas fa-user fa-2x text-primary"></i>

                        </div>

                    </div>


                    <h5 class="mb-1">
                        {{ $customer->name }}
                    </h5>


                    <div>
                        {!! $customer->type_badge !!}
                    </div>

                </div>



                <div class="mb-3">

                    <strong>
                        Phone
                    </strong>

                    <div class="text-muted">

                        {{ $customer->phone ?: '-' }}

                    </div>

                </div>



                <div class="mb-3">

                    <strong>
                        Email
                    </strong>

                    <div class="text-muted">

                        {{ $customer->email ?: '-' }}

                    </div>

                </div>



                <div class="mb-3">

                    <strong>
                        Gender
                    </strong>

                    <div class="text-muted text-capitalize">

                        {{ $customer->gender ?: '-' }}

                    </div>

                </div>



                <div class="mb-3">

                    <strong>
                        Date of Birth
                    </strong>

                    <div class="text-muted">

                        {{ $customer->date_of_birth
                            ? $customer->date_of_birth->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>



                <div class="mb-3">

                    <strong>
                        Address
                    </strong>

                    <div class="text-muted">

                        {{ $customer->full_address ?: '-' }}

                    </div>

                </div>



            </div>

        </div>

    </div>




    {{-- =====================================================
        ACCOUNT INFORMATION
    ====================================================== --}}

    <div class="col-lg-4 mb-4">

        <div class="card h-100">


            <div class="card-header">

                <h5 class="mb-0">
                    Account Information
                </h5>

            </div>



            <div class="card-body">


                <div class="d-flex justify-content-between mb-3">

                    <span>
                        Credit Limit
                    </span>

                    <strong>

                        {{ number_format(
                            $customer->credit_limit,
                            2
                        ) }}

                    </strong>

                </div>



                <div class="d-flex justify-content-between mb-3">

                    <span>
                        Opening Balance
                    </span>

                    <strong>

                        {{ number_format(
                            $customer->opening_balance,
                            2
                        ) }}

                    </strong>

                </div>



                <div class="d-flex justify-content-between mb-3">

                    <span>
                        Balance Type
                    </span>

                    <span>

                        {!! $customer->balance_badge !!}

                    </span>

                </div>



                <hr>



                <div class="d-flex justify-content-between mb-3">

                    <span>
                        Total Sales
                    </span>

                    <strong>

                        {{ number_format(
                            $customer->sales->sum('grand_total'),
                            2
                        ) }}

                    </strong>

                </div>



                <div class="d-flex justify-content-between mb-3">

                    <span>
                        Total Paid
                    </span>

                    <strong class="text-success">

                        {{ number_format(
                            $customer->sales->sum('paid_amount'),
                            2
                        ) }}

                    </strong>

                </div>



                <div class="d-flex justify-content-between mb-3">

                    <span>
                        Total Due
                    </span>

                    <strong class="text-danger">

                        {{ number_format(
                            $customer->sales->sum('due_amount'),
                            2
                        ) }}

                    </strong>

                </div>



            </div>

        </div>

    </div>




    {{-- =====================================================
        MEDICAL INFORMATION
    ====================================================== --}}

    <div class="col-lg-4 mb-4">

        <div class="card h-100">


            <div class="card-header">

                <h5 class="mb-0">
                    Medical Information
                </h5>

            </div>



            <div class="card-body">


                <div class="mb-3">

                    <strong>
                        Blood Group
                    </strong>

                    <div class="text-muted">

                        {{ $customer->blood_group ?: '-' }}

                    </div>

                </div>



                <div class="mb-3">

                    <strong>
                        Allergies
                    </strong>

                    <div class="text-muted">

                        {{ $customer->allergies ?: '-' }}

                    </div>

                </div>



                <div class="mb-3">

                    <strong>
                        Notes
                    </strong>

                    <div class="text-muted">

                        {{ $customer->notes ?: '-' }}

                    </div>

                </div>



                <hr>



                <div class="mb-3">

                    <strong>
                        Created By
                    </strong>

                    <div class="text-muted">

                        {{ $customer->creator->name ?? '-' }}

                    </div>

                </div>



                <div>

                    <strong>
                        Created At
                    </strong>

                    <div class="text-muted">

                        {{ $customer->created_at
                            ? $customer->created_at->format('d M Y, h:i A')
                            : '-'
                        }}

                    </div>

                </div>


            </div>

        </div>

    </div>


</div>



{{-- =========================================================
    SALES HISTORY
========================================================== --}}

<div class="card">


    <div class="card-header d-flex justify-content-between
                align-items-center">

        <h5 class="mb-0">
            Sales History
        </h5>


        <span class="badge bg-primary">

            {{ $customer->sales->count() }}

            Sales

        </span>

    </div>



    <div class="card-body">


        @if($customer->sales->count() > 0)


            <div class="table-responsive">


                <table class="table table-bordered
                              table-hover align-middle">


                    <thead>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Invoice
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Grand Total
                        </th>

                        <th>
                            Paid
                        </th>

                        <th>
                            Due
                        </th>

                        <th>
                            Payment Status
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                    </thead>



                    <tbody>


                    @foreach(
                        $customer->sales->sortByDesc('sale_date')
                        as $sale
                    )


                        <tr>


                            <td>
                                {{ $loop->iteration }}
                            </td>



                            <td>

                                <strong>
                                    {{ $sale->invoice_number }}
                                </strong>

                            </td>



                            <td>

                                {{ $sale->sale_date
                                    ? \Carbon\Carbon::parse(
                                        $sale->sale_date
                                    )->format('d M Y')
                                    : '-'
                                }}

                            </td>



                            <td>

                                {{ number_format(
                                    $sale->grand_total,
                                    2
                                ) }}

                            </td>



                            <td class="text-success">

                                {{ number_format(
                                    $sale->paid_amount,
                                    2
                                ) }}

                            </td>



                            <td class="text-danger">

                                {{ number_format(
                                    $sale->due_amount,
                                    2
                                ) }}

                            </td>



                            <td>

                                @if(
                                    $sale->payment_status
                                    === 'paid'
                                )

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif(
                                    $sale->payment_status
                                    === 'partial'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Partial
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Due
                                    </span>

                                @endif

                            </td>



                            <td>

                                @if(
                                    $sale->status
                                    === 'completed'
                                )

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @elseif(
                                    $sale->status
                                    === 'draft'
                                )

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                @elseif(
                                    $sale->status
                                    === 'cancelled'
                                )

                                    <span class="badge bg-danger">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($sale->status) }}
                                    </span>

                                @endif

                            </td>



                            <td>

                                <a href="{{ route(
                                    'sales.show',
                                    $sale
                                ) }}"
                                   class="btn btn-sm btn-info">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </td>


                        </tr>


                    @endforeach


                    </tbody>


                </table>

            </div>


        @else


            <div class="text-center py-5">

                <i class="fas fa-shopping-cart
                          fa-3x text-muted mb-3"></i>

                <h5>
                    No Sales Found
                </h5>

                <p class="text-muted mb-0">

                    This customer has no sales records yet.

                </p>

            </div>


        @endif


    </div>


</div>

</div>

@endsection
