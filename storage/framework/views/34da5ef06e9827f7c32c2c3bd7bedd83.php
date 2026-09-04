<aside class="menu-sidebar" id="main-sidebar">
    <div class="logo"
        style="
            background: transparent;
            padding: 20px 15px 15px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        ">
        <a href="<?php echo e(route('home')); ?>" class="logo-link" aria-label="PharmaDesk home">
            <div
                style="
                    background: #ffffff;
                    padding: 10px 15px;
                    border-radius: 12px;
                    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    transition: all 0.3s ease;
                 ">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="PharmaDesk Logo"
                    style="
                    width: 100%;
                    max-width: 200px;
                    height: auto;
                    max-height: 70px;
                    object-fit: contain;
                    display: block;
                ">
            </div>
        </a>
        <button class="sidebar-close js-sidebar-toggle" type="button" aria-label="Close navigation">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <li class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('home')); ?>">
                        <i class="fas fa-chart-pie"></i>Dashboard
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('pos.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('pos.index')); ?>">
                        <i class="fas fa-desktop"></i>POS
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('medicine-categories.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('medicine-categories.index')); ?>">
                        <i class="fas fa-tags"></i>Categories
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('medicine-types.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('medicine-types.index')); ?>">
                        <i class="fas fa-pills"></i>Types
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('units.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('units.index')); ?>">
                        <i class="fas fa-weight-scale"></i>Units
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('manufacturers.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('manufacturers.index')); ?>">
                        <i class="fas fa-industry"></i>Manufacturers
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('medicines.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('medicines.index')); ?>">
                        <i class="fas fa-capsules"></i>Medicines
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('suppliers.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('suppliers.index')); ?>">
                        <i class="fas fa-truck"></i>Suppliers
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('customers.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('customers.index')); ?>">
                        <i class="fas fa-users"></i>Customers
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('purchases.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('purchases.index')); ?>">
                        <i class="fas fa-shopping-cart"></i>Purchases
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('purchase-returns.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('purchase-returns.index')); ?>">
                        <i class="fas fa-undo-alt"></i>Purchase Returns
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('sales.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('sales.index')); ?>">
                        <i class="fas fa-cash-register"></i>Sales
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('sale-returns.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('sale-returns.index')); ?>">
                        <i class="fas fa-rotate-left"></i>Sale Returns
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('expense-categories.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('expense-categories.index')); ?>">
                        <i class="fas fa-tags"></i>Expense Categories
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('expenses.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('expenses.index')); ?>">
                        <i class="fas fa-money-bill"></i>Expenses
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('stock-adjustments.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('stock-adjustments.index')); ?>">
                        <i class="fas fa-sliders-h"></i>Stock Adjustments
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('expiry.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('expiry.index')); ?>">
                        <i class="fas fa-calendar-xmark"></i>Expiry Management
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('payments.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('payments.index')); ?>">
                        <i class="fas fa-credit-card"></i>Payments
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('stock-ledger.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('stock-ledger.index')); ?>">
                        <i class="fas fa-book"></i>Stock Ledger
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('inventory-reports.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('inventory-reports.index')); ?>">
                        <i class="fas fa-box"></i>Inventory Reports
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('sales-reports.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('sales-reports.index')); ?>">
                        <i class="fas fa-money-check-dollar"></i>Sales Reports
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('purchase-reports.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('purchase-reports.index')); ?>">
                        <i class="fas fa-money-check-dollar"></i>Purchase Reports
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('financial-reports.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('financial-reports.index')); ?>">
                        <i class="fas fa-money-check-dollar"></i>Financial Reports
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('profit-loss.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('profit-loss.index')); ?>">
                        <i class="fas fa-money-check-dollar"></i>Profit & Loss
                    </a>
                </li>
                
                <li class="<?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('settings.index')); ?>">
                        <i class="fas fa-cog"></i>Settings
                    </a>
                </li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.view')): ?>
                    <li class="<?php echo e(request()->routeIs('roles.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('roles.index')); ?>">
                            <i class="fas fa-user-shield"></i>Roles
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.view')): ?>
                    <li class="<?php echo e(request()->routeIs('permissions.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('permissions.index')); ?>">
                            <i class="fas fa-lock"></i>Permissions
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permissions.view')): ?>
                    <li class="<?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('users.index')); ?>">
                            <i class="fas fa-user-cog"></i>Users
                        </a>
                    </li>
                <?php endif; ?>

                <li class="<?php echo e(request()->routeIs('backups.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('backups.index')); ?>">
                        <i class="fas fa-cog"></i>Backups
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('audit-logs.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('audit-logs.index')); ?>">
                        <i class="fas fa-history"></i>Audit Logs
                    </a>
                </li>

                <li class="<?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('notifications.index')); ?>">
                        <i class="fas fa-bell"></i>Notifications
                    </a>

                </li>

            </ul>
        </nav>
    </div>
</aside>
<?php /**PATH C:\laragon\www\PharmaDesk\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>