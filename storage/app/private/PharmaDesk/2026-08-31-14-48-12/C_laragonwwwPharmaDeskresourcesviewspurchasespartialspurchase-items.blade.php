<div class="card shadow-sm border-0 mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-pills me-2"></i>
            Purchase Items
        </h5>
        <button
            type="button"
            class="btn btn-primary btn-sm"
            id="addRow"
        >
            <i class="fas fa-plus me-1"></i>
            Add Medicine
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-large" id="purchaseItemsTable">
                <thead class="table-light">
                    <tr>
                        <th width="25%">Medicine</th>
                        <th width="12%">Batch</th>
                        <th width="10%">Expiry</th>
                        <th width="7%">Qty</th>
                        <th width="7%">Free</th>
                        <th width="10%">Purchase</th>
                        <th width="10%">Selling</th>
                        <th width="7%">Disc.</th>
                        <th width="7%">Tax</th>
                        <th width="10%">Total</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Items will be populated by JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<template id="purchaseItemTemplate">

    <tr>

        {{-- Medicine --}}
        <td>

            <select
                name="items[__INDEX__][medicine_id]"
                class="form-select medicine-select"
                required
            >

                <option value="">
                    Select Medicine
                </option>

                @foreach($medicines as $medicine)

                    <option
                        value="{{ $medicine->id }}"
                        data-purchase="{{ $medicine->purchase_price }}"
                        data-selling="{{ $medicine->selling_price }}"
                    >
                        {{ $medicine->name }}
                    </option>

                @endforeach

            </select>

        </td>


        {{-- Source --}}
        <td class="d-none">

            <input
                type="hidden"
                name="items[__INDEX__][source]"
                value="purchase"
                class="source"
            >

        </td>


        {{-- Batch --}}
        <td>

            <input
                type="text"
                name="items[__INDEX__][batch_number]"
                class="form-control batch"
                readonly
            >

        </td>


        {{-- Expiry --}}
        <td>

            <input
                type="date"
                name="items[__INDEX__][expiry_date]"
                class="form-control"
            >

        </td>


        {{-- Quantity --}}
        <td>

            <input
                type="number"
                min="1"
                value="1"
                name="items[__INDEX__][quantity]"
                class="form-control quantity calculate"
                required
            >

        </td>


        {{-- Free --}}
        <td>

            <input
                type="number"
                min="0"
                value="0"
                name="items[__INDEX__][free_quantity]"
                class="form-control"
            >

        </td>


        {{-- Purchase Price --}}
        <td>

            <input
                type="number"
                step="0.01"
                min="0"
                value="0"
                name="items[__INDEX__][purchase_price]"
                class="form-control purchase-price calculate"
                required
            >

        </td>


        {{-- Selling Price --}}
        <td>

            <input
                type="number"
                step="0.01"
                min="0"
                value="0"
                name="items[__INDEX__][selling_price]"
                class="form-control selling-price"
                required
            >

        </td>


        {{-- Discount --}}
        <td>

            <input
                type="number"
                step="0.01"
                min="0"
                value="0"
                name="items[__INDEX__][discount]"
                class="form-control discount calculate"
            >

        </td>


        {{-- Tax --}}
        <td>

            <input
                type="number"
                step="0.01"
                min="0"
                value="0"
                name="items[__INDEX__][tax]"
                class="form-control tax calculate"
            >

        </td>


        {{-- Total --}}
        <td>

            <input
                type="number"
                step="0.01"
                value="0"
                readonly
                name="items[__INDEX__][total]"
                class="form-control total bg-light"
            >

        </td>


        {{-- Action --}}
        <td class="text-center">

            <button
                type="button"
                class="btn btn-danger btn-sm remove-row"
            >

                <i class="fas fa-trash"></i>

            </button>

        </td>

    </tr>

</template>

@push('scripts')
<script>
    let purchaseRowIndex = 0;

    $(document).ready(function() {
        // Load existing items
        @if(isset($purchase) && $purchase->items->isNotEmpty())
            @foreach($purchase->items as $item)
                addRowWithData({
                    medicine_id: {{ $item->medicine_id }},
                    source: @json($item->source ?? 'purchase'),
                    batch_number: '{{ $item->batch_number }}',
                    expiry_date: '{{ $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '' }}',
                    quantity: {{ $item->quantity }},
                    free_quantity: {{ $item->free_quantity }},
                    purchase_price: {{ $item->purchase_price }},
                    selling_price: {{ $item->selling_price }},
                    discount: {{ $item->discount ?? 0 }},
                    tax: {{ $item->tax ?? 0 }},
                    total: {{ $item->total }}
                });
            @endforeach
        @else
            // If no items, add one empty row
            addRow();
        @endif

        // Calculate grand total after loading
        setTimeout(calculateGrandTotal, 200);
    });

    /*
    |--------------------------------------------------------------------------
    | Add Row with Data
    |--------------------------------------------------------------------------
    */
    function addRowWithData(data) {
        let html = $('#purchaseItemTemplate').html();
        html = html.replaceAll('__INDEX__', purchaseRowIndex);
        
        let $row = $(html);
        
        // Set values
        $row.find('.medicine-select').val(data.medicine_id);
        $row.find('.source').val(data.source ?? 'purchase');
        $row.find('.batch').val(data.batch_number);
        $row.find('input[type="date"]').val(data.expiry_date);
        $row.find('.quantity').val(data.quantity);
        $row.find('input[name$="[free_quantity]"]').val(data.free_quantity);
        $row.find('.purchase-price').val(data.purchase_price);
        $row.find('.selling-price').val(data.selling_price);
        $row.find('.discount').val(data.discount);
        $row.find('.tax').val(data.tax);
        $row.find('.total').val(data.total.toFixed(2));
        
        $('#purchaseItemsTable tbody').append($row);
        purchaseRowIndex++;
        
        calculateRow($row);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Row
    |--------------------------------------------------------------------------
    */
    function addRow() {
        let html = $('#purchaseItemTemplate').html();
        html = html.replaceAll('__INDEX__', purchaseRowIndex);
        $('#purchaseItemsTable tbody').append(html);
        purchaseRowIndex++;
    }

    $('#addRow').on('click', addRow);

    /*
    |--------------------------------------------------------------------------
    | Remove Row
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.remove-row', function() {
        if ($('#purchaseItemsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Remove',
                text: 'At least one item is required.',
                confirmButtonColor: '#ffc107'
            });
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Medicine Selected
    |--------------------------------------------------------------------------
    */
    $(document).on('change', '.medicine-select', function () {

        const option = $(this).find(':selected');
        const row = $(this).closest('tr');

        const medicineId = $(this).val();

        if (!medicineId) {

            row.find('.batch').val('');
            row.find('.purchase-price').val(0);
            row.find('.selling-price').val(0);

            calculateRow(row);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Generate batch number
        |--------------------------------------------------------------------------
        */

        row.find('.batch').val(
            generateBatchNumber()
        );

        /*
        |--------------------------------------------------------------------------
        | Set prices
        |--------------------------------------------------------------------------
        */

        row.find('.purchase-price').val(
            option.data('purchase') ?? 0
        );

        row.find('.selling-price').val(
            option.data('selling') ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Normal purchase source
        |--------------------------------------------------------------------------
        */

        row.find('.source').val('purchase');

        calculateRow(row);
    });

    /*
    |--------------------------------------------------------------------------
    | Auto Calculate Row
    |--------------------------------------------------------------------------
    */
    $(document).on('keyup change', '.calculate', function() {
        calculateRow($(this).closest('tr'));
    });

    function calculateRow(row) {
        let qty = parseFloat(row.find('.quantity').val()) || 0;
        let purchase = parseFloat(row.find('.purchase-price').val()) || 0;
        let discount = parseFloat(row.find('.discount').val()) || 0;
        let tax = parseFloat(row.find('.tax').val()) || 0;

        let total = (qty * purchase) - discount + tax;
        row.find('.total').val(total.toFixed(2));

        calculateGrandTotal();
    }

    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */
    $('#discount,#tax,#shipping,#other_charges,#paid_amount')
        .on('keyup change', function() {
            calculateGrandTotal();
        });

    function calculateGrandTotal() {
        let subtotal = 0;
        $('.total').each(function() {
            subtotal += parseFloat($(this).val()) || 0;
        });

        $('#subtotal').val(subtotal.toFixed(2));

        let discount = parseFloat($('#discount').val()) || 0;
        let tax = parseFloat($('#tax').val()) || 0;
        let shipping = parseFloat($('#shipping').val()) || 0;
        let other = parseFloat($('#other_charges').val()) || 0;

        let grandTotal = subtotal - discount + tax + shipping + other;
        $('#grand_total').val(grandTotal.toFixed(2));

        let paid = parseFloat($('#paid_amount').val()) || 0;
        let due = grandTotal - paid;
        $('#due_amount').val(due.toFixed(2));

        // Payment Status
        if (due <= 0) {
            $('#payment_status').val('Paid');
        } else if (paid > 0) {
            $('#payment_status').val('Partially Paid');
        } else {
            $('#payment_status').val('Unpaid');
        }
    }

    // generate batch number
    function generateBatchNumber() {

        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        let lettersPart = '';

        for (let i = 0; i < 4; i++) {
            lettersPart +=
                letters.charAt(
                    Math.floor(
                        Math.random() * letters.length
                    )
                );
        }

        const numbersPart =
            Math.floor(
                100000 + Math.random() * 900000
            );

        return `BT${numbersPart}${lettersPart}`;
    }
</script>
@endpush