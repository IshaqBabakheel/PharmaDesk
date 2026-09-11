@php
    $alertTotal =
        $alerts['low_stock_count']
        + $alerts['expiry_count']
        + $alerts['expired_count']
        + $customerDue['accounts']
        + $supplierDue['accounts'];
@endphp

<section class="dashboard-card mb-3 attention-card">
    <div class="dashboard-card-header">
        <div>
            <h2>Attention Required</h2>
            <p>Items that may need action from the pharmacy team.</p>
        </div>

        <span class="attention-count {{ $alertTotal > 0 ? 'has-alerts' : '' }}">
            {{ number_format($alertTotal) }}
            {{ $alertTotal === 1 ? 'item' : 'items' }}
        </span>
    </div>

    @if($alertTotal === 0)
        <div class="dashboard-empty dashboard-empty-success">
            <div class="dashboard-empty-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div>
                <strong>Everything looks good.</strong>
                <span>No urgent stock, expiry or account alerts require attention.</span>
            </div>
        </div>
    @else
        <div class="attention-grid">

            <a href="{{ route('medicines.index') }}"
               class="attention-item attention-danger">
                <span class="attention-item-icon">
                    <i class="fa-solid fa-box-open"></i>
                </span>

                <span class="attention-item-content">
                    <strong>{{ number_format($alerts['low_stock_count']) }}</strong>
                    <small>Low stock medicines</small>
                </span>

                <i class="fa-solid fa-chevron-right attention-arrow"></i>
            </a>

            <a href="{{ route('medicines.index') }}"
               class="attention-item attention-warning">
                <span class="attention-item-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </span>

                <span class="attention-item-content">
                    <strong>{{ number_format($alerts['expiry_count']) }}</strong>
                    <small>Expiring within {{ (int) env('NOTIFICATION_EXPIRY_DAYS', 30) }} days</small>
                </span>

                <i class="fa-solid fa-chevron-right attention-arrow"></i>
            </a>

            <a href="{{ route('medicines.index') }}"
               class="attention-item attention-danger">
                <span class="attention-item-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>

                <span class="attention-item-content">
                    <strong>{{ number_format($alerts['expired_count']) }}</strong>
                    <small>Expired stock batches</small>
                </span>

                <i class="fa-solid fa-chevron-right attention-arrow"></i>
            </a>

            <a href="{{ route('customers.index') }}"
               class="attention-item attention-info">
                <span class="attention-item-icon">
                    <i class="fa-solid fa-user-clock"></i>
                </span>

                <span class="attention-item-content">
                    <strong>{{ number_format($customerDue['accounts']) }}</strong>
                    <small>Customers with outstanding dues</small>
                </span>

                <i class="fa-solid fa-chevron-right attention-arrow"></i>
            </a>

            <a href="{{ route('suppliers.index') }}"
               class="attention-item attention-purple">
                <span class="attention-item-icon">
                    <i class="fa-solid fa-truck-clock"></i>
                </span>

                <span class="attention-item-content">
                    <strong>{{ number_format($supplierDue['accounts']) }}</strong>
                    <small>Suppliers with outstanding balances</small>
                </span>

                <i class="fa-solid fa-chevron-right attention-arrow"></i>
            </a>

        </div>
    @endif
</section>
