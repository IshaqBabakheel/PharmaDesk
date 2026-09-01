<div class="payment-section">

    <h4>
        Payment Information
    </h4>

    @forelse ($payments as $payment)

        <div class="payment-row">

            <div>

                {{ $payment->payment_number }}

                <small>
                    {{ $payment->payment_date?->format('d M Y') }}
                </small>

            </div>

            <div>

                {{ ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $payment->method
                    )
                ) }}

            </div>

            <div class="text-end">

                Rs.
                {{ number_format(
                    (float) $payment->amount,
                    2
                ) }}

            </div>

        </div>

    @empty

        <div class="text-muted">
            No payment recorded.
        </div>

    @endforelse

</div>