@php
    $currency = 'Rs.';
@endphp

<div class="row g-3 mb-3">

    <div class="col-12 col-sm-6 col-xl-3">
        <article class="dashboard-stat dashboard-stat-blue">
            <div class="dashboard-stat-top">
                <div>
                    <span class="dashboard-stat-label">Sales</span>
                    <strong class="dashboard-stat-value">
                        {{ $currency }}{{ number_format($sales['net'], 2) }}
                    </strong>
                </div>

                <span class="dashboard-stat-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </span>
            </div>

            <div class="dashboard-stat-bottom">
                <span>
                    <i class="fa-solid fa-receipt"></i>
                    {{ number_format($sales['count']) }} completed
                </span>

                @if($sales['due'] > 0)
                    <span class="dashboard-stat-warning">
                        {{ $currency }}{{ number_format($sales['due'], 0) }} due
                    </span>
                @else
                    <span class="dashboard-stat-positive">Fully collected</span>
                @endif
            </div>
        </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <article class="dashboard-stat dashboard-stat-green">
            <div class="dashboard-stat-top">
                <div>
                    <span class="dashboard-stat-label">Net Profit</span>
                    <strong class="dashboard-stat-value">
                        {{ $currency }}{{ number_format($profit['net_profit'], 2) }}
                    </strong>
                </div>

                <span class="dashboard-stat-icon">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </span>
            </div>

            <div class="dashboard-stat-bottom">
                <span>
                    {{ number_format($profit['margin'], 1) }}% gross margin
                </span>

                <span class="dashboard-stat-positive">
                    {{ $periodLabel }}
                </span>
            </div>
        </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <article class="dashboard-stat dashboard-stat-purple">
            <div class="dashboard-stat-top">
                <div>
                    <span class="dashboard-stat-label">Purchases</span>
                    <strong class="dashboard-stat-value">
                        {{ $currency }}{{ number_format($purchases['total'], 2) }}
                    </strong>
                </div>

                <span class="dashboard-stat-icon">
                    <i class="fa-solid fa-truck-fast"></i>
                </span>
            </div>

            <div class="dashboard-stat-bottom">
                <span>
                    <i class="fa-solid fa-boxes-stacked"></i>
                    {{ number_format($purchases['count']) }} purchases
                </span>

                @if($purchases['due'] > 0)
                    <span class="dashboard-stat-warning">
                        {{ $currency }}{{ number_format($purchases['due'], 0) }} due
                    </span>
                @else
                    <span class="dashboard-stat-positive">Fully paid</span>
                @endif
            </div>
        </article>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <article class="dashboard-stat dashboard-stat-orange">
            <div class="dashboard-stat-top">
                <div>
                    <span class="dashboard-stat-label">Stock Health</span>
                    <strong class="dashboard-stat-value">
                        {{ number_format($inventory['stock_units'], 0) }}
                    </strong>
                </div>

                <span class="dashboard-stat-icon">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </span>
            </div>

            <div class="dashboard-stat-bottom">
                <span>
                    {{ number_format($inventory['total_medicines']) }} medicines
                </span>

                @if($inventory['low_stock'] > 0)
                    <span class="dashboard-stat-danger">
                        {{ number_format($inventory['low_stock']) }} low
                    </span>
                @else
                    <span class="dashboard-stat-positive">Healthy levels</span>
                @endif
            </div>
        </article>
    </div>

</div>
