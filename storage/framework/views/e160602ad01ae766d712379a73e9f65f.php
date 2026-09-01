<?php
    $actionName = match ($module) {
        'sales' => $row->invoice_number,
        'purchases' => $row->purchase_number,
        'sale-returns',
        'purchase-returns' => $row->return_number,
        default => $row->name ?? $row->id,
    };
?>

<div class="dropdown">

    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-ellipsis-v me-1"></i>
        Actions
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow">

        
        <?php if($show ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.view')): ?>
                <li>
                    <a href="<?php echo e(route($module . '.show', $row)); ?>" class="dropdown-item">
                        <i class="fas fa-eye text-info me-2"></i>
                        View Details
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($edit ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.edit')): ?>
                <li>
                    <a href="<?php echo e(route($module . '.edit', $row)); ?>" class="dropdown-item">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Edit
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($complete ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.complete')): ?>
                <li>
                    <form action="<?php echo e(route($module . '.complete', $row)); ?>" method="POST" class="complete-form"
                        data-name="<?php echo e($actionName); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <button type="submit" class="dropdown-item text-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Complete
                        </button>
                    </form>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if($purchaseReturn ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-returns.create')): ?>
                <li>
                    <a href="<?php echo e(route('purchase-returns.create', ['purchase' => $row->id])); ?>"
                        class="dropdown-item text-danger">
                        <i class="fas fa-rotate-left me-2"></i>
                        Purchase Return
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($cancel ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.cancel')): ?>
                <li>
                    <form action="<?php echo e(route($module . '.cancel', $row)); ?>" method="POST" class="cancel-form"
                        data-name="<?php echo e($actionName); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-ban me-2"></i>
                            Cancel
                        </button>
                    </form>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if($saleReturn ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sale-returns.create')): ?>
                <li>
                    <a href="<?php echo e(route('sale-returns.create', ['sale' => $row->id])); ?>"
                        class="dropdown-item text-danger">
                        <i class="fas fa-rotate-left me-2"></i>
                        Sale Return
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if($updatePayment ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.update-payment')): ?>
                <li>
                    <button type="button" class="dropdown-item text-primary update-payment-btn"
                        data-id="<?php echo e($row->id); ?>" 
                        data-name="<?php echo e($module === 'purchases'
                            ? $row->purchase_number
                            : $row->invoice_number); ?>"
                        data-paid="<?php echo e($row->paid_amount); ?>" data-total="<?php echo e($row->grand_total); ?>">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Update Payment
                    </button>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        <?php if(($stockHistory ?? false) || ($purchaseHistory ?? false) || ($salesHistory ?? false)): ?>
            <li>
                <hr class="dropdown-divider">
            </li>
        <?php endif; ?>

        
        <?php if($stockHistory ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.view')): ?>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-clock-rotate-left text-primary me-2"></i>
                        Stock History
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($purchaseHistory ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.view')): ?>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cart-shopping text-success me-2"></i>
                        Purchase History
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($salesHistory ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.view')): ?>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cash-register text-secondary me-2"></i>
                        Sales History
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        <?php if(
                ($receipt ?? false) ||
                ($barcode ?? false) ||
                ($duplicate ?? false) ||
                ($restore ?? false) ||
                ($forceDelete ?? false) ||
                ($delete ?? false)
            ): ?>
            <li>
                <hr class="dropdown-divider">
            </li>
        <?php endif; ?>


        
        <?php if($barcode ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.print')): ?>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-barcode text-dark me-2"></i>
                        Print Barcode
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php if($receipt ?? false): ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.view')): ?>

                <li>

                    <a
                        href="<?php echo e(route(
                            'sales.receipt.thermal',
                            $row
                        )); ?>"
                        target="_blank"
                        class="dropdown-item"
                    >
                        <i class="fas fa-receipt text-primary me-2"></i>
                        Print Receipt
                    </a>

                </li>

            <?php endif; ?>

        <?php endif; ?>


        
        <?php if($duplicate ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.create')): ?>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-copy text-primary me-2"></i>
                        Duplicate
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($restore ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.restore')): ?>
                <li>
                    <form action="<?php echo e(route($module . '.restore', $row->id)); ?>" method="POST" class="restore-form"
                        data-name="<?php echo e($row->name); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <button type="submit" class="dropdown-item text-success">
                            <i class="fas fa-rotate-left me-2"></i>
                            Restore
                        </button>
                    </form>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($forceDelete ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.force-delete')): ?>
                <li>
                    <form action="<?php echo e(route($module . '.force-delete', $row->id)); ?>" method="POST"
                        class="force-delete-form" data-name="<?php echo e($row->name); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-trash-can me-2"></i>
                            Permanently Delete
                        </button>
                    </form>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        
        <?php if($delete ?? false): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($module . '.delete')): ?>
                <li>
                    <form action="<?php echo e(route($module . '.destroy', $row)); ?>" method="POST" class="delete-form"
                        data-name="<?php echo e($row->name); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>

                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-trash me-2"></i>
                            Delete
                        </button>
                    </form>
                </li>
            <?php endif; ?>
        <?php endif; ?>

    </ul>

</div>
<?php /**PATH C:\laragon\www\PharmaDesk\resources\views/components/action-dropdown.blade.php ENDPATH**/ ?>