<div class="row">

    <div class="col-lg-8">

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Expense Information
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Category
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="expense_category_id"
                            class="form-select @error('expense_category_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Category
                            </option>

                            @foreach ($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old(
                                            'expense_category_id',
                                            $expense->expense_category_id ?? ''
                                        ) == $category->id
                                    )
                                >
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('expense_category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Expense Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="expense_date"
                            class="form-control @error('expense_date') is-invalid @enderror"
                            value="{{ old(
                                'expense_date',
                                isset($expense)
                                    ? $expense->expense_date?->format('Y-m-d')
                                    : now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                        @error('expense_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old(
                                'title',
                                $expense->title ?? ''
                            ) }}"
                            placeholder="Example: Electricity Bill"
                            required
                        >

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="Completed"
                                @selected(
                                    old(
                                        'status',
                                        $expense->status ?? 'Completed'
                                    ) === 'Completed'
                                )
                            >
                                Completed
                            </option>

                            <option
                                value="Draft"
                                @selected(
                                    old(
                                        'status',
                                        $expense->status ?? ''
                                    ) === 'Draft'
                                )
                            >
                                Draft
                            </option>

                            <option
                                value="Cancelled"
                                @selected(
                                    old(
                                        'status',
                                        $expense->status ?? ''
                                    ) === 'Cancelled'
                                )
                            >
                                Cancelled
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            placeholder="Optional description"
                        >{{ old(
                            'description',
                            $expense->description ?? ''
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Payment Method
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="payment_method"
                            class="form-select"
                            required
                        >

                            @foreach ([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'jazzcash' => 'JazzCash',
                                'easypaisa' => 'Easypaisa',
                                'other' => 'Other',
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'payment_method',
                                            $expense->payment_method ?? 'cash'
                                        ) === $value
                                    )
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Payment Summary
                </h5>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Amount
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="number"
                        name="amount"
                        id="expenseAmount"
                        class="form-control form-control-lg fw-semibold"
                        min="0.01"
                        step="0.01"
                        value="{{ old(
                            'amount',
                            $expense->amount ?? ''
                        ) }}"
                        required
                    >

                    @error('amount')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Paid Amount
                    </label>

                    <input
                        type="number"
                        name="paid_amount"
                        id="expensePaidAmount"
                        class="form-control"
                        min="0"
                        step="0.01"
                        value="{{ old(
                            'paid_amount',
                            $expense->paid_amount ?? 0
                        ) }}"
                    >

                    @error('paid_amount')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="border rounded p-3 bg-light">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Amount</span>
                        <strong id="summaryAmount">
                            Rs. 0.00
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Due</span>
                        <strong
                            id="summaryDue"
                            class="text-danger"
                        >
                            Rs. 0.00
                        </strong>
                    </div>

                </div>


                <div class="mt-4">

                    <label class="form-label">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="5"
                        class="form-control"
                    >{{ old(
                        'notes',
                        $expense->notes ?? ''
                    ) }}</textarea>

                </div>

            </div>

        </div>

    </div>

</div>


<div class="d-flex justify-content-end gap-2">

    <a
        href="{{ route('expenses.index') }}"
        class="btn btn-secondary"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="btn btn-primary"
    >
        <i class="fas fa-save me-1"></i>

        {{ isset($expense)
            ? 'Update Expense'
            : 'Save Expense' }}
    </button>

</div>


@push('scripts')

<script>
$(function () {

    function calculateExpenseDue() {

        const amount =
            parseFloat(
                $('#expenseAmount').val()
            ) || 0;

        const paid =
            parseFloat(
                $('#expensePaidAmount').val()
            ) || 0;

        const due =
            Math.max(
                amount - paid,
                0
            );

        $('#summaryAmount').text(
            'Rs. ' +
            amount.toLocaleString(
                'en-PK',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            )
        );

        $('#summaryDue').text(
            'Rs. ' +
            due.toLocaleString(
                'en-PK',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            )
        );

    }

    $('#expenseAmount, #expensePaidAmount')
        .on('input change', calculateExpenseDue);

    calculateExpenseDue();

});
</script>

@endpush