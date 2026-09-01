@php
    $isEdit = $isEdit ?? false;
@endphp


<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            <i class="fas fa-file-invoice text-primary me-2"></i>

            Adjustment Information

        </h5>

    </div>


    <div class="card-body">

        <div class="row g-3">

            {{-- Adjustment Number --}}
            @if ($isEdit)

                <div class="col-md-4">

                    <label class="form-label">
                        Adjustment Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $stockAdjustment->adjustment_number }}"
                        readonly
                    >

                </div>

            @endif


            {{-- Type --}}
            <div class="{{ $isEdit ? 'col-md-4' : 'col-md-6' }}">

                <label
                    for="type"
                    class="form-label"
                >

                    Adjustment Type
                    <span class="text-danger">*</span>

                </label>


                <select
                    name="type"
                    id="type"
                    class="form-select @error('type') is-invalid @enderror"
                    required
                >

                    <option value="">
                        Select Type
                    </option>

                    <option
                        value="increase"
                        @selected(
                            old(
                                'type',
                                $stockAdjustment->type ?? ''
                            ) === 'increase'
                        )
                    >
                        Increase Stock
                    </option>

                    <option
                        value="decrease"
                        @selected(
                            old(
                                'type',
                                $stockAdjustment->type ?? ''
                            ) === 'decrease'
                        )
                    >
                        Decrease Stock
                    </option>

                </select>


                @error('type')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- Adjustment Date --}}
            <div class="{{ $isEdit ? 'col-md-4' : 'col-md-6' }}">

                <label
                    for="adjustment_date"
                    class="form-label"
                >

                    Adjustment Date
                    <span class="text-danger">*</span>

                </label>


                <input
                    type="date"
                    name="adjustment_date"
                    id="adjustment_date"
                    class="form-control @error('adjustment_date') is-invalid @enderror"
                    value="{{ old(
                        'adjustment_date',
                        isset($stockAdjustment)
                            ? $stockAdjustment->adjustment_date?->format('Y-m-d')
                            : now()->format('Y-m-d')
                    ) }}"
                    required
                >


                @error('adjustment_date')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- Reason --}}
            <div class="col-md-6">

                <label
                    for="reason"
                    class="form-label"
                >

                    Reason
                    <span class="text-danger">*</span>

                </label>


                <input
                    type="text"
                    name="reason"
                    id="reason"
                    class="form-control @error('reason') is-invalid @enderror"
                    value="{{ old(
                        'reason',
                        $stockAdjustment->reason ?? ''
                    ) }}"
                    placeholder="e.g. Damaged stock, physical count correction"
                    required
                >


                @error('reason')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- Status --}}
            <div class="col-md-3">

                <label
                    for="status"
                    class="form-label"
                >

                    Status
                    <span class="text-danger">*</span>

                </label>


                <select
                    name="status"
                    id="status"
                    class="form-select"
                    required
                >

                    <option
                        value="draft"
                        @selected(
                            old(
                                'status',
                                $stockAdjustment->status ?? 'draft'
                            ) === 'draft'
                        )
                    >
                        Draft
                    </option>

                    <option
                        value="completed"
                        @selected(
                            old(
                                'status',
                                $stockAdjustment->status ?? ''
                            ) === 'completed'
                        )
                    >
                        Completed
                    </option>

                </select>

            </div>


            {{-- Notes --}}
            <div class="col-md-3">

                <label
                    for="notes"
                    class="form-label"
                >
                    Notes
                </label>


                <textarea
                    name="notes"
                    id="notes"
                    rows="1"
                    class="form-control"
                    placeholder="Optional notes..."
                >{{ old(
                    'notes',
                    $stockAdjustment->notes ?? ''
                ) }}</textarea>

            </div>

        </div>

    </div>

</div>


{{-- Adjustment Items --}}
@include(
    'stock_adjustments.partials.adjustment-items',
    [
        'stockAdjustment' => $stockAdjustment,
        'medicines' => $medicines,
    ]
)


{{-- Actions --}}
<div class="d-flex justify-content-end gap-2 mb-4">

    <a
        href="{{ $isEdit
            ? route(
                'stock-adjustments.show',
                $stockAdjustment
            )
            : route('stock-adjustments.index')
        }}"
        class="btn btn-secondary"
    >

        <i class="fas fa-arrow-left me-1"></i>

        Cancel

    </a>


    <button
        type="submit"
        class="btn btn-primary"
    >

        <i class="fas fa-save me-1"></i>

        {{ $isEdit
            ? 'Update Adjustment'
            : 'Save Adjustment'
        }}

    </button>

</div>