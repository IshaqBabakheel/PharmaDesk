@php
    $currency = 'Rs.';
    $stockTotal = max($inventory['total_medicines'], 1);
    $inStockPercent = min(($inventory['in_stock'] / $stockTotal) * 100, 100);
@endphp

<div class="row g-3 mb-3">

    <div class="col-xl-7">
        <section class="dashboard-card h-100">
            <div class="dashboard-card-header">
                <div>
                    <h2>Inventory Health</h2>
                    <p>Current medicine and stock position.</p>
                </div>

                <a href="{{ route('medicines.index') }}"
                   class="dashboard-link">
                    Manage stock
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="inventory-overview">
                <div class="inventory-number">
                    <span>Total Medicines</span>
                    <strong>{{ number_format($inventory['total_medicines']) }}</strong>
                </div>

                <div class="inventory-number">
                    <span>In Stock</span>
                    <strong class="text-success">
                        {{ number_format($inventory['in_stock']) }}
                    </strong>
                </div>

                <div class="inventory-number">
                    <span>Low Stock</span>
                    <strong class="text-warning">
                        {{ number_format($inventory['low_stock']) }}
                    </strong>
                </div>

                <div class="inventory-number">
                    <span>Out of Stock</span>
                    <strong class="text-danger">
                        {{ number_format($inventory['out_of_stock']) }}
                    </strong>
                </div>
            </div>

            <div class="inventory-progress-wrap">
                <div class="inventory-progress-head">
                    <span>Medicines currently available</span>
                    <strong>{{ number_format($inStockPercent, 1) }}%</strong>
                </div>

                <div class="inventory-progress">
                    <div class="inventory-progress-bar"
                         style="width: {{ $inStockPercent }}%;"></div>
                </div>
            </div>

            <div class="low-stock-list">
                <div class="low-stock-list-header">
                    <span>Priority low-stock items</span>
                    <a href="{{ route('medicines.index') }}">
                        View all
                    </a>
                </div>

                @forelse($alerts['low_stock'] as $medicine)
                    <div class="low-stock-row">
                        <div class="medicine-mini-icon">
                            <i class="fa-solid fa-capsules"></i>
                        </div>

                        <div class="low-stock-name">
                            <strong>{{ $medicine->name }}</strong>
                            <small>{{ $medicine->medicine_code ?: 'No code' }}</small>
                        </div>

                        <div class="low-stock-quantity">
                            <strong>{{ number_format((float) $medicine->current_stock, 0) }}</strong>
                            <small>Reorder {{ number_format((float) $medicine->reorder_level, 0) }}</small>
                        </div>
                    </div>
                @empty
                    <div class="dashboard-empty dashboard-empty-compact">
                        <i class="fa-solid fa-circle-check"></i>
                        No medicines are currently below reorder level.
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <div class="col-xl-5">
        <section class="dashboard-card h-100">
            <div class="dashboard-card-header">
                <div>
                    <h2>Expiry Overview</h2>
                    <p>Keep near-expiry stock moving safely.</p>
                </div>

                <a href="{{ route('medicines.index') }}"
                   class="dashboard-link">
                    Review
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="expiry-summary">
                <div class="expiry-tile expiry-tile-danger">
                    <strong>{{ number_format($alerts['expired_count']) }}</strong>
                    <span>Expired batches</span>
                </div>

                <div class="expiry-tile expiry-tile-warning">
                    <strong>{{ number_format($alerts['expiry_count']) }}</strong>
                    <span>Expiring soon</span>
                </div>
            </div>

            <div class="expiry-list">
                @forelse($alerts['expired_batches']->take(3) as $batch)
                    <div class="expiry-row">
                        <div class="expiry-row-icon danger">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>

                        <div class="expiry-row-content">
                            <strong>{{ $batch->medicine?->name ?? 'Medicine' }}</strong>
                            <small>
                                Batch {{ $batch->batch_number ?: 'N/A' }}
                                · Expired {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') }}
                            </small>
                        </div>

                        <span class="expiry-badge danger">Expired</span>
                    </div>
                @empty
                @endforelse

                @forelse($alerts['expiry_batches']->take(4) as $batch)
                    @php
                        $expiry = \Carbon\Carbon::parse($batch->expiry_date);
                        $days = now()->startOfDay()->diffInDays($expiry->startOfDay());
                    @endphp

                    <div class="expiry-row">
                        <div class="expiry-row-icon warning">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>

                        <div class="expiry-row-content">
                            <strong>{{ $batch->medicine?->name ?? 'Medicine' }}</strong>
                            <small>
                                Batch {{ $batch->batch_number ?: 'N/A' }}
                                · {{ $expiry->format('d M Y') }}
                                · {{ $batch->dashboard_available_quantity }} units
                            </small>
                        </div>

                        <span class="expiry-badge warning">
                            {{ $days }}d
                        </span>
                    </div>
                @empty
                    @if($alerts['expired_batches']->isEmpty())
                        <div class="dashboard-empty dashboard-empty-compact">
                            <i class="fa-solid fa-shield-heart"></i>
                            No active expiry alerts.
                        </div>
                    @endif
                @endforelse
            </div>
        </section>
    </div>
</div>
