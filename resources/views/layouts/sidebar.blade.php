<aside class="menu-sidebar" id="main-sidebar">
    <div class="logo" style="background: transparent; padding: 15px 15px 12px 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
        <a href="{{ route('home') }}" class="logo-link" aria-label="PharmaDesk home">
            <div style="background: #ffffff; padding: 8px 12px; border-radius: 10px; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2); display: flex; justify-content: center; align-items: center; transition: all 0.3s ease;">
                <img src="{{ asset('images/logo.png') }}" alt="PharmaDesk Logo" style="width: 100%; max-width: 200px; height: auto; max-height: 60px; object-fit: contain; display: block;">
            </div>
        </a>
        <button class="sidebar-close js-sidebar-toggle" type="button" aria-label="Close navigation">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list" id="sidebarNav">
                
                <!-- MAIN SECTION -->
                <li class="section-label">
                    <span>MAIN</span>
                </li>
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <a href="{{ route('pos.index') }}">
                        <i class="fas fa-desktop"></i>
                        <span>POS</span>
                    </a>
                </li>

                <!-- MASTER DATA SECTION -->
                <li class="section-label">
                    <span>MASTER DATA</span>
                </li>
                <li class="{{ request()->routeIs('medicine-categories.*') ? 'active' : '' }}">
                    <a href="{{ route('medicine-categories.index') }}">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medicine-types.*') ? 'active' : '' }}">
                    <a href="{{ route('medicine-types.index') }}">
                        <i class="fas fa-pills"></i>
                        <span>Types</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('units.*') ? 'active' : '' }}">
                    <a href="{{ route('units.index') }}">
                        <i class="fas fa-weight-scale"></i>
                        <span>Units</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('manufacturers.*') ? 'active' : '' }}">
                    <a href="{{ route('manufacturers.index') }}">
                        <i class="fas fa-industry"></i>
                        <span>Manufacturers</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                    <a href="{{ route('medicines.index') }}">
                        <i class="fas fa-capsules"></i>
                        <span>Medicines</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <a href="{{ route('suppliers.index') }}">
                        <i class="fas fa-truck"></i>
                        <span>Suppliers</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <a href="{{ route('customers.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li>

                <!-- TRANSACTIONS SECTION -->
                <li class="section-label">
                    <span>TRANSACTIONS</span>
                </li>
                <li class="{{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <a href="{{ route('purchases.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Purchases</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('purchase-returns.*') ? 'active' : '' }}">
                    <a href="{{ route('purchase-returns.index') }}">
                        <i class="fas fa-undo-alt"></i>
                        <span>Purchase Returns</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <a href="{{ route('sales.index') }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Sales</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('sale-returns.*') ? 'active' : '' }}">
                    <a href="{{ route('sale-returns.index') }}">
                        <i class="fas fa-rotate-left"></i>
                        <span>Sale Returns</span>
                    </a>
                </li>

                <!-- FINANCIAL MANAGEMENT SECTION -->
                <li class="section-label">
                    <span>FINANCIAL</span>
                </li>
                <li class="{{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                    <a href="{{ route('expense-categories.index') }}">
                        <i class="fas fa-tags"></i>
                        <span>Expense Categories</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <a href="{{ route('expenses.index') }}">
                        <i class="fas fa-money-bill"></i>
                        <span>Expenses</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <a href="{{ route('payments.index') }}">
                        <i class="fas fa-credit-card"></i>
                        <span>Payments</span>
                    </a>
                </li>

                <!-- INVENTORY SECTION -->
                <li class="section-label">
                    <span>INVENTORY</span>
                </li>
                <li class="{{ request()->routeIs('stock-adjustments.*') ? 'active' : '' }}">
                    <a href="{{ route('stock-adjustments.index') }}">
                        <i class="fas fa-sliders-h"></i>
                        <span>Stock Adjustments</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('expiry.*') ? 'active' : '' }}">
                    <a href="{{ route('expiry.index') }}">
                        <i class="fas fa-calendar-xmark"></i>
                        <span>Expiry Management</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('stock-ledger.*') ? 'active' : '' }}">
                    <a href="{{ route('stock-ledger.index') }}">
                        <i class="fas fa-book"></i>
                        <span>Stock Ledger</span>
                    </a>
                </li>

                <!-- REPORTS SECTION -->
                <li class="section-label">
                    <span>REPORTS</span>
                </li>
                <li class="{{ request()->routeIs('inventory-reports.*') ? 'active' : '' }}">
                    <a href="{{ route('inventory-reports.index') }}">
                        <i class="fas fa-box"></i>
                        <span>Inventory Reports</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('sales-reports.*') ? 'active' : '' }}">
                    <a href="{{ route('sales-reports.index') }}">
                        <i class="fas fa-money-check-dollar"></i>
                        <span>Sales Reports</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('purchase-reports.*') ? 'active' : '' }}">
                    <a href="{{ route('purchase-reports.index') }}">
                        <i class="fas fa-money-check-dollar"></i>
                        <span>Purchase Reports</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('financial-reports.*') ? 'active' : '' }}">
                    <a href="{{ route('financial-reports.index') }}">
                        <i class="fas fa-money-check-dollar"></i>
                        <span>Financial Reports</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('profit-loss.*') ? 'active' : '' }}">
                    <a href="{{ route('profit-loss.index') }}">
                        <i class="fas fa-money-check-dollar"></i>
                        <span>Profit & Loss</span>
                    </a>
                </li>


                <!-- ADMINISTRATION SECTION -->
                <li class="section-label">
                    <span>ADMINISTRATION</span>
                </li>
                @can('permissions.view')
                    <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}">
                            <i class="fas fa-user-shield"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <a href="{{ route('permissions.index') }}">
                            <i class="fas fa-lock"></i>
                            <span>Permissions</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="fas fa-user-cog"></i>
                            <span>Users</span>
                        </a>
                    </li>
                @endcan

                <!-- SYSTEMS -->
                <li class="section-label">
                    <span>SYSTEM</span>
                </li>
                <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('backups.*') ? 'active' : '' }}">
                    <a href="{{ route('backups.index') }}">
                        <i class="fas fa-archive"></i>
                        <span>Backups</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                    <a href="{{ route('audit-logs.index') }}">
                        <i class="fas fa-history"></i>
                        <span>Audit Logs</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <a href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>