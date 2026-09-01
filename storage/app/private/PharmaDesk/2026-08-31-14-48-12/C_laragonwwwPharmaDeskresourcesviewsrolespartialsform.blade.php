@csrf

<div class="card">

    <div class="card-header">

        <h5 class="mb-0">

            Role Information

        </h5>

    </div>

    <div class="card-body">

        <div class="mb-4">

            <label class="form-label">

                Role Name <span class="text-danger">*</span>

            </label>

            <input
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $role->name ?? '') }}"
                placeholder="Enter role name"
            >

            @error('name')

                <div class="invalid-feedback">

                    {{ $message }}

                </div>

            @enderror

        </div>

    </div>

</div>

<div class="card mt-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            Permissions

        </h5>

        <div>

            <button
                type="button"
                class="btn btn-success btn-sm"
                id="checkAll"
            >

                Select All

            </button>

            <button
                type="button"
                class="btn btn-danger btn-sm"
                id="uncheckAll"
            >

                Clear All

            </button>

        </div>

    </div>

    <div class="card-body">

        <div class="row">

            @foreach($permissions as $module => $modulePermissions)

                <div class="col-lg-6 mb-4">

                    <div class="card border">

                        <div class="card-header d-flex justify-content-between">

                            <strong>

                                {{ ucwords(str_replace('-', ' ', $module)) }}

                            </strong>

                            <div>

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm select-module"
                                >

                                    All

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-outline-danger btn-sm clear-module"
                                >

                                    None

                                </button>

                            </div>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                @foreach($modulePermissions as $permission)

                                    <div class="col-md-6 mb-2">

                                        <div class="form-check">

                                            <input

                                                class="form-check-input permission-checkbox"

                                                type="checkbox"

                                                name="permissions[]"

                                                value="{{ $permission->name }}"

                                                id="{{ $permission->id }}"

                                                @checked(

                                                    old(
                                                        'permissions',
                                                        isset($role)
                                                            ? $role->permissions->pluck('name')->toArray()
                                                            : []
                                                    ) &&
                                                    in_array(
                                                        $permission->name,
                                                        old(
                                                            'permissions',
                                                            isset($role)
                                                                ? $role->permissions->pluck('name')->toArray()
                                                                : []
                                                        )
                                                    )

                                                )

                                            >

                                            <label
                                                class="form-check-label"
                                                for="{{ $permission->id }}"
                                            >

                                                {{ ucwords(str_replace('-', ' ', explode('.', $permission->name)[1])) }}

                                            </label>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

<div class="mt-4">

    <button
        class="btn btn-primary"
    >

        <i class="fas fa-save me-1"></i>

        Save Role

    </button>

    <a
        href="{{ route('roles.index') }}"
        class="btn btn-secondary"
    >

        Cancel

    </a>

</div>

@push('scripts')

<script>

document.getElementById('checkAll').onclick = function(){

    document.querySelectorAll('.permission-checkbox')

        .forEach(cb => cb.checked = true);

};

document.getElementById('uncheckAll').onclick = function(){

    document.querySelectorAll('.permission-checkbox')

        .forEach(cb => cb.checked = false);

};

document.querySelectorAll('.select-module')

    .forEach(function(button){

        button.addEventListener('click',function(){

            this.closest('.card')

                .querySelectorAll('.permission-checkbox')

                .forEach(cb => cb.checked = true);

        });

});

document.querySelectorAll('.clear-module')

    .forEach(function(button){

        button.addEventListener('click',function(){

            this.closest('.card')

                .querySelectorAll('.permission-checkbox')

                .forEach(cb => cb.checked = false);

        });

});

</script>

@endpush