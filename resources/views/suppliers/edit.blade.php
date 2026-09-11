@extends('layouts.app')

@section('content')

    {{-- Page Header --}}
    @include('suppliers.partials.page-header')

    {{-- main content --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">

                Update Supplier

            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('suppliers.update',$supplier) }}"
                method="POST"
            >

                @csrf

                @method('PUT')

                @include('suppliers.partials.form')

            </form>

        </div>

    </div>

@endsection