@extends('layouts.app')

@section('content')
{{-- Page Header --}}
    @include('units.page-header')

{{-- main content  --}}
<div class="card">

    <div class="card-header">
        <h4>Edit Unit</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('units.update',$unit) }}" method="POST">

            @csrf
            @method('PUT')

            @include('units.form')

        </form>

    </div>

</div>

@endsection