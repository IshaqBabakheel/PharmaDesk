@php
    $currency = 'Rs.';
@endphp

<div class="row g-3">

    <div class="col-xl-7">
        <section class="dashboard-card h-100">
            <div class="dashboard-card-header">
                <div>
                    <h2>Profit Snapshot</h2>
                    <p>{{ $periodLabel }} financial performance.</p>
                </div>

                <a href="{{ route('profit-loss.index') }}"
                   class="dashboard-link">
                    Open P&amp;L
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="profit-grid">
                <div class="profit-stat">
                    <span>Revenue</span>
                    <strong>{{ $currency }}{{ number_format($profit['revenue'], 2) }}</strong>
                </div>

                <div class="profit-stat">
                    <span>Cost of Goods</span>
                    <strong>{{ $currency }}{{ number_format($profit['cost'], 2) }}</strong>
                </div>

                <div class="profit-stat profit-stat-positive">
                    <span>Gross Profit</span>
                    <strong>{{ $currency }}{{ number_format($profit['gross_profit'], 2) }}</strong>
                </div>

                <div class="profit-stat profit-stat-danger">
                    <span>Expenses</span>
                    <strong>{{ $currency }}{{ number_format($profit['expenses'], 2) }}</strong>
                </div>
            </div>

            <div class="net-profit-row">
                <div>
                    <span>Estimated Net Profit</span>
                    <small>Gross profit less recorded expenses</small>
                </div>

                <strong class="{{ $profit['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $currency }}{{ number_format($profit['net_profit'], 2) }}
                </strong>
            </div>
        </section>
    </div>

    <div class="col-xl-5">
        <section class="dashboard-card h-100">
            <div class="dashboard-card-header">
                <div>
                    <h2>Receivables &amp; Payables</h2>
                    <p>Current outstanding account balances.</p>
                </div>

                <div class="card-icon card-icon-orange">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>

            <div class="balance-grid">
                <a href="{{ route('customers.index') }}" class="balance-box balance-receivable">
                    <span>
                        <i class="fa-solid fa-arrow-down"></i>
                        Customer Due
                    </span>

                    <strong>
                        {{ $currency }}{{ number_format($customerDue['amount'], 2) }}
                    </strong>

                    <small>
                        {{ number_format($customerDue['accounts']) }} customer accounts
                    </small>
                </a>

                <a href="{{ route('suppliers.index') }}" class="balance-box balance-payable">
                    <span>
                        <i class="fa-solid fa-arrow-up"></i>
                        Supplier Due
                    </span>

                    <strong>
                        {{ $currency }}{{ number_format($supplierDue['amount'], 2) }}
                    </strong>

                    <small>
                        {{ number_format($supplierDue['accounts']) }} supplier accounts
                    </small>
                </a>
            </div>
        </section>
    </div>

</div>
