<div class="totals">

    <div class="total-row">

        <span>
            Subtotal
        </span>

        <span>
            Rs.
            {{ number_format(
                (float) $sale->subtotal,
                2
            ) }}
        </span>

    </div>


    <div class="total-row">

        <span>
            Discount
        </span>

        <span>
            - Rs.
            {{ number_format(
                (float) $sale->discount,
                2
            ) }}
        </span>

    </div>


    <div class="total-row">

        <span>
            Tax
        </span>

        <span>
            Rs.
            {{ number_format(
                (float) $sale->tax,
                2
            ) }}
        </span>

    </div>


    @if ((float) $sale->shipping > 0)

        <div class="total-row">

            <span>
                Shipping
            </span>

            <span>
                Rs.
                {{ number_format(
                    (float) $sale->shipping,
                    2
                ) }}
            </span>

        </div>

    @endif


    @if ((float) $sale->other_charges > 0)

        <div class="total-row">

            <span>
                Other Charges
            </span>

            <span>
                Rs.
                {{ number_format(
                    (float) $sale->other_charges,
                    2
                ) }}
            </span>

        </div>

    @endif


    <div class="total-row grand-total">

        <span>
            Grand Total
        </span>

        <span>
            Rs.
            {{ number_format(
                (float) $sale->grand_total,
                2
            ) }}
        </span>

    </div>


    <div class="total-row">

        <span>
            Paid
        </span>

        <span>
            Rs.
            {{ number_format(
                (float) $sale->paid_amount,
                2
            ) }}
        </span>

    </div>


    <div class="total-row due-row">

        <span>
            Due
        </span>

        <span>
            Rs.
            {{ number_format(
                (float) $sale->due_amount,
                2
            ) }}
        </span>

    </div>

</div>