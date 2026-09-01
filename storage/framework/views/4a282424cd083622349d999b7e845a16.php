<?php echo csrf_field(); ?>

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
                class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('name', $role->name ?? '')); ?>"
                placeholder="Enter role name"
            >

            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                <div class="invalid-feedback">

                    <?php echo e($message); ?>


                </div>

            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

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

            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="col-lg-6 mb-4">

                    <div class="card border">

                        <div class="card-header d-flex justify-content-between">

                            <strong>

                                <?php echo e(ucwords(str_replace('-', ' ', $module))); ?>


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

                                <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <div class="col-md-6 mb-2">

                                        <div class="form-check">

                                            <input

                                                class="form-check-input permission-checkbox"

                                                type="checkbox"

                                                name="permissions[]"

                                                value="<?php echo e($permission->name); ?>"

                                                id="<?php echo e($permission->id); ?>"

                                                <?php if(

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

                                                ): echo 'checked'; endif; ?>

                                            >

                                            <label
                                                class="form-check-label"
                                                for="<?php echo e($permission->id); ?>"
                                            >

                                                <?php echo e(ucwords(str_replace('-', ' ', explode('.', $permission->name)[1]))); ?>


                                            </label>

                                        </div>

                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
        href="<?php echo e(route('roles.index')); ?>"
        class="btn btn-secondary"
    >

        Cancel

    </a>

</div>

<?php $__env->startPush('scripts'); ?>

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

<?php $__env->stopPush(); ?><?php /**PATH C:\laragon\www\PharmaDesk\resources\views/roles/partials/form.blade.php ENDPATH**/ ?>