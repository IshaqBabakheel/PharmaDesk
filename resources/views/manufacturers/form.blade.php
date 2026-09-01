<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Company Name <span class="text-danger">*</span>
        </label>

        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $manufacturer->name ?? '') }}" placeholder="Enter Company name">

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Contact Person

        </label>
        <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
            value="{{ old('contact_person', $manufacturer->contact_person ?? '') }}"placeholder="Enter Contact person">

        @error('contact_person')
            <div class="invalid-feedback">

                {{ $message }}

            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Phone <span class="text-danger">*</span>
        </label>

        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $manufacturer->phone ?? '') }}" placeholder="Enter Phone No">

        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email <span class="text-danger">*</span>
        </label>

        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $manufacturer->email ?? '') }}" placeholder="Enter email">

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Website <span class="text-danger">*</span>
        </label>

        <input type="text" name="website" class="form-control @error('website') is-invalid @enderror"
            value="{{ old('website', $manufacturer->website ?? '') }}" placeholder="Enter Website">

        @error('website')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Address <span class="text-danger">*</span>
        </label>

        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
            value="{{ old('address', $manufacturer->address ?? '') }}" placeholder="Enter address">

        @error('address')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            City <span class="text-danger">*</span>
        </label>

        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
            value="{{ old('city', $manufacturer->city ?? '') }}" placeholder="Enter city">

        @error('city')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Country <span class="text-danger">*</span>
        </label>

        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
            value="{{ old('country', $manufacturer->country ?? '') }}" placeholder="Enter country">

        @error('country')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Notes <span class="text-danger">*</span>
        </label>

        <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror"
            value="{{ old('notes', $manufacturer->notes ?? '') }}" placeholder="Enter notes">

        @error('notes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    <div class="col-md-6 mb-4">

        <label class="form-label">

            Status

        </label>

        <select name="status" class="form-select">

            <option value="1" @selected(old('status', $manufacturer->status ?? 1) == 1)>
                Active
            </option>

            <option value="0" @selected(old('status', $manufacturer->status ?? 1) == 0)>
                Inactive
            </option>

        </select>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Sort Order

        </label>

        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $manufacturer->sort_order ?? 0) }}">

        @error('sort_order')
            <div class="invalid-feedback">

                {{ $message }}

            </div>
        @enderror

    </div>

</div>




<div class="border-top pt-3">

    <button class="btn btn-primary">

        <i class="fas fa-save me-1"></i>

        Save

    </button>

    <a href="{{ route('manufacturers.index') }}" class="btn btn-secondary">

        Cancel

    </a>

</div>
