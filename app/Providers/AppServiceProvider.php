<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineType;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Policies\CustomerPolicy;
use App\Policies\ManufacturerPolicy;
use App\Policies\MedicineCategoryPolicy;
use App\Policies\MedicinePolicy;
use App\Policies\MedicineTypePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PurchasePolicy;
use App\Policies\PurchaseReturnPolicy;
use App\Policies\RolePolicy;
use App\Policies\SalePolicy;
use App\Policies\StockAdjustmentPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\UnitPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Facades\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        Gate::policy(Unit::class, UnitPolicy::class);
        Gate::policy(MedicineType::class, MedicineTypePolicy::class);
        Gate::policy(MedicineCategory::class, MedicineCategoryPolicy::class);
        Gate::policy(Manufacturer::class, ManufacturerPolicy::class);
        Gate::policy(Medicine::class, MedicinePolicy::class);

        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Supplier::class, SupplierPolicy::class);

        Gate::policy(Purchase::class, PurchasePolicy::class);
        Gate::policy(PurchaseReturn::class, PurchaseReturnPolicy::class);
        Gate::policy(Sale::class, SalePolicy::class);
        Gate::policy(StockAdjustment::class, StockAdjustmentPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);

        Gate::policy(User::class, UserPolicy::class);

        Activity::beforeLogging(
            function (\Spatie\Activitylog\Contracts\Activity $activity): void {
                if (app()->runningInConsole()) {
                    return;
                }

                $properties = $activity->properties ?? collect();

                $activity->properties = $properties
                    ->put('ip', request()->ip())
                    ->put('user_agent', request()->userAgent())
                    ->put('url', request()->fullUrl());
            }
        );
    }
}