<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $modules = [

            'dashboard',

            'settings',

            'roles',

            'permissions',

            'users',

            'medicine-categories',

            'medicine-types',

            'units',

            'manufacturers',

            'medicines',

            'suppliers',

            'customers',

            'purchases',

            'purchase-returns',

            'sales',

            'sale-returns',

            'stocks',

            'stock-adjustments',

            'expenses',

            'reports',

            'companies',

            'backups',

        ];

        $actions = [

            'view',

            'create',

            'edit',

            'delete',

            'restore',

            'force-delete',

            'import',

            'export',

        ];

        foreach ($modules as $module) {

            foreach ($actions as $action) {

                Permission::firstOrCreate([

                    'name' => "{$module}.{$action}",

                    'guard_name' => 'web',

                ]);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Medicine Special Permissions
        |--------------------------------------------------------------------------
        */

        $medicinePermissions = [

            'medicines.print-barcode',

            'medicines.stock-history',

            'medicines.purchase-history',

            'medicines.sales-history',

            'medicines.adjust-stock',

            'medicines.view-cost-price',

            'medicines.duplicate',

        ];

        foreach ($medicinePermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Sales Special Permissions
        |--------------------------------------------------------------------------
        */

        $salesPermissions = [

            'sales.cancel',           // Cancel a sale
            'sales.complete',         // Complete a sale
            'sales.update-payment',   // Update payment status
            'sales.print-invoice',    // Print invoice
            'sales.download-pdf',     // Download PDF
            'sales.send-email',       // Send invoice via email
            'sales.view-payment',     // View payment details
            'sales.duplicate',        // Duplicate a sale
            'sales.convert-to-quote', // Convert to quotation
            'sales.view-profit',      // View profit margin
            'sales.return',           // Process return
            'sales.bulk-delete',      // Bulk delete
            'sales.bulk-export',      // Bulk export

        ];

        foreach ($salesPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Sale Returns Special Permissions
        |--------------------------------------------------------------------------
        */

        $saleReturnsPermissions = [

            'sale-returns.complete',   // Complete a sale return
            'sale-returns.cancel',     // Cancel a sale return

        ];

        foreach ($saleReturnsPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        $userPermissions = [

            'users.assign-role',

            'users.remove-role',

            'users.assign-permission',

        ];

        foreach ($userPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Purchase Special Permissions
        |--------------------------------------------------------------------------
        */

        $purchasePermissions = [

            'purchases.receive',       // Receive purchase order
            'purchases.return',        // Return purchase
            'purchases.print-order',   // Print purchase order
            'purchases.download-pdf',  // Download PDF
            'purchases.view-profit',   // View profit margin
            'purchases.duplicate',     // Duplicate purchase
            'purchases.bulk-delete',   // Bulk delete
            'purchases.bulk-export',   // Bulk export

        ];

        foreach ($purchasePermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Customer Special Permissions
        |--------------------------------------------------------------------------
        */

        $customerPermissions = [

            'customers.view-sales',     // View customer sales history
            'customers.view-payments',  // View customer payment history
            'customers.view-returns',   // View customer returns
            'customers.duplicate',      // Duplicate customer
            'customers.bulk-import',    // Bulk import customers
            'customers.bulk-export',    // Bulk export customers
            'customers.send-email',     // Send email to customer
            'customers.send-sms',       // Send SMS to customer

        ];

        foreach ($customerPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Supplier Special Permissions
        |--------------------------------------------------------------------------
        */

        $supplierPermissions = [

            'suppliers.view-purchases',  // View supplier purchase history
            'suppliers.view-payments',   // View supplier payment history
            'suppliers.duplicate',       // Duplicate supplier
            'suppliers.bulk-import',     // Bulk import suppliers
            'suppliers.bulk-export',     // Bulk export suppliers
            'suppliers.send-email',      // Send email to supplier
            'suppliers.send-sms',        // Send SMS to supplier

        ];

        foreach ($supplierPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Medicine Additional Permissions
        |--------------------------------------------------------------------------
        */

        $medicineAdditionalPermissions = [

            'medicines.view-stock',      // View stock details
            'medicines.view-expiry',     // View expiry dates
            'medicines.bulk-import',     // Bulk import medicines
            'medicines.bulk-export',     // Bulk export medicines
            'medicines.update-price',    // Update selling price
            'medicines.update-cost',     // Update cost price
            'medicines.reorder',         // Reorder stock
            'medicines.view-reorder',    // View reorder levels

        ];

        foreach ($medicineAdditionalPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Report Permissions
        |--------------------------------------------------------------------------
        */

        $reportPermissions = [

            'reports.sales-daily',       // Daily sales report
            'reports.sales-monthly',     // Monthly sales report
            'reports.sales-yearly',      // Yearly sales report
            'reports.purchases-daily',   // Daily purchase report
            'reports.purchases-monthly', // Monthly purchase report
            'reports.stock-report',      // Stock report
            'reports.profit-loss',       // Profit/Loss report
            'reports.tax-report',        // Tax report
            'reports.customer-report',   // Customer report
            'reports.supplier-report',   // Supplier report
            'reports.export-excel',      // Export to Excel
            'reports.export-pdf',        // Export to PDF
            'reports.export-csv',        // Export to CSV

        ];

        foreach ($reportPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Stock Management Special Permissions
        |--------------------------------------------------------------------------
        */

        $stockPermissions = [

            'stocks.adjust',             // Adjust stock
            'stocks.transfer',           // Transfer stock between locations
            'stocks.audit',              // Stock audit
            'stocks.count',              // Stock count (physical count)
            'stocks.import',             // Import stock
            'stocks.export',             // Export stock
            'stocks.history',            // View stock history
            'stocks.report',             // Stock report

        ];

        foreach ($stockPermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Expense Permissions
        |--------------------------------------------------------------------------
        */

        $expensePermissions = [

            'expenses.approve',          // Approve expense
            'expenses.reject',           // Reject expense
            'expenses.bulk-delete',      // Bulk delete
            'expenses.bulk-export',      // Bulk export
            'expenses.view-report',      // View expense report

        ];

        foreach ($expensePermissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);

        }
    }
}