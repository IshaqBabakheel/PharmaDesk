<div class="card shadow-sm border-0 mt-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-pills text-primary me-2"></i>
            Sale Medicines
        </h5>

        @if(!isset($sale))
            <button type="button" class="btn btn-sm btn-primary" id="addSaleItem">
                <i class="fas fa-plus me-1"></i>
                Add Medicine
            </button>
        @endif

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered align-middle mb-0" id="saleItemsTable">

                <thead class="table-light">

                    <tr>
                        <th width="18%">Medicine</th>
                        <th width="20%">Batch</th>
                        <th width="10%">Expiry</th>
                        <th width="8%">Available</th>
                        <th width="8%">Quantity</th>
                        <th width="20%">Free</th>
                        <th width="10%">Selling Price</th>
                        <th width="10%">Discount</th>
                        <th width="10%">Tax</th>
                        <th width="10%">Total</th>
                        <th width="5%">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @if(isset($sale) && $sale->items->count())

                        @foreach($sale->items as $index => $item)
                            @include('sales.partials.sale-item-row', [
                                'index' => $index,
                                'item' => $item
                            ])
                        @endforeach

                    @else

                        @include('sales.partials.sale-item-row', [
                            'index' => 0,
                            'item' => null
                        ])

                    @endif

                </tbody>

            </table>

        </div>

    </div>

</div>

@push('scripts')
<script>
let saleItemIndex = {{ isset($sale) ? $sale->items->count() : 1 }};

const medicines = @json($medicines);

function medicineOptions(selected = '') {
    let html = '<option value="">Select Medicine</option>';

    medicines.forEach(function (medicine) {
        html += `<option value="${medicine.id}" ${selected == medicine.id ? 'selected' : ''}>${medicine.name}</option>`;
    });

    return html;
}

function addSaleItem() {
    const index = saleItemIndex++;

    const row = `
        <tr>
            <td>
                <input type="hidden" name="items[${index}][medicine_id]" class="medicine-id">

                <select class="form-select medicine-select" data-index="${index}">
                    ${medicineOptions()}
                </select>
            </td>

            <td>
                <input type="text" name="items[${index}][batch_number]" class="form-control batch-number" readonly>
            </td>

            <td>
                <input type="date" name="items[${index}][expiry_date]" class="form-control expiry-date" readonly>
            </td>

            <td>
                <input type="number" class="form-control available-stock" readonly>
            </td>

            <td>
                <input type="number" min="1" name="items[${index}][quantity]" class="form-control quantity" value="1">
            </td>

            <td>
                <input type="number" min="0" name="items[${index}][free_quantity]" class="form-control free-quantity" value="0">
            </td>

            <td>
                <input type="number" step="0.01" min="0.01" name="items[${index}][selling_price]" class="form-control selling-price">
            </td>

            <td>
                <input type="number" step="0.01" min="0" name="items[${index}][discount]" class="form-control item-discount" value="0">
            </td>

            <td>
                <input type="number" step="0.01" min="0" name="items[${index}][tax]" class="form-control item-tax" value="0">
            </td>

            <td>
                <input type="number" step="0.01" name="items[${index}][total]" class="form-control item-total" readonly>
            </td>

            <td>
                <button type="button" class="btn btn-sm btn-danger remove-sale-item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#saleItemsTable tbody').append(row);
}

$('#addSaleItem').on('click', function () {
    addSaleItem();
});

$(document).on('click', '.remove-sale-item', function () {
    if ($('#saleItemsTable tbody tr').length <= 1) {
        toastr.warning('At least one medicine is required.');
        return;
    }

    $(this).closest('tr').remove();
    calculateSaleTotals();
});

$(document).on('change', '.medicine-select', function () {
    const medicineId = $(this).val();
    const row = $(this).closest('tr');
    const medicine = medicines.find(item => item.id == medicineId);
    if (!medicine) {
        row.find('.medicine-id').val('');
        row.find('.batch-number').val('');
        row.find('.expiry-date').val('');
        row.find('.available-stock').val('');
        row.find('.selling-price').val('');
        calculateRowTotal(row);
        return;
    }

    row.find('.medicine-id').val(medicine.id);
    row.find('.selling-price').val(medicine.selling_price ?? 0);
    row.find('.purchase-price').val(medicine.purchase_price ?? 0);

    loadMedicineStock(row, medicine.id);
});

function loadMedicineStock(row, medicineId) {
    $.get("{{ url('sales') }}/medicine/" + medicineId + "/stock", function (response) {
        row.find('.available-stock').val(response.current_stock ?? 0);
        row.find('.batch-number').val(response.batch_number ?? '');
        row.find('.expiry-date').val(response.expiry_date ?? '');
        calculateRowTotal(row);
    }).fail(function () {
        row.find('.available-stock').val(10);
        row.find('.batch-number').val('');
        row.find('.expiry-date').val('');
        calculateRowTotal(row);
    });
}

$(document).on('input change', '.quantity, .free-quantity, .selling-price, .item-discount, .item-tax', function () {
    calculateRowTotal($(this).closest('tr'));
});

function calculateRowTotal(row) {
    const quantity = parseFloat(row.find('.quantity').val()) || 0;
    const price = parseFloat(row.find('.selling-price').val()) || 0;
    const discount = parseFloat(row.find('.item-discount').val()) || 0;
    const tax = parseFloat(row.find('.item-tax').val()) || 0;

    const subtotal = quantity * price;
    const total = Math.max(subtotal - discount + tax, 0);

    row.find('.item-total').val(total.toFixed(2));

    calculateSaleTotals();
}

function calculateSaleTotals() {
    let subtotal = 0;

    $('.item-total').each(function () {
        const rowTotal = parseFloat($(this).val()) || 0;
        subtotal += rowTotal;
    });

    const discount = parseFloat($('#discount').val()) || 0;
    const tax = parseFloat($('#tax').val()) || 0;
    const shipping = parseFloat($('#shipping').val()) || 0;
    const otherCharges = parseFloat($('#other_charges').val()) || 0;

    let discountAmount = discount;

    if ($('#discount_type').val() === 'percentage') {
        discountAmount = subtotal * discount / 100;
    }

    let taxAmount = tax;

    if ($('#tax_type').val() === 'percentage') {
        taxAmount = subtotal * tax / 100;
    }

    const grandTotal = Math.max(
        subtotal - discountAmount + taxAmount + shipping + otherCharges,
        0
    );

    const paid = parseFloat($('#paid_amount').val()) || 0;
    const due = Math.max(grandTotal - paid, 0);

    $('#summarySubtotal').text(subtotal.toFixed(2));
    $('#summaryDiscount').text(discountAmount.toFixed(2));
    $('#summaryTax').text(taxAmount.toFixed(2));
    $('#summaryShipping').text(shipping.toFixed(2));
    $('#summaryOtherCharges').text(otherCharges.toFixed(2));
    $('#summaryGrandTotal').text(grandTotal.toFixed(2));
    $('#summaryDueAmount').text(due.toFixed(2));

    if (!$('#subtotal').length) {
        $('#saleForm').append(`<input type="hidden" name="subtotal" id="subtotal">`);
        $('#saleForm').append(`<input type="hidden" name="grand_total" id="grand_total">`);
        $('#saleForm').append(`<input type="hidden" name="due_amount" id="due_amount">`);
    }

    $('#subtotal').val(subtotal.toFixed(2));
    $('#grand_total').val(grandTotal.toFixed(2));
    $('#due_amount').val(due.toFixed(2));
}

$('#discount, #tax, #shipping, #other_charges, #paid_amount, #discount_type, #tax_type').on('input change', function () {
    calculateSaleTotals();
});

$(function () {
    $('.medicine-select').each(function () {
        if ($(this).val()) {
            $(this).trigger('change');
        }
    });

    calculateSaleTotals();
});
</script>
@endpush