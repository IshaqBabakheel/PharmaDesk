<div class="row">

    {{-- Name --}}
    <div class="col-md-6 mb-3">

        <label for="name" class="form-label">
            Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $customer->name ?? '') }}"
        >

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Phone --}}
    <div class="col-md-6 mb-3">

        <label for="phone" class="form-label">
            Phone
        </label>

        <input
            type="text"
            id="phone"
            name="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $customer->phone ?? '') }}"
        >

        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Email --}}
    <div class="col-md-6 mb-3">

        <label for="email" class="form-label">
            Email
        </label>

        <input
            type="email"
            id="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $customer->email ?? '') }}"
        >

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Customer Type --}}
    <div class="col-md-6 mb-3">

        <label for="customer_type" class="form-label">
            Customer Type <span class="text-danger">*</span>
        </label>

        <select
            id="customer_type"
            name="customer_type"
            class="form-select @error('customer_type') is-invalid @enderror"
        >

            <option
                value="regular"
                {{ old('customer_type', $customer->customer_type ?? 'regular') === 'regular' ? 'selected' : '' }}
            >
                Regular
            </option>

            <option
                value="walk_in"
                {{ old('customer_type', $customer->customer_type ?? '') === 'walk_in' ? 'selected' : '' }}
            >
                Walk In
            </option>

            <option
                value="corporate"
                {{ old('customer_type', $customer->customer_type ?? '') === 'corporate' ? 'selected' : '' }}
            >
                Corporate
            </option>

        </select>

        @error('customer_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Credit Limit --}}
    <div class="col-md-6 mb-3">

        <label for="credit_limit" class="form-label">
            Credit Limit
        </label>

        <input
            type="number"
            id="credit_limit"
            name="credit_limit"
            class="form-control @error('credit_limit') is-invalid @enderror"
            step="0.01"
            min="0"
            value="{{ old('credit_limit', $customer->credit_limit ?? 0) }}"
        >

        @error('credit_limit')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Opening Balance --}}
    <div class="col-md-6 mb-3">

        <label for="opening_balance" class="form-label">
            Opening Balance
        </label>

        <input
            type="number"
            id="opening_balance"
            name="opening_balance"
            class="form-control @error('opening_balance') is-invalid @enderror"
            step="0.01"
            min="0"
            value="{{ old('opening_balance', $customer->opening_balance ?? 0) }}"
        >

        @error('opening_balance')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Balance Type --}}
    <div class="col-md-6 mb-3">

        <label for="balance_type" class="form-label">
            Balance Type <span class="text-danger">*</span>
        </label>

        <select
            id="balance_type"
            name="balance_type"
            class="form-select @error('balance_type') is-invalid @enderror"
        >

            <option
                value="debit"
                {{ old('balance_type', $customer->balance_type ?? 'debit') === 'debit' ? 'selected' : '' }}
            >
                Debit
            </option>

            <option
                value="credit"
                {{ old('balance_type', $customer->balance_type ?? '') === 'credit' ? 'selected' : '' }}
            >
                Credit
            </option>

        </select>

        @error('balance_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Gender --}}
    <div class="col-md-6 mb-3">

        <label for="gender" class="form-label">
            Gender
        </label>

        <select
            id="gender"
            name="gender"
            class="form-select @error('gender') is-invalid @enderror"
        >

            <option value="">Select Gender</option>

            <option
                value="male"
                {{ old('gender', $customer->gender ?? '') === 'male' ? 'selected' : '' }}
            >
                Male
            </option>

            <option
                value="female"
                {{ old('gender', $customer->gender ?? '') === 'female' ? 'selected' : '' }}
            >
                Female
            </option>

            <option
                value="other"
                {{ old('gender', $customer->gender ?? '') === 'other' ? 'selected' : '' }}
            >
                Other
            </option>

        </select>

        @error('gender')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Date of Birth --}}
    <div class="col-md-6 mb-3">

        <label for="date_of_birth" class="form-label">
            Date of Birth
        </label>

        <input
            type="date"
            id="date_of_birth"
            name="date_of_birth"
            class="form-control @error('date_of_birth') is-invalid @enderror"
            value="{{ old('date_of_birth', isset($customer->date_of_birth) ? $customer->date_of_birth->format('Y-m-d') : '') }}"
        >

        @error('date_of_birth')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- City --}}
    <div class="col-md-6 mb-3">

        <label for="city" class="form-label">
            City
        </label>

        <input
            type="text"
            id="city"
            name="city"
            class="form-control @error('city') is-invalid @enderror"
            value="{{ old('city', $customer->city ?? '') }}"
        >

        @error('city')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- State --}}
    <div class="col-md-6 mb-3">

        <label for="state" class="form-label">
            State / Province
        </label>

        <input
            type="text"
            id="state"
            name="state"
            class="form-control @error('state') is-invalid @enderror"
            value="{{ old('state', $customer->state ?? '') }}"
        >

        @error('state')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Country --}}
    <div class="col-md-6 mb-3">

        <label for="country" class="form-label">
            Country
        </label>

        <input
            type="text"
            id="country"
            name="country"
            class="form-control @error('country') is-invalid @enderror"
            value="{{ old('country', $customer->country ?? 'Pakistan') }}"
        >

        @error('country')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Blood Group --}}
    <div class="col-md-6 mb-3">

        <label for="blood_group" class="form-label">
            Blood Group
        </label>

        <input
            type="text"
            id="blood_group"
            name="blood_group"
            class="form-control @error('blood_group') is-invalid @enderror"
            value="{{ old('blood_group', $customer->blood_group ?? '') }}"
        >

        @error('blood_group')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Address --}}
    <div class="col-md-12 mb-3">

        <label for="address" class="form-label">
            Address
        </label>

        <textarea
            id="address"
            name="address"
            rows="3"
            class="form-control @error('address') is-invalid @enderror"
        >{{ old('address', $customer->address ?? '') }}</textarea>

        @error('address')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Allergies --}}
    <div class="col-md-6 mb-3">

        <label for="allergies" class="form-label">
            Allergies
        </label>

        <textarea
            id="allergies"
            name="allergies"
            rows="3"
            class="form-control @error('allergies') is-invalid @enderror"
        >{{ old('allergies', $customer->allergies ?? '') }}</textarea>

        @error('allergies')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Notes --}}
    <div class="col-md-6 mb-3">

        <label for="notes" class="form-label">
            Notes
        </label>

        <textarea
            id="notes"
            name="notes"
            rows="3"
            class="form-control @error('notes') is-invalid @enderror"
        >{{ old('notes', $customer->notes ?? '') }}</textarea>

        @error('notes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>