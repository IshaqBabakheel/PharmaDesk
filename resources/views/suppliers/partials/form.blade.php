@csrf

<div class="row">

    {{-- Supplier Name --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Supplier Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name',$supplier->name ?? '') }}"
        >

        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    {{-- Company Name --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Company Name

        </label>

        <input
            type="text"
            name="company_name"
            class="form-control"
            value="{{ old('company_name',$supplier->company_name ?? '') }}"
        >

    </div>

    {{-- Contact Person --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Contact Person

        </label>

        <input
            type="text"
            name="contact_person"
            class="form-control"
            value="{{ old('contact_person',$supplier->contact_person ?? '') }}"
        >

    </div>

    {{-- Phone --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Phone <span class="text-danger">*</span>

        </label>

        <input
            type="text"
            name="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone',$supplier->phone ?? '') }}"
        >

        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    {{-- Alternate Phone --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Alternate Phone

        </label>

        <input
            type="text"
            name="alternate_phone"
            class="form-control"
            value="{{ old('alternate_phone',$supplier->alternate_phone ?? '') }}"
        >

    </div>

    {{-- Email --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Email

        </label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="{{ old('email',$supplier->email ?? '') }}"
        >

    </div>

    {{-- Website --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Website

        </label>

        <input
            type="url"
            name="website"
            class="form-control"
            value="{{ old('website',$supplier->website ?? '') }}"
        >

    </div>

    {{-- City --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            City

        </label>

        <input
            type="text"
            name="city"
            class="form-control"
            value="{{ old('city',$supplier->city ?? '') }}"
        >

    </div>

    {{-- State --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            State / Province

        </label>

        <input
            type="text"
            name="state"
            class="form-control"
            value="{{ old('state',$supplier->state ?? '') }}"
        >

    </div>

    {{-- Country --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Country

        </label>

        <input
            type="text"
            name="country"
            class="form-control"
            value="{{ old('country',$supplier->country ?? 'Pakistan') }}"
        >

    </div>

    {{-- Postal Code --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Postal Code

        </label>

        <input
            type="text"
            name="postal_code"
            class="form-control"
            value="{{ old('postal_code',$supplier->postal_code ?? '') }}"
        >

    </div>

    {{-- NTN --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            NTN

        </label>

        <input
            type="text"
            name="ntn"
            class="form-control"
            value="{{ old('ntn',$supplier->ntn ?? '') }}"
        >

    </div>

    {{-- STRN --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            STRN

        </label>

        <input
            type="text"
            name="strn"
            class="form-control"
            value="{{ old('strn',$supplier->strn ?? '') }}"
        >

    </div>

    {{-- Opening Balance --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Opening Balance

        </label>

        <input
            type="number"
            step="0.01"
            name="opening_balance"
            class="form-control"
            value="{{ old('opening_balance',$supplier->opening_balance ?? 0) }}"
        >

    </div>

    {{-- Balance Type --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Balance Type

        </label>

        <select
            name="balance_type"
            class="form-select"
        >

            <option
                value="Payable"
                @selected(old('balance_type',$supplier->balance_type ?? 'Payable')=='Payable')
            >
                Payable
            </option>

            <option
                value="Receivable"
                @selected(old('balance_type',$supplier->balance_type ?? '')=='Receivable')
            >
                Receivable
            </option>

        </select>

    </div>

    {{-- Address --}}
    <div class="col-md-12 mb-3">

        <label class="form-label">

            Address

        </label>

        <textarea
            name="address"
            rows="3"
            class="form-control"
        >{{ old('address',$supplier->address ?? '') }}</textarea>

    </div>

    {{-- Notes --}}
    <div class="col-md-12 mb-3">

        <label class="form-label">

            Notes

        </label>

        <textarea
            name="notes"
            rows="3"
            class="form-control"
        >{{ old('notes',$supplier->notes ?? '') }}</textarea>

    </div>

    {{-- Status --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Status

        </label>

        <select
            name="status"
            class="form-select"
        >

            <option
                value="1"
                @selected(old('status',$supplier->status ?? 1))
            >
                Active
            </option>

            <option
                value="0"
                @selected(old('status',$supplier->status ?? 1)==0)
            >
                Inactive
            </option>

        </select>

    </div>

    {{-- Sort Order --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Sort Order

        </label>

        <input
            type="number"
            name="sort_order"
            class="form-control"
            value="{{ old('sort_order',$supplier->sort_order ?? 0) }}"
        >

    </div>

</div>

<hr>

<div class="d-flex justify-content-end">

    <a
        href="{{ route('suppliers.index') }}"
        class="btn btn-secondary me-2"
    >
        Cancel
    </a>

    <button
        class="btn btn-primary"
        type="submit"
    >
        <i class="fas fa-save me-1"></i>

        {{ isset($supplier) ? 'Update Supplier' : 'Save Supplier' }}

    </button>

</div>