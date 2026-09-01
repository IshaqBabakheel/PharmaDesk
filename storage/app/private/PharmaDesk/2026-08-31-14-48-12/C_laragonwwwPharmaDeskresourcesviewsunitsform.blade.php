<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Unit Name <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $unit->name ?? '') }}"
               placeholder="e.g. Tablet">

        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Short Name <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="short_name"
               class="form-control @error('short_name') is-invalid @enderror"
               value="{{ old('short_name', $unit->short_name ?? '') }}"
               placeholder="e.g. Tab">

        @error('short_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

</div>

<div class="mb-3">

    <label class="form-label">
        Description
    </label>

    <textarea
        name="description"
        rows="4"
        placeholder="Optional description"
        class="form-control">{{ old('description', $unit->description ?? '') }}</textarea>

</div>

<div class="row">

    <div class="col-md-6">

        <label class="form-label">Status</label>

        <select class="form-select" name="status">

            <option value="1"
                @selected(old('status', $unit->status ?? 1)==1)>
                Active
            </option>

            <option value="0"
                @selected(old('status', $unit->status ?? 1)==0)>
                Inactive
            </option>

        </select>

    </div>

    <div class="col-md-6">

        <label class="form-label">Sort Order</label>

        <input type="number"
               class="form-control"
               name="sort_order"
               value="{{ old('sort_order', $unit->sort_order ?? 0) }}">

    </div>

</div>

<hr>

<button class="btn btn-primary">
    <i class="fas fa-save me-1"></i>
    Save
</button>

<a href="{{ route('units.index') }}"
   class="btn btn-secondary">
    Cancel
</a>