@php
    $isEdit = isset($sale);
@endphp

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-file-invoice me-2 text-primary"></i>
            Sale Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row g-3">

            @if($isEdit)
                <div class="col-md-4">
                    <label class="form-label">Invoice Number</label>
                    <input type="text" class="form-control" value="{{ $sale->invoice_number }}" readonly>
                </div>
            @endif

            <div class="col-md-4">
                <label for="customer_id" class="form-label">Customer</label>

                <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                    <option value="">Walk-in Customer</option>

                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id', $sale->customer_id ?? '') == $customer->id)>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>

                @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="sale_date" class="form-label">
                    Sale Date <span class="text-danger">*</span>
                </label>

                <input type="date" name="sale_date" id="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ old('sale_date', isset($sale) ? $sale->sale_date?->format('Y-m-d') : now()->format('Y-m-d')) }}" required>

                @error('sale_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="doctor_name" class="form-label">Doctor Name</label>

                <input type="text" name="doctor_name" id="doctor_name" class="form-control @error('doctor_name') is-invalid @enderror" value="{{ old('doctor_name', $sale->doctor_name ?? '') }}" placeholder="Enter doctor name">

                @error('doctor_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="discount_type" class="form-label">Discount Type</label>

                <select name="discount_type" id="discount_type" class="form-select">
                    <option value="fixed" @selected(old('discount_type', strtolower($sale->discount_type ?? 'fixed')) == 'fixed')>Fixed</option>
                    <option value="percentage" @selected(old('discount_type', strtolower($sale->discount_type ?? 'fixed')) == 'percentage')>Percentage</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="discount" class="form-label">Discount</label>

                <input type="number" step="0.01" min="0" name="discount" id="discount" class="form-control" value="{{ old('discount', $sale->discount ?? 0) }}">
            </div>

            <div class="col-md-3">
                <label for="tax_type" class="form-label">Tax Type</label>

                <select name="tax_type" id="tax_type" class="form-select">
                    <option value="fixed" @selected(old('tax_type', strtolower($sale->tax_type ?? 'fixed')) == 'fixed')>Fixed</option>
                    <option value="percentage" @selected(old('tax_type', strtolower($sale->tax_type ?? 'fixed')) == 'percentage')>Percentage</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="tax" class="form-label">Tax</label>

                <input type="number" step="0.01" min="0" name="tax" id="tax" class="form-control" value="{{ old('tax', $sale->tax ?? 0) }}">
            </div>

            <div class="col-md-3">
                <label for="shipping" class="form-label">Shipping</label>

                <input type="number" step="0.01" min="0" name="shipping" id="shipping" class="form-control" value="{{ old('shipping', $sale->shipping ?? 0) }}">
            </div>

            <div class="col-md-3">
                <label for="other_charges" class="form-label">Other Charges</label>

                <input type="number" step="0.01" min="0" name="other_charges" id="other_charges" class="form-control" value="{{ old('other_charges', $sale->other_charges ?? 0) }}">
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label">
                    Status <span class="text-danger">*</span>
                </label>

                <select name="status" id="status" class="form-select" required>
                    <option value="draft" @selected(old('status', $sale->status ?? 'draft') == 'draft')>Draft</option>
                    <option value="completed" @selected(old('status', $sale->status ?? '') == 'completed')>Completed</option>
                </select>
            </div>

            <div class="col-md-8">
                <label for="notes" class="form-label">Notes</label>

                <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Optional notes">{{ old('notes', $sale->notes ?? '') }}</textarea>
            </div>

        </div>

    </div>

</div>

@include('sales.partials.sales-items')

<div class="row g-3 mt-1">

    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-note-sticky text-secondary me-2"></i>
                    Additional Notes
                </h5>
            </div>

            <div class="card-body">
                <textarea name="notes" class="form-control" rows="5" placeholder="Add any additional notes...">{{ old('notes', $sale->notes ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Payment Summary</h5>
            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong id="summarySubtotal">0.00</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Discount</span>
                    <strong id="summaryDiscount">0.00</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Tax</span>
                    <strong id="summaryTax">0.00</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping</span>
                    <strong id="summaryShipping">0.00</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Other Charges</span>
                    <strong id="summaryOtherCharges">0.00</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold">Grand Total</span>
                    <strong class="text-primary fs-5" id="summaryGrandTotal">0.00</strong>
                </div>

                <div class="mb-3">
                    <label for="paid_amount" class="form-label">Paid Amount</label>

                    <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount" class="form-control" value="{{ old('paid_amount', $sale->paid_amount ?? 0) }}">
                </div>

                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Due Amount</span>
                    <strong class="text-danger" id="summaryDueAmount">0.00</strong>
                </div>

            </div>
        </div>
    </div>

</div>