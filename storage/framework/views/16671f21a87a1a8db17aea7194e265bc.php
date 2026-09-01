

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fas fa-user-shield text-warning me-2"></i>

            Edit Role

        </h2>

        <p class="text-muted mb-0">

            Update role information and permissions.

        </p>

    </div>

    <div>

        <nav>

            <ol class="breadcrumb justify-content-end mb-0">

                <li class="breadcrumb-item">

                    <a href="<?php echo e(route('home')); ?>">

                        Dashboard

                    </a>

                </li>

                <li class="breadcrumb-item">

                    User Management

                </li>

                <li class="breadcrumb-item">

                    <a href="<?php echo e(route('roles.index')); ?>">

                        Roles

                    </a>

                </li>

                <li class="breadcrumb-item active">

                    Edit

                </li>

            </ol>

        </nav>

    </div>

</div>

<form
    action="<?php echo e(route('roles.update',$role)); ?>"
    method="POST"
>

    <?php echo method_field('PUT'); ?>

    <?php echo $__env->make('roles.partials.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PharmaDesk\resources\views/roles/edit.blade.php ENDPATH**/ ?>