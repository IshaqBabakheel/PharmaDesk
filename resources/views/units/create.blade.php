@extends('layouts.app')

@section('content')
{{-- Page Header --}}
    @include('units.page-header')

{{-- main content --}}
<div class="card">

    <div class="card-header">
        <h4>Add Unit</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('units.store') }}" method="POST">

            @csrf

            @include('units.form')

        </form>

    </div>

</div>

@endsection