<div class="customer-section">

    <div>
        <strong>Customer:</strong>

        {{ $customer?->name ?? 'Walk-in Customer' }}
    </div>

    @if ($customer?->phone)

        <div>
            <strong>Phone:</strong>
            {{ $customer->phone }}
        </div>

    @endif

</div>