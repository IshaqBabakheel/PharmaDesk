{{-- @csrf


<div class="card shadow-sm">


    <div class="card-header">

        <h5 class="mb-0">

            Permission Information

        </h5>


    </div>



    <div class="card-body">


        <div class="mb-3">


            <label class="form-label">

                Permission Name

                <span class="text-danger">*</span>

            </label>


            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $permission->name ?? '') }}" placeholder="Example: medicines.create">



            @error('name')
                <div class="invalid-feedback">

                    {{ $message }}

                </div>
            @enderror


            <small class="text-muted">

                Format: module.action

                Example: medicines.create

            </small>


        </div>



        <div class="mb-3">


            <label class="form-label">

                Guard Name

            </label>


            <select name="guard_name" class="form-select">


                <option value="web">

                    Web

                </option>


            </select>


        </div>


    </div>


</div>





<div class="mt-4">


    <button class="btn btn-primary">

        <i class="fas fa-save me-1"></i>

        Save Permission

    </button>



    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">

        Cancel

    </a>



</div> --}}


@csrf

<div class="row">

    {{-- Module --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Module <span class="text-danger">*</span>
        </label>

        <select name="module" id="permissionModule" class="form-select @error('module') is-invalid @enderror" required>

            <option value="">
                Select Module
            </option>

            @foreach (config('permission.modules', []) as $moduleName => $moduleActions)
                <option value="{{ $moduleName }}" @selected(old('module', $module ?? '') === $moduleName)>
                    {{ Str::headline($moduleName) }}
                </option>
            @endforeach

        </select>

        @error('module')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Action --}}
    <div class="col-md-6 mb-3">

        <label class="form-label">
            Action <span class="text-danger">*</span>
        </label>

        <select name="action" id="permissionAction" class="form-select @error('action') is-invalid @enderror" required
            disabled>

            <option value="">
                Select Module First
            </option>

        </select>

        @error('action')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

<hr>

<div class="d-flex justify-content-end">

    <a href="{{ route('permissions.index') }}" class="btn btn-secondary me-2">
        Cancel
    </a>

    <button class="btn btn-primary" type="submit">
        <i class="fas fa-save me-1"></i>

        {{ isset($permission) ? 'Update Permission' : 'Create Permission' }}

    </button>

</div>
