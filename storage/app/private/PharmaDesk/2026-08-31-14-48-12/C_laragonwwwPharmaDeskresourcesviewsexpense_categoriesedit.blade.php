@extends('layouts.app')

@section('title', 'Edit Expense Category')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="mb-1">
            Edit Expense Category
        </h3>

        <p class="text-muted mb-0">
            Update {{ $expenseCategory->name }}.
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
        action="{{ route(
            'expense-categories.update',
            $expenseCategory
        ) }}"
    >

        @csrf
        @method('PUT')

        @include(
            'expense_categories.partials.form'
        )

    </form>

</div>

@endsection