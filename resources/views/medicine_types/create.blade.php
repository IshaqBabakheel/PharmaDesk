@extends('layouts.app')

@section('title', 'Add Medicine Type')

@section('content')
    {{-- Page Header --}}
    @include('medicine_types.page-header')

    {{-- main content --}}
    <div class="card">

        <div class="card-header">

            <h4>

                Add Medicine Type

            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('medicine-types.store') }}" method="POST">

                @csrf

                @include('medicine_types.form')

            </form>

        </div>

    </div>

@endsection
