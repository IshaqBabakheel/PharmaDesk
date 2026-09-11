@php
    $currency = 'Rs.';
    $topMax = max((float) ($topMedicines->max('quantity') ?? 1), 1);
@endphp

<div class="col-xl-5">
    <section class="dashboard-card h-100">
        <div class="dashboard-card-header">
            <div>
                <h2>Top Selling Medicines</h2>
                <p>Highest quantities sold in the selected period.</p>
            </div>

            <a href="{{ route('medicines.index') }}"
               class="dashboard-link">
                Medicines
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="top-medicines-list">
            @forelse($topMedicines as $index => $medicine)
                @php
                    $width = max(((float) $medicine->quantity / $topMax) * 100, 6);
                @endphp

                <div class="top-medicine-row">
                    <div class="top-medicine-rank">
                        {{ $index + 1 }}
                    </div>

                    <div class="top-medicine-info">
                        <div class="top-medicine-title">
                            <strong>{{ $medicine->name }}</strong>
                            <span>{{ number_format((float) $medicine->quantity, 0) }} units</span>
                        </div>

                        <div class="top-medicine-progress">
                            <div style="width: {{ $width }}%;"></div>
                        </div>

                        <small>
                            {{ $medicine->medicine_code ?: 'No code' }}
                            · {{ $currency }}{{ number_format((float) $medicine->revenue, 2) }}
                        </small>
                    </div>
                </div>
            @empty
                <div class="dashboard-empty">
                    <i class="fa-solid fa-chart-simple"></i>
                    No medicine sales found for this period.
                </div>
            @endforelse
        </div>
    </section>
</div>
