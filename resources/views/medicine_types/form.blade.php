<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Medicine Type Name <span class="text-danger">*</span>
        </label>

        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $medicineType->name ?? '') }}" placeholder="Enter medicine type name">

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">

            Sort Order

        </label>

        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $medicineType->sort_order ?? 0) }}">

        @error('sort_order')
            <div class="invalid-feedback">

                {{ $message }}

            </div>
        @enderror

    </div>

</div>


<div class="mb-3">

    <label class="form-label">

        Description

    </label>

    <textarea rows="4" name="description" class="form-control @error('description') is-invalid @enderror"
        placeholder="Optional description">{{ old('description', $medicineType->description ?? '') }}</textarea>

    @error('description')
        <div class="invalid-feedback">

            {{ $message }}

        </div>
    @enderror

</div>


<div class="mb-4">

    <label class="form-label">

        Status

    </label>

    <select name="status" class="form-select">

        <option value="1" @selected(old('status', $medicineType->status ?? 1) == 1)>
            Active
        </option>

        <option value="0" @selected(old('status', $medicineType->status ?? 1) == 0)>
            Inactive
        </option>

    </select>

</div>


<div class="border-top pt-3">

    <button class="btn btn-primary">

        <i class="fas fa-save me-1"></i>

        Save

    </button>

    <a href="{{ route('medicine-types.index') }}" class="btn btn-secondary">

        Cancel

    </a>

</div>
