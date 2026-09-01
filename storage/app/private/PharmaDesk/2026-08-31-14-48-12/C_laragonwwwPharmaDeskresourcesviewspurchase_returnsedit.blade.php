@extends('layouts.app')

@section('title','Edit Purchase Return')

@section('content')

<div class="container-fluid">

    <form action="{{ route('purchase-returns.update',$purchaseReturn) }}" method="POST" id="purchaseReturnForm">

        @csrf

        @method('PUT')

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>

                    <i class="fas fa-edit text-warning"></i>

                    Edit Purchase Return

                </h3>

                <p class="text-muted mb-0">

                    Update purchase return information.

                </p>

            </div>

            <div>

                <a href="{{ route('purchase-returns.index') }}" class="btn btn-secondary">

                    <i class="fas fa-arrow-left me-2"></i>

                    Back

                </a>

                <button type="submit" class="btn btn-warning">

                    <i class="fas fa-save me-2"></i>

                    Update Purchase Return

                </button>

            </div>

        </div>

        @include('purchase_returns.partials.form')

    </form>

</div>

@endsection