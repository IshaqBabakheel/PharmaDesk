@extends('layouts.app')

@section('title', 'Edit Manufacturers')

@section('content')
    {{-- Page Header --}}
    @include('manufacturers.page-header')

    {{-- main content --}}
    <div class="card">

        <div class="card-header">

            <h4>

                Edit Manufacturer

            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('manufacturers.update', $manufacturer) }}" method="POST">

                @csrf

                @method('PUT')

                @include('manufacturers.form')

            </form>

        </div>

    </div>

@endsection
