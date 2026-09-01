

<?php $__env->startSection('title', 'Backups'); ?>

<?php $__env->startSection('content'); ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="fas fa-database text-primary me-2"></i>
                    Backups
                </h3>
                <p class="text-muted mb-0">
                    Protect PharmaDesk database and application data.
                </p>
            </div>


            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('backups.create')): ?>
                <form method="POST" action="<?php echo e(route('backups.create')); ?>" id="createBackupForm">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary" id="createBackupBtn">
                        <i class="fas fa-database me-1"></i>
                        Create Backup
                    </button>
                </form>
            <?php endif; ?>
        </div>


        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>


        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>


        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Backup History
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Backup</th>
                                <th>Date</th>
                                <th>Size</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <i class="fas fa-file-zipper text-primary me-1"></i>
                                        <?php echo e($backup['filename']); ?>

                                    </td>
                                    <td><?php echo e($backup['date'] ?? '-'); ?></td>
                                    <td><?php echo e($backup['size_human']); ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('backups.download')): ?>
                                                <a href="<?php echo e(route('backups.download', $backup['filename'])); ?>"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('backups.delete')): ?>
                                                <form method="POST"
                                                    action="<?php echo e(route('backups.destroy', $backup['filename'])); ?>"
                                                    class="backup-delete-form">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fas fa-database fs-1 d-block mb-2"></i>
                                        No backups found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        $(function() {

            $('#createBackupForm')
                .on('submit', function() {

                    const button =
                        $('#createBackupBtn');

                    button
                        .prop('disabled', true)
                        .html(`
                    <span
                        class="spinner-border
                        spinner-border-sm
                        me-1">
                    </span>
                    Creating...
                `);

                });


            $('.backup-delete-form')
                .on('submit', function(event) {

                    if (
                        !confirm(
                            'Are you sure you want to delete this backup?'
                        )
                    ) {
                        event.preventDefault();
                    }

                });

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PharmaDesk\resources\views/backups/index.blade.php ENDPATH**/ ?>