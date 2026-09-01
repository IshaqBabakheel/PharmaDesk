<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ResetPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Clear existing data
        Permission::truncate();
        Role::truncate();
        \DB::table('role_has_permissions')->truncate();
        \DB::table('model_has_roles')->truncate();
        \DB::table('model_has_permissions')->truncate();

        // Run your seeders
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
    }
}