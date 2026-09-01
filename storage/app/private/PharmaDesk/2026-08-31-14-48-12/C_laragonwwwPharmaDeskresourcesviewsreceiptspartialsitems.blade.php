<table class="items-table">

    <thead>

        <tr>

            <th>#</th>

            <th>
                Medicine
            </th>

            <th>
                Batch
            </th>

            <th>
                Qty
            </th>

            <th>
                Price
            </th>

            <th>
                Discount
            </th>

            <th>
                Tax
            </th>

            <th>
                Total
            </th>

        </tr>

    </thead>

    <tbody>

        @foreach ($items as $index => $item)

            @php
                $batch =
                    $item->purchaseItem?->batch_number
                    ?? $item->batch_number
                    ?? '-';

                $quantity =
                    (int) $item->quantity;

                $freeQuantity =
                    (int) ($item->free_quantity ?? 0);
            @endphp

            <tr>

                <td>
                    {{ $index + 1 }}
                </td>

                <td>

                    <strong>
                        {{ $item->medicine?->name ?? '-' }}
                    </strong>

                    @if ($freeQuantity > 0)

                        <small>
                            +{{ $freeQuantity }} free
                        </small>

                    @endif

                </td>

                <td>
                    {{ $batch }}
                </td>

                <td>
                    {{ $quantity }}
                </td>

                <td class="text-end">
                    {{ number_format(
                        (float) $item->selling_price,
                        2
                    ) }}
                </td>

                <td class="text-end">
                    {{ number_format(
                        (float) $item->discount,
                        2
                    ) }}
                </td>

                <td class="text-end">
                    {{ number_format(
                        (float) $item->tax,
                        2
                    ) }}
                </td>

                <td class="text-end">
                    <strong>
                        {{ number_format(
                            (float) $item->total,
                            2
                        ) }}
                    </strong>
                </td>

            </tr>

        @endforeach

    </tbody>

</table>