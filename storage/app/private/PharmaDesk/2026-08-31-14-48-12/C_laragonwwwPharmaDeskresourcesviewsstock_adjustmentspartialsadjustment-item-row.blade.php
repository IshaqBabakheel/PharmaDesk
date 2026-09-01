@php

    $index = $index ?? 0;
    $item = $item ?? null;

    $type = old(
        'type',
        $stockAdjustment?->type ?? ''
    );

    $medicineId = old(
        "items.$index.medicine_id",
        $item?->medicine_id ?? ''
    );

    $purchaseItemId = old(
        "items.$index.purchase_item_id",
        $item?->purchase_item_id ?? ''
    );

@endphp

<tr>

    {{-- Medicine --}}
    <td>

        <select
            name="items[{{ $index }}][medicine_id]"
            class="form-select medicine-select @error("items.$index.medicine_id") is-invalid @enderror"
            required
        >

            <option value="">
                Select Medicine
            </option>

            @foreach ($medicines as $medicine)

                <option
                    value="{{ $medicine->id }}"
                    @selected($medicineId == $medicine->id)
                >
                    {{ $medicine->name }}
                </option>

            @endforeach

        </select>

        @error("items.$index.medicine_id")

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </td>


    {{-- Batch --}}
    <td>

        <select
            name="items[{{ $index }}][purchase_item_id]"
            class="form-select batch-select @error("items.$index.purchase_item_id") is-invalid @enderror"
            data-selected-batch="{{ $purchaseItemId }}"
            {{ $type === 'increase' ? 'disabled' : '' }}
        >

            @if ($type === 'increase')

                <option value="">
                    New Adjustment Batch
                </option>

            @else

                <option value="">
                    Select Batch
                </option>

            @endif

        </select>

        @error("items.$index.purchase_item_id")

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </td>


    {{-- Expiry --}}
    <td>

        <input
            type="date"
            name="items[{{ $index }}][expiry_date]"
            class="form-control expiry-date"
            value="{{ old(
                "items.$index.expiry_date",
                $item?->expiry_date?->format('Y-m-d')
            ) }}"
            readonly
        >

    </td>


    {{-- Available --}}
    <td>

        <input
            type="number"
            class="form-control available-quantity"
            value=""
            readonly
        >

    </td>


    {{-- Quantity --}}
    <td>

        <input
            type="number"
            name="items[{{ $index }}][quantity]"
            class="form-control quantity @error("items.$index.quantity") is-invalid @enderror"
            min="1"
            value="{{ old(
                "items.$index.quantity",
                $item?->quantity ?? 1
            ) }}"
            required
        >

        @error("items.$index.quantity")

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </td>


    {{-- Purchase Price --}}
    <td>

        <input
            type="number"
            name="items[{{ $index }}][purchase_price]"
            class="form-control purchase-price"
            step="0.01"
            min="0"
            value="{{ old(
                "items.$index.purchase_price",
                $item?->purchase_price
                ?? $item?->medicine?->purchase_price
                ?? 0
            ) }}"
            readonly
        >

    </td>


    {{-- Selling Price --}}
    <td>

        <input
            type="number"
            name="items[{{ $index }}][selling_price]"
            class="form-control selling-price"
            step="0.01"
            min="0"
            value="{{ old(
                "items.$index.selling_price",
                $item?->selling_price
                ?? $item?->medicine?->selling_price
                ?? 0
            ) }}"
            readonly
        >

    </td>


    {{-- Action --}}
    <td>

        <button
            type="button"
            class="btn btn-danger btn-sm remove-adjustment-item"
        >

            <i class="fas fa-trash"></i>

        </button>

    </td>

</tr>