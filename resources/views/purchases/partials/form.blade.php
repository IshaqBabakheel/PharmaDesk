@csrf

<div class="row">

    {{-- Supplier --}}
    <div class="col-lg-3 mb-3">

        <label class="form-label">

            Supplier
            <span class="text-danger">*</span>

        </label>

        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>

            <option value="">Select Supplier</option>

            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchase->supplier_id ?? '') == $supplier->id)>

                    {{ $supplier->name }}

                </option>
            @endforeach

        </select>

        @error('supplier_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    {{-- Purchase Date --}}
    <div class="col-lg-3 mb-3">

        <label class="form-label">

            Purchase Date

        </label>

        <input type="date" name="purchase_date" class="form-control"
            value="{{ old('purchase_date', isset($purchase) ? $purchase->purchase_date->format('Y-m-d') : now()->format('Y-m-d')) }}">

    </div>

    {{-- Invoice --}}
    <div class="col-lg-3 mb-3">
        <label class="form-label">
            Invoice #
            <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip"
                title="Enter the invoice number from your supplier's invoice"></i>
        </label>
        <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror"
            value="{{ old('invoice_number', $purchase->invoice_number ?? '') }}" placeholder="e.g., INV-2024-001">
        <small class="text-muted">Enter the supplier's invoice number</small>
        @error('invoice_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Reference --}}
    <div class="col-lg-3 mb-3">

        <label class="form-label">
            Reference
            <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip"
                title="Automatically generated when a supplier is selected."></i>
        </label>

        <input type="text" name="reference_number" id="reference_number"
            class="form-control @error('reference_number') is-invalid @enderror"
            value="{{ old('reference_number', $purchase->reference_number ?? '') }}" placeholder="Select supplier first"
            readonly>

        <small class="text-muted">
            Auto-generated after supplier selection
        </small>

        @error('reference_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

<hr>

@include('purchases.partials.purchase-items')

<hr>

<div class="row">

    <div class="col-lg-8">

        <label class="form-label">

            Notes

        </label>

        <textarea class="form-control" rows="5" name="notes">{{ old('notes', $purchase->notes ?? '') }}</textarea>

    </div>

    <div class="col-lg-4">

        <table class="table table-bordered">

            <tr>

                <th width="55%">Subtotal</th>

                <td>

                    <input readonly id="subtotal" name="subtotal" class="form-control"
                        value="{{ old('subtotal', 0) }}">

                </td>

            </tr>

            <tr>

                <th>Discount</th>

                <td>

                    <input type="number" step="0.01" id="discount" name="discount" class="form-control"
                        value="{{ old('discount', 0) }}">

                </td>

            </tr>

            <tr>

                <th>Tax</th>

                <td>

                    <input type="number" step="0.01" id="tax" name="tax" class="form-control"
                        value="{{ old('tax', 0) }}">

                </td>

            </tr>

            <tr>

                <th>Shipping</th>

                <td>

                    <input type="number" step="0.01" id="shipping" name="shipping" class="form-control"
                        value="{{ old('shipping', 0) }}">

                </td>

            </tr>

            <tr>

                <th>Other Charges</th>

                <td>

                    <input type="number" step="0.01" id="other_charges" name="other_charges" class="form-control"
                        value="{{ old('other_charges', 0) }}">

                </td>

            </tr>

            <tr class="table-success">

                <th>Grand Total</th>

                <td>

                    <input readonly id="grand_total" name="grand_total" class="form-control fw-bold"
                        value="{{ old('grand_total', 0) }}">

                </td>

            </tr>

            <tr>

                <th>Paid Amount</th>

                <td>

                    <input type="number" step="0.01" id="paid_amount" name="paid_amount" class="form-control"
                        value="{{ old('paid_amount', $purchase->paid_amount ?? 0) }}">

                </td>

            </tr>

            <tr>

                <th>Payment Status</th>

                <td>

                    <select name="payment_status" id="payment_status" class="form-select">
                        <option value="Paid" {{ old('payment_status', $purchase->payment_status ?? '') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Partially Paid" {{ old('payment_status', $purchase->payment_status ?? '') == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="Unpaid" {{ old('payment_status', $purchase->payment_status ?? '') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>

                </td>

            </tr>

            <tr>

                <th>Due Amount</th>

                <td>

                    <input readonly id="due_amount" name="due_amount" class="form-control"
                        value="{{ old('due_amount', 0) }}">

                </td>

            </tr>

            <tr>

                <th>Status</th>

                <td>

                    <select name="status" class="form-select">
                        <option value="Draft" {{ old('status', $purchase->status ?? '') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Completed" {{ old('status', $purchase->status ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ old('status', $purchase->status ?? '') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                </td>

            </tr>

        </table>

    </div>

</div>

<hr>

<div class="text-end">

    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">

        Cancel

    </a>

    <button class="btn btn-primary" type="submit">

        <i class="fas fa-save me-1"></i>

        {{ isset($purchase) ? 'Update Purchase' : 'Save Purchase' }}

    </button>

</div>

@push('scripts')
    <script>
        $(document).on('change', 'select[name="supplier_id"]', function() {
            @if (!isset($purchase))

                const supplierId = $(this).val();

                if (!supplierId) {
                    $('#reference_number')
                        .val('')
                        .attr('placeholder', 'Select supplier first');

                    return;
                }

                $.ajax({
                    url: "{{ route('purchases.next-reference') }}",
                    type: "GET",

                    success: function(response) {

                        $('#reference_number')
                            .val(response.reference_number);

                    },

                    error: function() {

                        $('#reference_number')
                            .val('')
                            .attr(
                                'placeholder',
                                'Unable to generate reference'
                            );

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to generate purchase reference number.'
                        });

                    }

                });
            @endif

        });
    </script>
@endpush
