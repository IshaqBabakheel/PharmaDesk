@extends('layouts.app')

@section('title', 'Create Expense Category')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="mb-1">
            Create Expense Category
        </h3>

        <p class="text-muted mb-0">
            Add a category for business expenses.
        </p>

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


    <form
        method="POST"
        action="{{ route('expense-categories.store') }}"
    >

        @csrf

        @include(
            'expense_categories.partials.form'
        )

    </form>

</div>

@endsection