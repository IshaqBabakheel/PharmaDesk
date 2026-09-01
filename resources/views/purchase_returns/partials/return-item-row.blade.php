<tr>

    <td>

        <input
            type="hidden"
            name="items[{{ $index }}][purchase_item_id]"
            class="purchase_item_id"
            value="{{ $item->purchase_item_id }}"
        >

        <input
            type="hidden"
            name="items[{{ $index }}][medicine_id]"
            class="medicine_id"
            value="{{ $item->medicine_id }}"
        >

        <select
            class="form-select medicine-select"
        >

            <option
                value="{{ $item->purchase_item_id }}"
                selected
            >

                {{ $item->medicine->name }}
                ({{ $item->batch_number }})

            </option>

        </select>

    </td>

    <td>

        <input
            type="text"
            class="form-control batch_number"
            name="items[{{ $index }}][batch_number]"
            value="{{ $item->batch_number }}"
            readonly
        >

    </td>

    <td>

        <input
            type="date"
            class="form-control expiry_date"
            name="items[{{ $index }}][expiry_date]"
            value="{{ optional($item->expiry_date)->format('Y-m-d') }}"
            readonly
        >

    </td>

    <td>

        <input
            type="number"
            class="form-control purchased"
            value="{{ $item->purchaseItem->quantity }}"
            readonly
        >

    </td>

    <td>

        <input
            type="number"
            class="form-control returned"
            value="{{ $item->purchaseItem->returned_quantity ?? 0 }}"
            readonly
        >

    </td>

    <td>

        <input
            type="number"
            class="form-control remaining"
            value="{{ $item->purchaseItem->quantity - ($item->purchaseItem->returned_quantity ?? 0) + $item->quantity }}"
            readonly
        >

    </td>

    <td>

        <input
            type="number"
            min="1"
            class="form-control quantity"
            name="items[{{ $index }}][quantity]"
            value="{{ $item->quantity }}"
        >

    </td>

    <td>

        <input
            type="number"
            step="0.01"
            class="form-control purchase_price"
            name="items[{{ $index }}][purchase_price]"
            value="{{ $item->purchase_price }}"
            readonly
        >

    </td>

    <td>

        <input
            type="number"
            step="0.01"
            class="form-control total"
            name="items[{{ $index }}][total]"
            value="{{ $item->total }}"
            readonly
        >

    </td>

    <td>

        <button
            type="button"
            class="btn btn-danger btn-sm removeRow"
        >

            <i class="fas fa-trash"></i>

        </button>

    </td>

</tr>