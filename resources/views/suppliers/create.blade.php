@extends('layouts.app')

@section('content')

    {{-- Page Header --}}
    @include('suppliers.partials.page-header')

    {{-- main content --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">

                Supplier Information

            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('suppliers.store') }}"
                method="POST"
            >

                @include('suppliers.partials.form')

            </form>

        </div>

    </div>

@endsection