@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $currency = 'Rs.';
        $userName = auth()->user()?->name ?? 'Administrator';

        $hour = now()->hour;
        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default => 'Good evening',
        };

        $maxChart = max(collect($salesChart)->max('value'), 1);

        $paidPercent = $sales['net'] > 0 ? min(($sales['paid'] / $sales['net']) * 100, 100) : 0;
    @endphp

    <div class="pharma-dashboard">

        {{-- Header --}}
        <div class="dashboard-header">
            <div>
                <div class="dashboard-eyebrow">
                    <span class="status-dot"></span>
                    Pharmacy operations
                </div>

                <h1 class="dashboard-title">
                    {{ $greeting }}, {{ $userName }}
                </h1>

                <p class="dashboard-subtitle">
                    Here is what is happening in your pharmacy today.
                </p>
            </div>

            <div class="dashboard-actions">
                <div class="dashboard-period">
                    <a href="{{ route('home', ['period' => 'today']) }}" class="{{ $period === 'today' ? 'active' : '' }}">
                        Today
                    </a>

                    <a href="{{ route('home', ['period' => 'week']) }}" class="{{ $period === 'week' ? 'active' : '' }}">
                        Week
                    </a>

                    <a href="{{ route('home', ['period' => 'month']) }}" class="{{ $period === 'month' ? 'active' : '' }}">
                        Month
                    </a>

                    <a href="{{ route('home', ['period' => 'year']) }}" class="{{ $period === 'year' ? 'active' : '' }}">
                        Year
                    </a>
                </div>

                <button type="button" class="pharma-btn pharma-btn-light" id="dashboardRefresh">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    Refresh
                </button>
            </div>
        </div>

        {{-- KPI Cards --}}
        @include('dashboard.partials.kpi-cards')

        {{-- Attention --}}
        @include('dashboard.partials.alerts')

        {{-- Sales Chart + Today's Sales --}}
        <div class="row g-3 mb-3">
            <div class="col-xl-8">
                <section class="dashboard-card h-100">
                    <div class="dashboard-card-header">
                        <div>
                            <h2>Sales Overview</h2>
                            <p>
                                @switch($period)
                                    @case('today')
                                        Completed sales for today by hour.
                                    @break

                                    @case('week')
                                        Completed sales for the last 7 days.
                                    @break

                                    @case('month')
                                        Completed sales for the last 30 days.
                                    @break

                                    @case('year')
                                        Completed sales for this year by month.
                                    @break

                                    @default
                                        Completed sales.
                                @endswitch
                            </p>
                        </div>

                        <span class="dashboard-chip">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $periodLabel }}
                        </span>
                    </div>

                    <div class="sales-chart">
                        @foreach ($salesChart as $point)
                            @php
                                $height = $point['value'] > 0 ? max(($point['value'] / $maxChart) * 100, 7) : 4;
                            @endphp

                            <div class="sales-chart-item">
                                <div class="sales-chart-value">
                                    @if ($point['value'] > 0)
                                        {{ $currency }}{{ number_format($point['value'], 0) }}
                                    @endif
                                </div>

                                <div class="sales-chart-track">
                                    <div class="sales-chart-bar" style="height: {{ $height }}%;"
                                        title="{{ $point['full_label'] }} — {{ $currency }}{{ number_format($point['value'], 2) }}">
                                    </div>
                                </div>

                                <div class="sales-chart-label">
                                    {{ $point['label'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="col-xl-4">
                <section class="dashboard-card h-100">
                    <div class="dashboard-card-header">
                        <div>
                            <h2>Sales Summary</h2>
                            <p>{{ $periodLabel }}</p>
                        </div>

                        <div class="card-icon card-icon-blue">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                    </div>

                    <div class="sales-summary">
                        <div class="summary-line summary-line-main">
                            <span>Net Sales</span>
                            <strong>{{ $currency }}{{ number_format($sales['net'], 2) }}</strong>
                        </div>

                        <div class="summary-line">
                            <span>Paid</span>
                            <strong class="text-success">
                                {{ $currency }}{{ number_format($sales['paid'], 2) }}
                            </strong>
                        </div>

                        <div class="summary-line">
                            <span>Outstanding</span>
                            <strong class="text-warning">
                                {{ $currency }}{{ number_format($sales['due'], 2) }}
                            </strong>
                        </div>

                        <div class="summary-line">
                            <span>Returns</span>
                            <strong class="text-danger">
                                {{ $currency }}{{ number_format($sales['returns'], 2) }}
                            </strong>
                        </div>

                        <div class="payment-progress">
                            <div class="payment-progress-head">
                                <span>Collection progress</span>
                                <strong>{{ number_format($paidPercent, 0) }}%</strong>
                            </div>

                            <div class="payment-progress-track">
                                <div class="payment-progress-bar" style="width: {{ $paidPercent }}%;"></div>
                            </div>
                        </div>

                        <div class="summary-footer">
                            <span>
                                <i class="fa-solid fa-file-invoice"></i>
                                {{ number_format($sales['count']) }} completed sales
                            </span>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        {{-- Inventory + Expiry --}}
        @include('dashboard.partials.inventory-health')

        {{-- Quick Actions --}}
        <section class="dashboard-card mb-3">
            <div class="dashboard-card-header">
                <div>
                    <h2>Quick Actions</h2>
                    <p>Common pharmacy tasks, one click away.</p>
                </div>
            </div>

            <div class="quick-actions">
                @can('create', App\Models\Sale::class)
                    <a href="{{ route('sales.create') }}" class="quick-action quick-action-primary">
                        <span class="quick-action-icon">
                            <i class="fa-solid fa-cart-plus"></i>
                        </span>
                        <span>
                            <strong>New Sale</strong>
                            <small>Create a customer sale</small>
                        </span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endcan

                @can('create', App\Models\Purchase::class)
                    <a href="{{ route('purchases.create') }}" class="quick-action">
                        <span class="quick-action-icon">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </span>
                        <span>
                            <strong>New Purchase</strong>
                            <small>Receive new stock</small>
                        </span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endcan

                @can('create', App\Models\Medicine::class)
                    <a href="{{ route('medicines.create') }}" class="quick-action">
                        <span class="quick-action-icon">
                            <i class="fa-solid fa-capsules"></i>
                        </span>
                        <span>
                            <strong>New Medicine</strong>
                            <small>Add a medicine</small>
                        </span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endcan

                @can('create', App\Models\Customer::class)
                    <a href="{{ route('customers.create') }}" class="quick-action">
                        <span class="quick-action-icon">
                            <i class="fa-solid fa-user-plus"></i>
                        </span>
                        <span>
                            <strong>New Customer</strong>
                            <small>Register a customer</small>
                        </span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endcan

                @can('create', App\Models\Supplier::class)
                    <a href="{{ route('suppliers.create') }}" class="quick-action">
                        <span class="quick-action-icon">
                            <i class="fa-solid fa-address-card"></i>
                        </span>
                        <span>
                            <strong>New Supplier</strong>
                            <small>Add a supplier</small>
                        </span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endcan

                @can('stocks.adjust')
                    <a href="{{ route('stock-adjustments.create') }}" class="quick-action">
                        <span class="quick-action-icon">
                            <i class="fa-solid fa-sliders"></i>
                        </span>
                        <span>
                            <strong>Adjust Stock</strong>
                            <small>Correct physical stock</small>
                        </span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endcan
            </div>
        </section>

        {{-- Recent Sales + Top Medicines --}}
        <div class="row g-3 mb-3">
            @include('dashboard.partials.recent-sales')

            @include('dashboard.partials.top-medicines')
        </div>

        {{-- Financial Snapshot --}}
        @include('dashboard.partials.financial-snapshot')

    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
