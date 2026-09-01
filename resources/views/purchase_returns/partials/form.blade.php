<div class="row">

    {{-- Left Side --}}
    <div class="col-lg-8">

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Return Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">
                    {{-- Supplier --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Supplier <span class="text-danger">*</span>
                        </label>

                        <select id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required
                            @disabled($lockedPurchase ?? false)>
                            <option value="">Select Supplier</option>

                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $selectedPurchase?->supplier_id ?? ($purchaseReturn->supplier_id ?? '')) == $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="supplier_id" id="supplier_id_hidden"
                            value="{{ old('supplier_id', $selectedPurchase?->supplier_id ?? ($purchaseReturn->supplier_id ?? '')) }}">

                        @error('supplier_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Purchase --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Purchase <span class="text-danger">*</span>
                        </label>

                        <select name="purchase_id" id="purchase_id"
                            class="form-select @error('purchase_id') is-invalid @enderror" required
                            @disabled($lockedPurchase ?? false)>
                            <option value="">Select Purchase</option>

                            @foreach ($purchases as $purchase)
                                <option value="{{ $purchase->id }}" @selected(old('purchase_id', $selectedPurchase?->id ?? ($purchaseReturn->purchase_id ?? '')) == $purchase->id)>
                                    {{ $purchase->purchase_number }}
                                </option>
                            @endforeach
                        </select>

                        @if ($lockedPurchase ?? false)
                            <input type="hidden" name="purchase_id" value="{{ $selectedPurchase->id }}">
                        @endif

                        @error('purchase_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Return Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Return Date

                        </label>

                        <input type="date" name="return_date" class="form-control"
                            value="{{ old('return_date', isset($purchaseReturn) ? $purchaseReturn->return_date->format('Y-m-d') : date('Y-m-d')) }}">

                    </div>

                    {{-- Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Status

                        </label>

                        <select name="status" class="form-select">

                            <option value="Completed">

                                Completed

                            </option>

                            <option value="Draft">

                                Draft

                            </option>

                            <option value="Cancelled">

                                Cancelled

                            </option>

                        </select>

                    </div>

                    {{-- Reason --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Reason

                        </label>

                        <input type="text" name="reason" class="form-control"
                            value="{{ old('reason', $purchaseReturn->reason ?? '') }}">

                    </div>

                </div>

            </div>

        </div>

        {{-- Return Items --}}

        @include('purchase_returns.partials.return-items')

    </div>

    {{-- Right Side --}}
    <div class="col-lg-4">

        <div class="card shadow-sm border-0">

            <div class="card-header">

                <h5 class="mb-0">

                    Return Summary

                </h5>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label>

                        Subtotal

                    </label>

                    <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control" readonly
                        value="{{ old('subtotal', $purchaseReturn->subtotal ?? 0) }}">

                </div>

                <div class="row">

                    <div class="col-6">

                        <label>

                            Discount Type

                        </label>

                        <select name="discount_type" id="discount_type" class="form-select">

                            <option value="Fixed">

                                Fixed

                            </option>

                            <option value="Percentage">

                                Percentage

                            </option>

                        </select>

                    </div>

                    <div class="col-6">

                        <label>

                            Discount

                        </label>

                        <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                            value="{{ old('discount', $purchaseReturn->discount ?? 0) }}">

                    </div>

                </div>

                <div class="mt-3 row">

                    <div class="col-6">

                        <label>

                            Tax Type

                        </label>

                        <select name="tax_type" id="tax_type" class="form-select">

                            <option value="Fixed">

                                Fixed

                            </option>

                            <option value="Percentage">

                                Percentage

                            </option>

                        </select>

                    </div>

                    <div class="col-6">

                        <label>

                            Tax

                        </label>

                        <input type="number" step="0.01" name="tax" id="tax" class="form-control"
                            value="{{ old('tax', $purchaseReturn->tax ?? 0) }}">

                    </div>

                </div>

                <div class="mt-4">

                    <label>

                        Grand Total

                    </label>

                    <input type="number" step="0.01" name="grand_total" id="grand_total"
                        class="form-control fw-bold" readonly
                        value="{{ old('grand_total', $purchaseReturn->grand_total ?? 0) }}">

                </div>

                <div class="mt-4">

                    <label>

                        Notes

                    </label>

                    <textarea name="notes" rows="5" class="form-control">{{ old('notes', $purchaseReturn->notes ?? '') }}</textarea>

                </div>

            </div>

        </div>

    </div>

</div>
