<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpiryController;
use App\Http\Controllers\FinancialReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // without import the auth::routes() works but the editor get confused.
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\ManufacturersController;
use App\Http\Controllers\MedicineCategoriesController;
use App\Http\Controllers\MedicinesController;
use App\Http\Controllers\MedicineTypesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockLedgerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return Redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    // Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/medicine-categories', [MedicineCategoriesController::class, 'index'])->name('medicine-categories.index');
    Route::get('/medicine-categories/create', [MedicineCategoriesController::class, 'create'])->name('medicine-categories.create');
    Route::post('/medicine-categories', [MedicineCategoriesController::class, 'store'])->name('medicine-categories.store');
    Route::get('/medicine-categories/{medicineCategory}/edit', [MedicineCategoriesController::class, 'edit'])->name('medicine-categories.edit');
    Route::delete('/medicine-categories/{medicineCategory}', [MedicineCategoriesController::class, 'destroy'])->name('medicine-categories.destroy');
    Route::put('/medicine-categories/{medicineCategory}', [MedicineCategoriesController::class, 'update'])->name('medicine-categories.update');

    // medicine types routes
    Route::prefix('medicine-types')->group(function () {

        Route::get('/', [MedicineTypesController::class, 'index'])
            ->name('medicine-types.index');

        Route::get('/create', [MedicineTypesController::class, 'create'])
            ->name('medicine-types.create');

        Route::post('/store', [MedicineTypesController::class, 'store'])
            ->name('medicine-types.store');

        Route::get('/{medicineType}/edit', [MedicineTypesController::class, 'edit'])
            ->name('medicine-types.edit');

        Route::put('/{medicineType}', [MedicineTypesController::class, 'update'])
            ->name('medicine-types.update');

        Route::delete('/{medicineType}', [MedicineTypesController::class, 'destroy'])
            ->name('medicine-types.destroy');

        Route::patch('/{id}/restore', [MedicineTypesController::class, 'restore'])
            ->name('medicine-types.restore');

        Route::delete('/{id}/force-delete', [MedicineTypesController::class, 'forceDelete'])
            ->name('medicine-types.force-delete');
    });

    // medicine units routes
    Route::prefix('units')->group(function () {

        Route::get('/', [UnitsController::class, 'index'])
            ->name('units.index');

        Route::get('/create', [UnitsController::class, 'create'])
            ->name('units.create');

        Route::post('/store', [UnitsController::class, 'store'])
            ->name('units.store');

        Route::get('/{unit}/edit', [UnitsController::class, 'edit'])
            ->name('units.edit');

        Route::put('/{unit}', [UnitsController::class, 'update'])
            ->name('units.update');

        Route::delete('/{unit}', [UnitsController::class, 'destroy'])
            ->name('units.destroy');

        Route::patch('/{id}/restore', [UnitsController::class, 'restore'])
            ->name('units.restore');

        Route::delete('/{id}/force-delete', [UnitsController::class, 'forceDelete'])
            ->name('units.force-delete');
    });

    // Manufacturer Routes
    Route::prefix('manufacturers')->group(function () {

        Route::get('/', [ManufacturersController::class, 'index'])
            ->name('manufacturers.index');

        Route::get('/create', [ManufacturersController::class, 'create'])
            ->name('manufacturers.create');

        Route::post('/store', [ManufacturersController::class, 'store'])
            ->name('manufacturers.store');

        Route::get('/{manufacturer}/edit', [ManufacturersController::class, 'edit'])
            ->name('manufacturers.edit');

        Route::put('/{manufacturer}', [ManufacturersController::class, 'update'])
            ->name('manufacturers.update');

        Route::delete('/{manufacturer}', [ManufacturersController::class, 'destroy'])
            ->name('manufacturers.destroy');

        Route::patch('/{id}/restore', [ManufacturersController::class, 'restore'])
            ->name('manufacturers.restore');

        Route::delete('/{id}/force-delete', [ManufacturersController::class, 'forceDelete'])
            ->name('manufacturers.force-delete');
    });

    // Medicines Routes
    Route::prefix('medicines')->group(function () {

        Route::get('/', [MedicinesController::class, 'index'])->name('medicines.index');

        Route::get('/datatable', [MedicinesController::class, 'datatable'])->name('medicines.datatable');

        Route::get('/create', [MedicinesController::class, 'create'])->name('medicines.create');

        Route::post('/store', [MedicinesController::class, 'store'])->name('medicines.store');

        Route::get('/{medicine}/edit', [MedicinesController::class, 'edit'])->name('medicines.edit');

        Route::put('/{medicine}', [MedicinesController::class, 'update'])->name('medicines.update');

        Route::delete('/{medicine}', [MedicinesController::class, 'destroy'])->name('medicines.destroy');

        Route::patch('/{id}/restore', [MedicinesController::class, 'restore'])->name('medicines.restore');

        Route::delete('/{id}/force-delete', [MedicinesController::class, 'forceDelete'])->name('medicines.force-delete');

        Route::get('/{medicine}', [MedicinesController::class, 'show'])->name('medicines.show');

        Route::get('/{medicine}/stock-history', [MedicinesController::class, 'stockHistory'])->name('medicines.stock-history');

        Route::get('/{medicine}/purchase-history', [MedicinesController::class, 'purchaseHistory'])->name('medicines.purchase-history');

        Route::get('/{medicine}/sales-history', [MedicinesController::class, 'salesHistory'])->name('medicines.sales-history');

        Route::get('/{medicine}/barcode', [MedicinesController::class, 'barcode'])->name('medicines.barcode');

        Route::post('/{medicine}/duplicate', [MedicinesController::class, 'duplicate'])->name('medicines.duplicate');
    });

    // Roles Routes
    Route::prefix('roles')->group(function () {

        Route::get('/', [RoleController::class, 'index'])->name('roles.index');

        Route::get('/datatable', [RoleController::class, 'datatable'])->name('roles.datatable');

        Route::get('/create', [RoleController::class, 'create'])->name('roles.create');

        Route::post('/store', [RoleController::class, 'store'])->name('roles.store');

        Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');

        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');

        Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        Route::patch('/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');

        Route::delete('/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('roles.force-delete');
    });

    // Permissions Routes
    Route::prefix('permissions')->group(function () {

        Route::get('/', [PermissionController::class, 'index'])->name('permissions.index');

        Route::get('/datatable', [PermissionController::class, 'datatable'])->name('permissions.datatable');

        Route::get('/actions', [PermissionController::class, 'actions'])->name('permissions.actions');

        Route::get('/create', [PermissionController::class, 'create'])->name('permissions.create');

        Route::post('/store', [PermissionController::class, 'store'])->name('permissions.store');

        Route::get('/{permission}', [PermissionController::class, 'show'])->name('permissions.show');

        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');

        Route::put('/{permission}', [PermissionController::class, 'update'])->name('permissions.update');

        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        Route::patch('/{id}/restore', [PermissionController::class, 'restore'])->name('permissions.restore');

        Route::delete('/{id}/force-delete', [PermissionController::class, 'forceDelete'])->name('permissions.force-delete');
    });

    // Users Roles
    Route::prefix('users')->group(function () {

        Route::get('/', [UserController::class, 'index'])->name('users.index');

        Route::get('/datatable', [UserController::class, 'datatable'])->name('users.datatable');

        Route::get('/create', [UserController::class, 'create'])->name('users.create');

        Route::post('/store', [UserController::class, 'store'])->name('users.store');

        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');

        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');

        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::patch('/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

        Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    });

    // Suppliers Routes
    Route::prefix('suppliers')->group(function () {

        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');

        Route::get('/datatable', [SupplierController::class, 'datatable'])->name('suppliers.datatable');

        Route::get('/create', [SupplierController::class, 'create'])->name('suppliers.create');

        Route::post('/store', [SupplierController::class, 'store'])->name('suppliers.store');

        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');

        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');

        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');

        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        Route::patch('/{id}/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');

        Route::delete('/{id}/force-delete', [SupplierController::class, 'forceDelete'])->name('suppliers.force-delete');
    });

    // Customers Routes
    Route::prefix('customers')->group(function () {

        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');

        Route::get('/datatable', [CustomerController::class, 'datatable'])->name('customers.datatable');

        Route::get('/{customer}/items', [CustomerController::class, 'purchaseItems'])->name('customers.purchase-items');

        Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');

        Route::post('/store', [CustomerController::class, 'store'])->name('customers.store');

        Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show');

        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');

        Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');

        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::patch('/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');

        Route::delete('/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');
    });

    // Purchase Routes
    Route::prefix('purchases')->group(function () {

        Route::get('/', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/next-reference', [PurchaseController::class, 'nextReference'])->name('purchases.next-reference');
        Route::get('/datatable', [PurchaseController::class, 'datatable'])->name('purchases.datatable');
        Route::get('/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/store', [PurchaseController::class, 'store'])->name('purchases.store');

        // Route::patch('/{purchase}/payment', [PurchaseController::class, 'updatePayment'])->name('purchases.update-payment');
        Route::patch('/{purchase}/payment', [PaymentController::class, 'purchasePayment'])->name('purchases.payment');
        Route::patch('/{purchase}/complete', [PurchaseController::class, 'complete'])->name('purchases.complete');
        Route::patch('/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');

        Route::get('/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
        Route::get('/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
        Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
        Route::patch('/{id}/restore', [PurchaseController::class, 'restore'])->name('purchases.restore');
        Route::delete('/{id}/force-delete', [PurchaseController::class, 'forceDelete'])->name('purchases.force-delete');
    });

    // Purchase Returns Routes
    Route::prefix('purchase-returns')->group(function () {

        Route::get('/', [PurchaseReturnController::class, 'index'])->name('purchase-returns.index');
        Route::get('/datatable', [PurchaseReturnController::class, 'datatable'])->name('purchase-returns.datatable');

        Route::get('/{purchase}/items', [PurchaseReturnController::class, 'purchaseItems'])->name('purchase-returns.purchase-items');
        Route::get('/suppliers', [PurchaseReturnController::class, 'suppliers'])->name('purchase-returns.suppliers');
        Route::get('/suppliers/{supplier}/purchases', [PurchaseReturnController::class, 'supplierPurchases'])->name('purchase-returns.supplier-purchases');

        Route::get('/create', [PurchaseReturnController::class, 'create'])->name('purchase-returns.create');
        Route::post('/store', [PurchaseReturnController::class, 'store'])->name('purchase-returns.store');
        Route::get('/{purchaseReturn}', [PurchaseReturnController::class, 'show'])->name('purchase-returns.show');
        Route::get('/{purchaseReturn}/edit', [PurchaseReturnController::class, 'edit'])->name('purchase-returns.edit');
        Route::put('/{purchaseReturn}', [PurchaseReturnController::class, 'update'])->name('purchase-returns.update');
        Route::delete('/{purchaseReturn}', [PurchaseReturnController::class, 'destroy'])->name('purchase-returns.destroy');

        Route::patch('/{id}/restore', [PurchaseReturnController::class, 'restore'])->name('purchase-returns.restore');
        Route::delete('/{id}/force-delete', [PurchaseReturnController::class, 'forceDelete'])->name('purchase-returns.force-delete');
    });

    // Sales Routes
    Route::prefix('sales')->group(function () {

        Route::get('/', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/datatable', [SaleController::class, 'datatable'])->name('sales.datatable');
        Route::get('/medicine/{id}/stock', [SaleController::class, 'medicineStock'])->name('sales.purchase-items');
        Route::get('/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/store', [SaleController::class, 'store'])->name('sales.store');

        Route::patch('/{sale}/complete', [SaleController::class, 'complete'])->name('sales.complete');
        Route::patch('/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
        // Route::patch('/{sale}/payment', [SaleController::class, 'updatePayment'])->name('sales.update-payment');
        Route::patch('/{sale}/payment', [PaymentController::class, 'salePayment'])->name('sales.payment');

        Route::get('/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
        Route::put('/{sale}', [SaleController::class, 'update'])->name('sales.update');
        Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
        Route::patch('/{id}/restore', [SaleController::class, 'restore'])->name('sales.restore');
        Route::delete('/{id}/force-delete', [SaleController::class, 'forceDelete'])->name('sales.force-delete');
    });

    // Sale Returns Routes
    Route::prefix('sale-returns')->group(function () {
        Route::get('/', [SaleReturnController::class, 'index'])->name('sale-returns.index');
        Route::get('/datatable', [SaleReturnController::class, 'datatable'])->name('sale-returns.datatable');
        Route::patch('/{saleReturn}/complete', [SaleReturnController::class, 'complete'])->name('sale-returns.complete');
        Route::patch('/{saleReturn}/cancel', [SaleReturnController::class, 'cancel'])->name('sale-returns.cancel');
        Route::get('/{sale}/items', [SaleReturnController::class, 'saleItems'])->name('sale-returns.sale-items');
        Route::get('/create', [SaleReturnController::class, 'create'])->name('sale-returns.create');
        Route::post('/store', [SaleReturnController::class, 'store'])->name('sale-returns.store');
        Route::get('/{saleReturn}', [SaleReturnController::class, 'show'])->name('sale-returns.show');
        Route::get('/{saleReturn}/edit', [SaleReturnController::class, 'edit'])->name('sale-returns.edit');
        Route::put('/{saleReturn}', [SaleReturnController::class, 'update'])->name('sale-returns.update');
        Route::delete('/{saleReturn}', [SaleReturnController::class, 'destroy'])->name('sale-returns.destroy');
        Route::patch('/{saleReturn}/restore', [SaleReturnController::class, 'restore'])
            ->withTrashed()
            ->name('sale-returns.restore');
        Route::delete('/{saleReturn}/force-delete', [SaleReturnController::class, 'forceDelete'])
            ->withTrashed()
            ->name('sale-returns.force-delete');
    });

    // Expense Routes
    Route::prefix('expenses')->group(function () {
        Route::get('/',   [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/datatable',   [ExpenseController::class, 'datatable'])->name('expenses.datatable');
        Route::get('/statistics', [ExpenseController::class, 'statistics'])->name('expenses.statistics');
        Route::get('/create',   [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/store',  [ExpenseController::class, 'store'])->name('expenses.store');
        Route::patch('/{expense}/complete', [ExpenseController::class, 'complete'])->name('expenses.complete');
        Route::patch('/{expense}/cancel', [ExpenseController::class, 'cancel'])->name('expenses.cancel');
        Route::get('/{expense}',   [ExpenseController::class, 'show'])->name('expenses.show');
        Route::get('/{expense}/edit',   [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('/{expense}',   [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
        Route::patch('/{id}/restore', [ExpenseController::class, 'restore'])->name('expenses.restore');
        Route::delete('/{id}/force-delete', [ExpenseController::class, 'forceDelete'])->name('expenses.force-delete');
    });

    // Expense Category Routes
    Route::prefix('expense-categories')->group(function () {
        Route::get('/', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
        Route::get('/datatable', [ExpenseCategoryController::class, 'datatable'])->name('expense-categories.datatable');
        Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('expense-categories.create');
        Route::post('/store', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
        Route::get('/{expenseCategory}/edit', [ExpenseCategoryController::class, 'edit'])->name('expense-categories.edit');
        Route::put('/{expenseCategory}', [ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
        Route::delete('/{expenseCategory}', [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
        Route::patch('/{id}/restore', [ExpenseCategoryController::class, 'restore'])->name('expense-categories.restore');
        Route::delete('/{id}/force-delete', [ExpenseCategoryController::class, 'forceDelete'])->name('expense-categories.force-delete');
    });

    // Stock Adjustment Routes
    Route::prefix('stock-adjustments')->group(function () {

        Route::get('/', [StockAdjustmentController::class, 'index'])->name('stock-adjustments.index');
        Route::get('/datatable', [StockAdjustmentController::class, 'datatable'])->name('stock-adjustments.datatable');
        Route::get('/create', [StockAdjustmentController::class, 'create'])->name('stock-adjustments.create');
        Route::post('/store', [StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');

        Route::get('/medicine/{medicine}/batches', [StockAdjustmentController::class, 'medicineBatches'])->name('stock-adjustments.medicine-batches');
        Route::patch('/{stockAdjustment}/complete', [StockAdjustmentController::class, 'complete'])->name('stock-adjustments.complete');
        Route::patch('/{stockAdjustment}/cancel', [StockAdjustmentController::class, 'cancel'])->name('stock-adjustments.cancel');

        Route::get('/{stockAdjustment}', [StockAdjustmentController::class, 'show'])->name('stock-adjustments.show');
        Route::get('/{stockAdjustment}/edit', [StockAdjustmentController::class, 'edit'])->name('stock-adjustments.edit');
        Route::put('/{stockAdjustment}', [StockAdjustmentController::class, 'update'])->name('stock-adjustments.update');
        Route::delete('/{stockAdjustment}', [StockAdjustmentController::class, 'destroy'])->name('stock-adjustments.destroy');
        Route::patch('/{id}/restore', [StockAdjustmentController::class, 'restore'])->name('stock-adjustments.restore');
        Route::delete('/{id}/force-delete', [StockAdjustmentController::class, 'forceDelete'])->name('stock-adjustments.force-delete');
    });

    // Expiry Management Routes
    Route::prefix('expiry')->group(function () {
        Route::get('/', [ExpiryController::class, 'index'])->name('expiry.index');
        Route::get('/datatable', [ExpiryController::class, 'datatable'])->name('expiry.datatable');
        Route::get('/statistics', [ExpiryController::class, 'statistics'])->name('expiry.statistics');
        Route::get('/medicine/{medicine}', [ExpiryController::class, 'medicine'])->name('expiry.medicine');
    });

    // Pos Routes
    Route::prefix('pos')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('pos.index');
        // Product listing / grid
        Route::get('/medicines', [PosController::class, 'medicines'])->name('pos.medicines');
        Route::get('/cart', [PosController::class, 'cart'])->name('pos.cart');
        Route::get('/customers', [PosController::class, 'customers'])->name('pos.customers');
        Route::get('/medicines/search', [PosController::class, 'searchMedicines'])->name('pos.medicines.search');
        Route::post('/cart/items', [PosController::class, 'addItem'])->name('pos.cart.items.store');
        Route::put('/cart/items/{cartItem}', [PosController::class, 'updateItem'])->name('pos.cart.items.update');
        Route::delete('/cart/items/{cartItem}', [PosController::class, 'removeItem'])->name('pos.cart.items.destroy');
        Route::patch('/cart', [PosController::class, 'updateCart'])->name('pos.cart.update');
        Route::post('/hold', [PosController::class, 'hold'])->name('pos.hold');
        Route::post('/cancel', [PosController::class, 'cancel'])->name('pos.cancel');
        Route::post('/resume/{cart}', [PosController::class, 'resume'])->name('pos.resume');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    });

    // Payments Routes
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/datatable', [PaymentController::class, 'datatable'])->name('payments.datatable');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        Route::patch('/{id}/restore', [PaymentController::class, 'restore'])->name('payments.restore');
        Route::delete('/{id}/force-delete', [PaymentController::class, 'forceDelete'])->name('payments.force-delete');
    });

    // Stock Ledger Routes
    Route::prefix('stock-ledger')->group(function () {
        Route::get('/', [StockLedgerController::class, 'index'])->name('stock-ledger.index');
        Route::get('/datatable', [StockLedgerController::class, 'datatable'])->name('stock-ledger.datatable');
        Route::get('/medicine/{medicineId}', [StockLedgerController::class, 'medicine'])->name('stock-ledger.medicine');
        Route::get('/medicine/{medicineId}/batch/{batchNumber}', [StockLedgerController::class, 'batch'])->name('stock-ledger.batch');
    });

    // Inventory Reports Routes
    Route::prefix('inventory-reports')->group(function () {
        Route::get('/', [InventoryReportController::class, 'index'])->name('inventory-reports.index');
        Route::get('/datatable', [InventoryReportController::class, 'datatable'])->name('inventory-reports.datatable');
        Route::get('/statistics', [InventoryReportController::class, 'statistics'])->name('inventory-reports.statistics');
        Route::get('/medicine/{medicineId}', [InventoryReportController::class, 'medicine'])->name('inventory-reports.medicine');
    });

    // Sales Reports Routes
    Route::prefix('sales-reports')->group(function () {
        Route::get('/', [SalesReportController::class, 'index'])->name('sales-reports.index');
        Route::get('/datatable', [SalesReportController::class, 'datatable'])->name('sales-reports.datatable');
        Route::get('/statistics', [SalesReportController::class, 'statistics'])->name('sales-reports.statistics');
        Route::get('/{saleId}', [SalesReportController::class, 'show'])->name('sales-reports.show');
    });

    // Purchase Reports Routes
    Route::prefix('purchase-reports')->group(function () {
        Route::get('/', [PurchaseReportController::class, 'index'])->name('purchase-reports.index');
        Route::get('/datatable', [PurchaseReportController::class, 'datatable'])->name('purchase-reports.datatable');
        Route::get('/statistics', [PurchaseReportController::class, 'statistics'])->name('purchase-reports.statistics');
        Route::get('/{purchaseId}', [PurchaseReportController::class, 'show'])->name('purchase-reports.show');
    });

    // Financial Reports Routes
    Route::prefix('financial-reports')->group(function () {
        Route::get('/', [FinancialReportController::class, 'index'])->name('financial-reports.index');
        Route::get('/summary', [FinancialReportController::class, 'summary'])->name('financial-reports.summary');
        Route::get('/datatable', [FinancialReportController::class, 'datatable'])->name('financial-reports.datatable');
    });

    // Profit & Loss Routes
    Route::prefix('profit-loss')->group(function () {
        Route::get('/', [ProfitLossController::class, 'index'])->name('profit-loss.index');
        Route::get('/summary', [ProfitLossController::class, 'summary'])->name('profit-loss.summary');
        Route::get('/monthly', [ProfitLossController::class, 'monthly'])->name('profit-loss.monthly');
    });

    //Receipt Routes
    Route::prefix('sales/{sale}/receipt')->group(function () {
        Route::get('/a4', [ReceiptController::class, 'a4'])->name('sales.receipt.a4');
        Route::get('/thermal', [ReceiptController::class, 'thermal'])->name('sales.receipt.thermal');
    });

    // Backup Routes
    Route::prefix('backups')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/create', [BackupController::class, 'create'])->name('backups.create');
        Route::post('/cleanup', [BackupController::class, 'cleanup'])->name('backups.cleanup');
        Route::post('/{filename}/restore', [BackupController::class, 'restore'])->name('backups.restore');
        Route::get('/{filename}/details', [BackupController::class, 'show'])->name('backups.show');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
    });

    // Audit Logs Routes
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/datatable', [AuditLogController::class, 'datatable'])->name('datatable');
        Route::get('/{id}', [AuditLogController::class, 'show'])->name('show');
    });


    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');                                                 
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });
});
