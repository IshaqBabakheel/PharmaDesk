@extends('layouts.app')


@section('content')
    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-pen-to-square text-warning me-2"></i>

                Edit Customer

            </h2>

            <p class="text-muted mb-0">

                Update Customer information.

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

                        Customers

                    </li>

                    <li class="breadcrumb-item active">

                        Edit

                    </li>

                </ol>

            </nav>

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
    <div class="container">


        <div class="card">


            <div class="card-header">

                <h5>
                    Edit Customer
                </h5>

            </div>




            <div class="card-body">


                <form method="POST" action="{{ route('customers.update', $customer) }}">


                    @csrf

                    @method('PUT')

                    @include('customers.partials.form')

                    <div class="text-end">

                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">

                            Cancel

                        </a>

                        <button class="btn btn-primary" type="submit">

                            <i class="fas fa-save me-1"></i>
                            Update
                        </button>

                    </div>


                </form>


            </div>


        </div>


    </div>
@endsection
