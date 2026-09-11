@extends('layouts.app')

@section('title', 'Edit Medicine Type')

@section('content')
    {{-- Page Header --}}
    @include('medicine_types.page-header')

    {{-- main content --}}
    <div class="card">

        <div class="card-header">

            <h4>

                Edit Medicine Type

            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('medicine-types.update', $medicineType) }}" method="POST">

                @csrf

                @method('PUT')

                @include('medicine_types.form')

            </form>

        </div>

    </div>

@endsection
