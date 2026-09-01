@php

    $index = $index ?? 0;
    $item = $item ?? null;

    $isEdit = $item !== null;

    $purchaseItem = $item?->purchaseItem;


    /*
    |--------------------------------------------------------------------------
    | Batch
    |--------------------------------------------------------------------------
    */

    $batchNumber =
        $purchaseItem?->batch_number
        ?? $item?->batch_number
        ?? '';


    /*
    |--------------------------------------------------------------------------
    | Expiry
    |--------------------------------------------------------------------------
    */

    $expiryDate =
        $purchaseItem?->expiry_date
        ?? $item?->expiry_date
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | Selected Medicine
    |--------------------------------------------------------------------------
    */

    $selectedMedicineId =
        old(
            "items.$index.medicine_id",
            $item?->medicine_id ?? ''
        );

@endphp


<tr>

    {{-- Medicine --}}
    <td>

        <input
            type="hidden"
            name="items[{{ $index }}][medicine_id]"
            class="medicine-id"
            value="{{ $selectedMedicineId }}"
        >


        @if ($isEdit)

            {{-- Existing medicine --}}
            <strong>
                {{ $item?->medicine?->name ?? '-' }}
            </strong>

        @else

            {{-- Create: Medicine selection --}}
            <select
                class="form-select medicine-select"
                data-index="{{ $index }}"
            >

                <option value="">
                    Select Medicine
                </option>

                @foreach ($medicines as $medicine)

                    <option
                        value="{{ $medicine->id }}"
                        @selected(
                            $selectedMedicineId == $medicine->id
                        )
                    >
                        {{ $medicine->name }}
                    </option>

                @endforeach

            </select>

        @endif

    </td>


    {{-- Batch --}}
    <td>

        <input
            type="text"
            name="items[{{ $index }}][batch_number]"
            class="form-control batch-number"
            value="{{ $batchNumber }}"
            readonly
        >

    </td>


    {{-- Expiry --}}
    <td>

        <input
            type="date"
            name="items[{{ $index }}][expiry_date]"
            class="form-control expiry-date"
            value="{{
                $expiryDate
                    ? \Carbon\Carbon::parse($expiryDate)->format('Y-m-d')
                    : ''
            }}"
            readonly
        >

    </td>


    {{-- Available Stock --}}
    <td>

        <input
            type="number"
            class="form-control available-stock"
            value="{{ $item?->medicine?->current_stock ?? 0 }}"
            readonly
        >

    </td>


    {{-- Quantity --}}
    <td>

        <input
            type="number"
            min="1"
            name="items[{{ $index }}][quantity]"
            class="form-control quantity"
            value="{{
                old(
                    "items.$index.quantity",
                    $item?->quantity ?? 1
                )
            }}"
        >

    </td>


    {{-- Free Quantity --}}
    <td>

        <input
            type="number"
            min="0"
            name="items[{{ $index }}][free_quantity]"
            class="form-control free-quantity"
            value="{{
                old(
                    "items.$index.free_quantity",
                    $item?->free_quantity ?? 0
                )
            }}"
        >

    </td>


    {{-- Selling Price --}}
    <td>

        <input
            type="number"
            step="0.01"
            min="0.01"
            name="items[{{ $index }}][selling_price]"
            class="form-control selling-price"
            value="{{
                old(
                    "items.$index.selling_price",
                    $item?->selling_price ?? ''
                )
            }}"
            readonly
        >

        <input
            type="hidden"
            name="items[{{ $index }}][purchase_price]"
            class="purchase-price"
            value=""
        >
    </td>


    {{-- Discount --}}
    <td>

        <input
            type="number"
            step="0.01"
            min="0"
            name="items[{{ $index }}][discount]"
            class="form-control item-discount"
            value="{{
                old(
                    "items.$index.discount",
                    $item?->discount ?? 0
                )
            }}"
        >

    </td>


    {{-- Tax --}}
    <td>

        <input
            type="number"
            step="0.01"
            min="0"
            name="items[{{ $index }}][tax]"
            class="form-control item-tax"
            value="{{
                old(
                    "items.$index.tax",
                    $item?->tax ?? 0
                )
            }}"
        >

    </td>


    {{-- Total --}}
    <td>

        <input
            type="number"
            step="0.01"
            name="items[{{ $index }}][total]"
            class="form-control item-total"
            value="{{
                old(
                    "items.$index.total",
                    $item?->total ?? 0
                )
            }}"
            readonly
        >

    </td>


    {{-- Action --}}
    <td>

        <button
            type="button"
            class="btn btn-sm btn-danger remove-sale-item"
        >

            <i class="fas fa-trash"></i>

        </button>

    </td>

</tr>