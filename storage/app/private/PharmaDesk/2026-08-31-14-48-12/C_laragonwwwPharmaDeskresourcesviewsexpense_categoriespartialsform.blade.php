<div class="card shadow-sm border-0">

    <div class="card-header bg-white">
        <h5 class="mb-0">
            Expense Category Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Name <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $expenseCategory->name ?? '') }}"
                    placeholder="Example: Electricity"
                    required
                >

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Status
                </label>

                <div class="form-check form-switch mt-2">

                    <input
                        type="hidden"
                        name="status"
                        value="0"
                    >

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        value="1"
                        id="categoryStatus"
                        @checked(
                            old(
                                'status',
                                $expenseCategory->status ?? true
                            )
                        )
                    >

                    <label
                        class="form-check-label"
                        for="categoryStatus"
                    >
                        Active
                    </label>

                </div>

            </div>


            <div class="col-12 mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Optional description"
                >{{ old('description', $expenseCategory->description ?? '') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

    </div>

</div>


<div class="d-flex justify-content-end gap-2 mt-4">

    <a
        href="{{ route('expense-categories.index') }}"
        class="btn btn-secondary"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="btn btn-primary"
    >
        <i class="fas fa-save me-1"></i>

        {{ isset($expenseCategory)
            ? 'Update Category'
            : 'Save Category' }}
    </button>

</div>