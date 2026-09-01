<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Base data first
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            
            // Master data
            MedicineCategorySeeder::class,
            MedicineTypeSeeder::class,
            UnitSeeder::class,
            ManufacturerSeeder::class,
            
            // Business partners
            SupplierSeeder::class,  // Suppliers before purchases
            CustomerSeeder::class,  // Customers before sales
            
            // Products
            MedicineSeeder::class,
            
            // Transactions
            PurchaseSeeder::class,
            PurchaseItemSeeder::class,
            SaleSeeder::class,
        ]);
    }
}
