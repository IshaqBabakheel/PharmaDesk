@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="mb-1">
            Edit Expense
        </h3>

        <p class="text-muted mb-0">
            Update {{ $expense->expense_number }}.
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
            'expenses.update',
            $expense
        ) }}"
    >

        @csrf
        @method('PUT')

        @include(
            'expenses.partials.form'
        )

    </form>

</div>

@endsection