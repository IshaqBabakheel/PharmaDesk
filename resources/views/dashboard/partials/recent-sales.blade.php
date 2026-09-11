@php
    $currency = 'Rs.';
@endphp

<div class="col-xl-7">
    <section class="dashboard-card h-100">
        <div class="dashboard-card-header">
            <div>
                <h2>Recent Sales</h2>
                <p>Latest completed transactions.</p>
            </div>

            <a href="{{ route('sales.index') }}"
               class="dashboard-link">
                View all
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive dashboard-table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}"
                                   class="dashboard-table-link">
                                    {{ $sale->invoice_number }}
                                </a>
                            </td>

                            <td>
                                {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                            </td>

                            <td>
                                <strong>
                                    {{ $currency }}{{ number_format((float) $sale->grand_total, 2) }}
                                </strong>
                            </td>

                            <td>
                                @php
                                    $statusClass = match (strtolower((string) $sale->payment_status)) {
                                        'paid' => 'success',
                                        'partial' => 'warning',
                                        'partially paid' => 'warning',
                                        default => 'danger',
                                    };
                                @endphp

                                <span class="dashboard-badge dashboard-badge-{{ $statusClass }}">
                                    {{ ucfirst($sale->payment_status ?: 'Due') }}
                                </span>
                            </td>

                            <td class="dashboard-muted">
                                {{ $sale->sale_date?->format('d M, h:i A') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="dashboard-empty table-empty">
                                    <i class="fa-solid fa-receipt"></i>
                                    No completed sales found.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
