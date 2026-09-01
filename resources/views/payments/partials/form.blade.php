@php
    $type = old('type', $payment->type ?? 'receipt');
@endphp

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
            Payment Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">
                    Payment Type <span class="text-danger">*</span>
                </label>

                <select
                    name="type"
                    id="paymentType"
                    class="form-select @error('type') is-invalid @enderror"
                    required
                >
                    <option value="receipt" @selected($type === 'receipt')}>
                        Customer Receipt
                    </option>

                    <option value="payment" @selected($type === 'payment')}>
                        Supplier Payment
                    </option>
                </select>

                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-4 receipt-field">
                <label class="form-label">
                    Sale <span class="text-danger">*</span>
                </label>

                <select
                    name="sale_id"
                    id="saleId"
                    class="form-select @error('sale_id') is-invalid @enderror"
                >
                    <option value="">Select Sale</option>

                    @foreach ($sales as $sale)
                        @php
                            $remaining = max(
                                (float) $sale->grand_total - (float) $sale->paid_amount,
                                0
                            );
                        @endphp

                        @if ($remaining > 0)
                            <option
                                value="{{ $sale->id }}"
                                data-total="{{ $sale->grand_total }}"
                                data-paid="{{ $sale->paid_amount }}"
                                @selected(old('sale_id', $payment->sale_id ?? '') == $sale->id)
                            >
                                {{ $sale->invoice_number }}
                                — Due: {{ number_format($remaining, 2) }}
                            </option>
                        @endif
                    @endforeach
                </select>

                @error('sale_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-4 payment-field d-none">
                <label class="form-label">
                    Purchase <span class="text-danger">*</span>
                </label>

                <select
                    name="purchase_id"
                    id="purchaseId"
                    class="form-select @error('purchase_id') is-invalid @enderror"
                >
                    <option value="">Select Purchase</option>

                    @foreach ($purchases as $purchase)
                        @php
                            $remaining = max(
                                (float) $purchase->grand_total - (float) $purchase->paid_amount,
                                0
                            );
                        @endphp

                        @if ($remaining > 0)
                            <option
                                value="{{ $purchase->id }}"
                                data-total="{{ $purchase->grand_total }}"
                                data-paid="{{ $purchase->paid_amount }}"
                                @selected(old('purchase_id', $payment->purchase_id ?? '') == $purchase->id)
                            >
                                {{ $purchase->purchase_number }}
                                — Due: {{ number_format($remaining, 2) }}
                            </option>
                        @endif
                    @endforeach
                </select>

                @error('purchase_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-4">
                <label class="form-label">
                    Amount <span class="text-danger">*</span>
                </label>

                <input
                    type="number"
                    name="amount"
                    id="paymentAmount"
                    class="form-control @error('amount') is-invalid @enderror"
                    step="0.01"
                    min="0.01"
                    value="{{ old('amount', $payment->amount ?? '') }}"
                    required
                >

                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-4">
                <label class="form-label">
                    Method <span class="text-danger">*</span>
                </label>

                <select
                    name="method"
                    class="form-select @error('method') is-invalid @enderror"
                    required
                >
                    <option value="cash" @selected(old('method', $payment->method ?? 'cash') === 'cash')>
                        Cash
                    </option>

                    <option value="card" @selected(old('method', $payment->method ?? '') === 'card')>
                        Card
                    </option>

                    <option value="bank_transfer" @selected(old('method', $payment->method ?? '') === 'bank_transfer')>
                        Bank Transfer
                    </option>

                    <option value="jazzcash" @selected(old('method', $payment->method ?? '') === 'jazzcash')>
                        JazzCash
                    </option>

                    <option value="easypaisa" @selected(old('method', $payment->method ?? '') === 'easypaisa')>
                        Easypaisa
                    </option>

                    <option value="other" @selected(old('method', $payment->method ?? '') === 'other')>
                        Other
                    </option>
                </select>

                @error('method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-4">
                <label class="form-label">
                    Payment Date <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="payment_date"
                    class="form-control @error('payment_date') is-invalid @enderror"
                    value="{{ old(
                        'payment_date',
                        isset($payment)
                            ? $payment->payment_date?->format('Y-m-d')
                            : now()->format('Y-m-d')
                    ) }}"
                    required
                >

                @error('payment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="col-md-6">
                <label class="form-label">
                    Reference Number
                </label>

                <input
                    type="text"
                    name="reference_number"
                    class="form-control"
                    value="{{ old('reference_number', $payment->reference_number ?? '') }}"
                    placeholder="Optional"
                >
            </div>


            <div class="col-md-6">
                <label class="form-label">
                    Notes
                </label>

                <textarea
                    name="notes"
                    class="form-control"
                    rows="1"
                >{{ old('notes', $payment->notes ?? '') }}</textarea>
            </div>

        </div>

    </div>
</div>


<div class="d-flex justify-content-end gap-2">

    <a
        href="{{ route('payments.index') }}"
        class="btn btn-secondary"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="btn btn-primary"
    >
        <i class="fas fa-save me-1"></i>
        {{ $isEdit ? 'Update Payment' : 'Record Payment' }}
    </button>

</div>


@push('scripts')
<script>
$(function () {
    function togglePaymentType() {
        const type = $('#paymentType').val();

        if (type === 'receipt') {
            $('.receipt-field').removeClass('d-none');
            $('.payment-field').addClass('d-none');

            $('#purchaseId').val('');
        } else {
            $('.receipt-field').addClass('d-none');
            $('.payment-field').removeClass('d-none');

            $('#saleId').val('');
        }
    }

    $('#paymentType').on('change', togglePaymentType);

    $('#saleId, #purchaseId').on('change', function () {
        const option = $(this).find(':selected');
        const due = Number(option.data('total') || 0) -
            Number(option.data('paid') || 0);

        if (due > 0) {
            $('#paymentAmount').val(due.toFixed(2));
        }
    });

    togglePaymentType();
});
</script>
@endpush