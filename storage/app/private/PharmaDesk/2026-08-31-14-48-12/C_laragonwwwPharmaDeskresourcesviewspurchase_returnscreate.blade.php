@extends('layouts.app')

@section('title','Create Purchase Return')

@section('content')

<div class="container-fluid">

    <form action="{{ route('purchase-returns.store') }}" method="POST" id="purchaseReturnForm">

        @csrf

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>

                    <i class="fas fa-rotate-left text-primary"></i>

                    Create Purchase Return

                </h3>

                <p class="text-muted mb-0">

                    Create a new purchase return.

                </p>

            </div>

            <div>

                <a href="{{ route('purchase-returns.index') }}" class="btn btn-secondary">

                    <i class="fas fa-arrow-left me-2"></i>

                    Back

                </a>

                <button type="submit" class="btn btn-primary">

                    <i class="fas fa-save me-2"></i>

                    Save Purchase Return

                </button>

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

        @include('purchase_returns.partials.form')

    </form>

</div>

@endsection