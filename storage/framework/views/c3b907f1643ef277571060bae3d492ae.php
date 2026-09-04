

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-1">Notifications</h4>
            <p class="text-muted mb-0">
                System alerts and important reminders.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-outline-primary"
            id="markAllRead"
        >
            <i class="fa-solid fa-check-double me-1"></i>
            Mark All as Read
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <?php
                    $data = $notification->data ?? [];
                    $type = $data['type'] ?? 'system';

                    $icon = match ($type) {
                        'low_stock' => 'fa-solid fa-box-open text-warning',
                        'expiry' => 'fa-solid fa-calendar-xmark text-warning',
                        'expired' => 'fa-solid fa-triangle-exclamation text-danger',
                        'customer_due' => 'fa-solid fa-user-clock text-primary',
                        'supplier_due' => 'fa-solid fa-truck-clock text-primary',
                        default => 'fa-solid fa-bell text-secondary',
                    };
                ?>

                <div
                    class="notification-item border-bottom p-3 <?php echo e($notification->read_at ? '' : 'bg-light'); ?>"
                    data-id="<?php echo e($notification->id); ?>"
                >
                    <div class="d-flex gap-3">

                        <div class="fs-4" style="width: 35px;">
                            <i class="<?php echo e($icon); ?>"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="d-flex justify-content-between gap-3">
                                <strong>
                                    <?php echo e($data['title'] ?? 'Notification'); ?>

                                </strong>

                                <small class="text-muted text-nowrap">
                                    <?php echo e($notification->created_at?->diffForHumans()); ?>

                                </small>
                            </div>

                            <div class="text-muted mt-1">
                                <?php echo e($data['message'] ?? ''); ?>

                            </div>

                            <?php if(!empty($data['batch_number'])): ?>
                                <div class="small mt-2">
                                    <strong>Batch:</strong>
                                    <?php echo e($data['batch_number']); ?>

                                </div>
                            <?php endif; ?>

                            <?php if(isset($data['amount'])): ?>
                                <div class="small mt-2">
                                    <strong>Amount:</strong>
                                    Rs. <?php echo e(number_format((float) $data['amount'], 2)); ?>

                                </div>
                            <?php endif; ?>

                            <div class="mt-2 d-flex gap-2">

                                <?php if(!empty($data['url'])): ?>
                                    <a
                                        href="<?php echo e($data['url']); ?>"
                                        class="btn btn-sm btn-outline-primary"
                                        onclick="markNotificationRead('<?php echo e($notification->id); ?>')"
                                    >
                                        View
                                    </a>
                                <?php endif; ?>

                                <?php if (! ($notification->read_at)): ?>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light border mark-read"
                                        data-id="<?php echo e($notification->id); ?>"
                                    >
                                        Mark as Read
                                    </button>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-bell-slash fs-2 d-block mb-3"></i>
                    No notifications found.
                </div>

            <?php endif; ?>

        </div>

        <?php if($notifications->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($notifications->links()); ?>

            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function () {

    $('.mark-read').on('click', function () {
        markNotificationRead($(this).data('id'), true);
    });

    $('#markAllRead').on('click', function () {
        $.ajax({
            url: <?php echo json_encode(route('notifications.read-all'), 15, 512) ?>,
            type: 'PATCH',
            data: {
                _token: <?php echo json_encode(csrf_token(), 15, 512) ?>
            },
            success: function () {
                window.location.reload();
            }
        });
    });

});

function markNotificationRead(id, reload = false) {
    $.ajax({
        url: <?php echo json_encode(url('/notifications'), 15, 512) ?> + '/' + id + '/read',
        type: 'PATCH',
        data: {
            _token: <?php echo json_encode(csrf_token(), 15, 512) ?>
        },
        success: function () {
            if (reload) {
                window.location.reload();
            }
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PharmaDesk\resources\views/notifications/index.blade.php ENDPATH**/ ?>