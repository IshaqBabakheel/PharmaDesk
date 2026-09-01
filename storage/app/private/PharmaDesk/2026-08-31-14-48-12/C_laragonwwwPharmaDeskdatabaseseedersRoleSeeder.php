<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Create Roles
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        $pharmacist = Role::firstOrCreate([
            'name' => 'Pharmacist',
            'guard_name' => 'web',
        ]);

        $cashier = Role::firstOrCreate([
            'name' => 'Cashier',
            'guard_name' => 'web',
        ]);

        $storeKeeper = Role::firstOrCreate([
            'name' => 'Store Keeper',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(
            Permission::all()
        );

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions(
            Permission::whereNotIn('name', [

                'roles.force-delete',
                'permissions.force-delete',
                'users.force-delete',
                'backup.force-delete',

            ])->get()
        );

        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        */

        $manager->syncPermissions([

            // Dashboard
            'dashboard.view',

            // Medicines
            'medicines.view',
            'medicines.create',
            'medicines.edit',
            'medicines.delete',
            'medicines.import',
            'medicines.export',
            'medicines.adjust-stock',
            'medicines.print-barcode',
            'medicines.stock-history',
            'medicines.purchase-history',
            'medicines.sales-history',
            'medicines.view-cost-price',

            // Master Data
            'medicine-categories.view',
            'medicine-categories.create',
            'medicine-categories.edit',

            'medicine-types.view',
            'medicine-types.create',
            'medicine-types.edit',

            'units.view',
            'units.create',
            'units.edit',

            'manufacturers.view',
            'manufacturers.create',
            'manufacturers.edit',

            // Suppliers
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',

            // Customers
            'customers.view',
            'customers.create',
            'customers.edit',

            // Purchases
            'purchases.view',
            'purchases.create',
            'purchases.edit',

            // Sales
            'sales.view',
            'sales.create',
            'sales.edit',

            // Reports
            'reports.view',

            // Settings
            'settings.view',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Pharmacist
        |--------------------------------------------------------------------------
        */

        $pharmacist->syncPermissions([

            'dashboard.view',

            'medicines.view',
            'medicines.create',
            'medicines.edit',
            'medicines.print-barcode',
            'medicines.stock-history',
            'medicines.purchase-history',
            'medicines.sales-history',

            'medicine-categories.view',
            'medicine-types.view',
            'units.view',
            'manufacturers.view',

            'customers.view',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Cashier
        |--------------------------------------------------------------------------
        */

        $cashier->syncPermissions([

            'dashboard.view',

            'sales.view',
            'sales.create',

            'customers.view',
            'customers.create',

            'medicines.view',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Keeper
        |--------------------------------------------------------------------------
        */

        $storeKeeper->syncPermissions([

            'dashboard.view',

            'medicines.view',

            'medicines.adjust-stock',

            'medicines.stock-history',

            'suppliers.view',

            'purchases.view',

            'purchases.create',

            'medicine-categories.view',

            'medicine-types.view',

            'units.view',

            'manufacturers.view',

        ]);
    }
}