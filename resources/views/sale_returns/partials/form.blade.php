<form action="{{ $formAction }}" method="POST" id="saleReturnForm">
    @csrf
    @if (!empty($formMethod))
        @method($formMethod)
    @endif

    {{-- Sale Information --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-file-invoice me-1 text-primary"></i>
                Sale Information
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Sale --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Sale / Invoice
                        <span class="text-danger">*</span>
                    </label>

                    @if ($lockedSale ?? false)
                        {{-- ✅ Sale is locked in shortcut mode --}}
                        <input type="text" class="form-control" 
                            value="{{ $selectedSale->invoice_number }} - {{ $selectedSale->sale_date?->format('d M Y') }}"
                            readonly>
                        <input type="hidden" name="sale_id" value="{{ $selectedSale->id }}">
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i>
                            Sale is locked. Return is being created from the sale.
                        </small>
                    @else
                        {{-- Normal mode - select from dropdown --}}
                        <select name="sale_id" id="sale_id" 
                            class="form-select @error('sale_id') is-invalid @enderror" required>
                            <option value="">Select Sale</option>
                            @foreach ($sales as $sale)
                                <option value="{{ $sale->id }}" 
                                    {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
                                    {{ $sale->invoice_number }}
                                    - {{ $sale->sale_date?->format('d M Y') }}
                                    - {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    @error('sale_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Return Date --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        Return Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="return_date"
                        class="form-control @error('return_date') is-invalid @enderror"
                        value="{{ old('return_date', $saleReturn?->return_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                        required>
                </div>

                {{-- Status --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach (['completed' => 'Completed', 'draft' => 'Draft', 'cancelled' => 'Cancelled'] as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('status', $saleReturn->status ?? 'completed') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Customer --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer</label>
                    <input type="text" id="customer_name" class="form-control"
                        value="{{ $selectedSale->customer?->name ?? ($saleReturn?->customer?->name ?? ($sale?->customer?->name ?? 'Walk-in Customer')) }}"
                        readonly>
                    <input type="hidden" name="customer_id"
                        value="{{ old('customer_id', $saleReturn->customer_id ?? $selectedSale?->customer_id ?? $sale?->customer_id) }}">
                </div>

                {{-- Original Sale Total --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">Original Sale Total</label>
                    <input type="text" id="sale_total" class="form-control"
                        value="{{ number_format((float) ($selectedSale->grand_total ?? $sale?->grand_total ?? 0), 2) }}"
                        readonly>
                </div>
            </div>
        </div>
    </div>

    {{-- Return Items --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-pills me-1 text-primary"></i>
                Return Items
            </h5>
        </div>
        <div class="card-body">
            @include('sale_returns.partials.return-items', [
                'items' => $saleItems ?? [],
                'saleReturn' => $saleReturn ?? null,
                'lockedSale' => $lockedSale ?? false,
            ])
        </div>
    </div>

    {{-- Totals --}}
    <div class="row">
        {{-- Notes --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-note-sticky me-1 text-primary"></i>
                        Notes
                    </h5>
                </div>
                <div class="card-body">
                    <textarea name="notes" rows="5" class="form-control" placeholder="Optional notes...">{{ old('notes', $saleReturn->notes ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Return Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <strong id="subtotalDisplay">
                            {{ number_format((float) ($saleReturn->subtotal ?? 0), 2) }}
                        </strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Discount</label>
                        <input type="number" name="discount" id="discount" class="form-control" min="0"
                            step="0.01" value="{{ old('discount', $saleReturn->discount ?? 0) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tax</label>
                        <input type="number" name="tax" id="tax" class="form-control" min="0"
                            step="0.01" value="{{ old('tax', $saleReturn->tax ?? 0) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Other Charges</label>
                        <input type="number" name="other_charges" id="other_charges" class="form-control"
                            min="0" step="0.01"
                            value="{{ old('other_charges', $saleReturn->other_charges ?? 0) }}">
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong class="fs-5">Grand Total</strong>
                        <strong class="fs-5 text-danger" id="grandTotalDisplay">
                            {{ number_format((float) ($saleReturn->grand_total ?? 0), 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('sale-returns.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Cancel
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>
            {{ $isEdit ? 'Update Sale Return' : 'Create Sale Return' }}
        </button>
    </div>
</form>