@csrf

<div class="row">

    {{-- Profile Photo --}}
    <div class="col-md-12 mb-4 text-center">

        @if(isset($user) && $user->profile_photo)

            <img
                src="{{ asset('storage/'.$user->profile_photo) }}"
                class="rounded-circle border mb-3"
                width="120"
                height="120"
                style="object-fit:cover;"
            >

        @endif

        <input
            type="file"
            name="profile_photo"
            class="form-control @error('profile_photo') is-invalid @enderror"
            accept="image/*"
        >

        @error('profile_photo')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Name --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Full Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name',$user->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
        >

        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Email --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Email <span class="text-danger">*</span>
        </label>

        <input
            type="email"
            name="email"
            value="{{ old('email',$user->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror"
        >

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Phone --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Phone

        </label>

        <input
            type="text"
            name="phone"
            value="{{ old('phone',$user->phone ?? '') }}"
            class="form-control @error('phone') is-invalid @enderror"
        >

        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Gender --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Gender

        </label>

        <select
            name="gender"
            class="form-select @error('gender') is-invalid @enderror"
        >

            <option value="">Select Gender</option>

            <option value="Male"
                @selected(old('gender',$user->gender ?? '')=='Male')
            >
                Male
            </option>

            <option value="Female"
                @selected(old('gender',$user->gender ?? '')=='Female')
            >
                Female
            </option>

            <option value="Other"
                @selected(old('gender',$user->gender ?? '')=='Other')
            >
                Other
            </option>

        </select>

        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- DOB --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Date of Birth

        </label>

        <input
            type="date"
            name="date_of_birth"
            value="{{ old('date_of_birth',$user->date_of_birth ?? '') }}"
            class="form-control @error('date_of_birth') is-invalid @enderror"
        >

        @error('date_of_birth')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- City --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            City

        </label>

        <input
            type="text"
            name="city"
            value="{{ old('city',$user->city ?? '') }}"
            class="form-control @error('city') is-invalid @enderror"
        >

        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Country --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Country

        </label>

        <input
            type="text"
            name="country"
            value="{{ old('country',$user->country ?? 'Pakistan') }}"
            class="form-control @error('country') is-invalid @enderror"
        >

        @error('country')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Address --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Address

        </label>

        <textarea
            name="address"
            rows="3"
            class="form-control @error('address') is-invalid @enderror"
        >{{ old('address',$user->address ?? '') }}</textarea>

        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Roles --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Roles <span class="text-danger">*</span>

        </label>

        <select
            name="roles[]"
            multiple
            class="form-select @error('roles') is-invalid @enderror"
            size="6"
        >

            @foreach($roles as $role)

                <option

                    value="{{ $role->name }}"

                    @selected(
                        in_array(
                            $role->name,
                            old(
                                'roles',
                                isset($user)
                                    ? $user->roles->pluck('name')->toArray()
                                    : []
                            )
                        )
                    )

                >

                    {{ $role->name }}

                </option>

            @endforeach

        </select>

        @error('roles')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Password --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Password

            @isset($user)
                <small class="text-muted">(Leave empty to keep current password)</small>
            @endisset

        </label>

        <input
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
        >

        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>


    {{-- Confirm Password --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">

            Confirm Password

        </label>

        <input
            type="password"
            name="password_confirmation"
            class="form-control"
        >

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
                @selected(old('status',$user->status ?? 1)==1)
            >
                Active
            </option>

            <option
                value="0"
                @selected(old('status',$user->status ?? 1)==0)
            >
                Inactive
            </option>

        </select>

    </div>

</div>

<hr>

<div class="d-flex justify-content-end">

    <a
        href="{{ route('users.index') }}"
        class="btn btn-secondary me-2"
    >

        Cancel

    </a>

    <button
        type="submit"
        class="btn btn-primary"
    >

        <i class="fas fa-save me-1"></i>

        {{ isset($user) ? 'Update User' : 'Create User' }}

    </button>

</div>