@extends('layouts.app')

@section('title', 'Create Payment')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-money-bill-wave text-primary me-2"></i>
                Record Payment
            </h3>
            <p class="text-muted mb-0">
                Record a customer receipt or supplier payment.
            </p>
        </div>

        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf

        @include('payments.partials.form', [
            'payment' => null,
            'sales' => $sales,
            'purchases' => $purchases,
            'isEdit' => false,
        ])
    </form>

</div>

@endsection