@extends('layouts.app')

@section('title', 'Create Manufacturers')

@section('content')
    {{-- Page Header --}}
    @include('manufacturers.page-header')

    {{-- main content --}}
    <div class="card">

        <div class="card-header">

            <h4>

                Add Manufacturer

            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('manufacturers.store') }}" method="POST">

                @csrf

                @include('manufacturers.form')

            </form>

        </div>

    </div>

@endsection
