@php

    /*
    |--------------------------------------------------------------------------
    | Previously Returned Quantities
    |--------------------------------------------------------------------------
    */

    $returnedQuantity = (int) (
        $item->returned_quantity ?? 0
    );

    $returnedFreeQuantity = (int) (
        $item->returned_free_quantity ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | Remaining quantities
    |--------------------------------------------------------------------------
    |
    | Sold - Already Returned
    |
    | Example:
    |
    | Sold = 2
    | Returned = 2
    | Remaining = 0
    |
    */

    $maxReturnQuantity = max(
        0,
        (int) $item->quantity - $returnedQuantity
    );

    $maxReturnFreeQuantity = max(
        0,
        (int) ($item->free_quantity ?? 0)
        - $returnedFreeQuantity
    );


    /*
    |--------------------------------------------------------------------------
    | Input values
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Default is always 0.
    |
    | This is an EDIT page, but the existing returned quantity is NOT
    | the new quantity being returned.
    |
    | It is displayed separately in the "Previously Returned" column.
    |
    | If validation fails, old() restores what the user submitted.
    |
    */

    $inputQuantity = old(
        "items.$index.quantity",
        0
    );

    $inputFreeQuantity = old(
        "items.$index.free_quantity",
        0
    );

@endphp


<tr
    data-sale-item-id="{{ $item->id }}"
    data-max-quantity="{{ $maxReturnQuantity }}"
    data-max-free-quantity="{{ $maxReturnFreeQuantity }}"
>

    {{-- ========================================================= --}}
    {{-- Medicine --}}
    {{-- ========================================================= --}}

    <td>

        <strong>
            {{ $item->medicine?->name ?? '-' }}
        </strong>

        <input
            type="hidden"
            name="items[{{ $index }}][sale_item_id]"
            value="{{ $item->id }}"
        >

    </td>


    {{-- ========================================================= --}}
    {{-- Batch --}}
    {{-- ========================================================= --}}

    <td>

        {{ $item->batch_number ?? '-' }}

    </td>


    {{-- ========================================================= --}}
    {{-- Expiry --}}
    {{-- ========================================================= --}}

    <td>

        {{ $item->expiry_date
            ? \Carbon\Carbon::parse($item->expiry_date)->format('d M Y')
            : '-'
        }}

    </td>


    {{-- ========================================================= --}}
    {{-- Sold Quantity --}}
    {{-- ========================================================= --}}

    <td>

        <strong>
            {{ number_format($item->quantity) }}
        </strong>

    </td>


    {{-- ========================================================= --}}
    {{-- Previously Returned Quantity --}}
    {{-- ========================================================= --}}

    <td>

        <strong class="{{ $returnedQuantity > 0 ? 'text-danger' : '' }}">

            {{ number_format($returnedQuantity) }}

        </strong>

    </td>


    {{-- ========================================================= --}}
    {{-- Return Quantity --}}
    {{-- ========================================================= --}}

    <td>

        <input
            type="number"
            name="items[{{ $index }}][quantity]"
            class="form-control return-quantity
                @error("items.$index.quantity")
                    is-invalid
                @enderror"
            min="0"
            max="{{ $maxReturnQuantity }}"
            value="{{ $inputQuantity }}"
            data-price="{{ $item->selling_price }}"
            data-max="{{ $maxReturnQuantity }}"
            @if($maxReturnQuantity === 0)
                readonly
            @endif
        >

        <small class="text-muted d-block mt-1">

            Max:
            <strong>
                {{ number_format($maxReturnQuantity) }}
            </strong>

        </small>

        @error("items.$index.quantity")

            <div class="invalid-feedback d-block">

                {{ $message }}

            </div>

        @enderror

    </td>


    {{-- ========================================================= --}}
    {{-- Free Sold --}}
    {{-- ========================================================= --}}

    <td>

        {{ number_format($item->free_quantity ?? 0) }}

    </td>


    {{-- ========================================================= --}}
    {{-- Previously Returned Free --}}
    {{-- ========================================================= --}}

    <td>

        <strong class="{{ $returnedFreeQuantity > 0 ? 'text-danger' : '' }}">

            {{ number_format($returnedFreeQuantity) }}

        </strong>

    </td>


    {{-- ========================================================= --}}
    {{-- Return Free Quantity --}}
    {{-- ========================================================= --}}

    <td>

        <input
            type="number"
            name="items[{{ $index }}][free_quantity]"
            class="form-control return-free-quantity
                @error("items.$index.free_quantity")
                    is-invalid
                @enderror"
            min="0"
            max="{{ $maxReturnFreeQuantity }}"
            value="{{ $inputFreeQuantity }}"
            data-max="{{ $maxReturnFreeQuantity }}"
            @if($maxReturnFreeQuantity === 0)
                readonly
            @endif
        >

        <small class="text-muted d-block mt-1">

            Max:
            <strong>
                {{ number_format($maxReturnFreeQuantity) }}
            </strong>

        </small>

        @error("items.$index.free_quantity")

            <div class="invalid-feedback d-block">

                {{ $message }}

            </div>

        @enderror

    </td>


    {{-- ========================================================= --}}
    {{-- Selling Price --}}
    {{-- ========================================================= --}}

    <td>

        {{ number_format(
            (float) $item->selling_price,
            2
        ) }}

    </td>


    {{-- ========================================================= --}}
    {{-- Current Return Total --}}
    {{-- ========================================================= --}}

    <td class="item-total">

        {{ number_format(
            (float) $inputQuantity *
            (float) $item->selling_price,
            2
        ) }}

    </td>

</tr>