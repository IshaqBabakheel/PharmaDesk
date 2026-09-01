@extends('layouts.app')

@section('content')
    <div class="page-header">

        <div>
            <h1>Application Settings</h1>
            <p class="subtitle">
                Configure your medical store system.
            </p>
        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header">
            General Settings
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Store Name
                        </label>

                        <input type="text" name="store_name" class="form-control"
                            value="{{ old('store_name', $settings['store_name'] ?? '') }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Owner Name
                        </label>

                        <input type="text" name="owner_name" class="form-control"
                            value="{{ old('owner_name', $settings['owner_name'] ?? '') }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Email</label>

                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $settings['email'] ?? '') }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Phone</label>

                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $settings['phone'] ?? '') }}">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Address</label>

                        <textarea name="address" class="form-control" rows="3">{{ old('address', $settings['address'] ?? '') }}</textarea>

                    </div>

                    <hr>

                    <div class="col-md-4">

                        <label>Currency</label>

                        <input type="text" name="currency" class="form-control"
                            value="{{ old('currency', $settings['currency'] ?? 'PKR') }}">

                    </div>

                    <div class="col-md-4">

                        <label>Timezone</label>

                        <input type="text" name="timezone" class="form-control"
                            value="{{ old('timezone', $settings['timezone'] ?? 'Asia/Karachi') }}">

                    </div>

                    <div class="col-md-4">

                        <label>Tax %</label>

                        <input type="number" name="tax_percentage" class="form-control"
                            value="{{ old('tax_percentage', $settings['tax_percentage'] ?? 0) }}">

                    </div>

                </div>

                <hr>

                <button class="btn btn-primary">

                    <i class="fa fa-save"></i>

                    Save Settings

                </button>

            </form>

        </div>

    </div>
@endsection
