<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create a new sale.
     */
    public function store(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $this->validateSaleData($data);

            /*
            |--------------------------------------------------------------------------
            | 4. Calculate totals
            |--------------------------------------------------------------------------
            */
            $grandTotal = $this->calculateGrandTotal($data);

            $paidAmount = (float) ($data['paid_amount'] ?? 0);

            $data['grand_total'] = $grandTotal;

            $sale = Sale::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_id' => $data['customer_id'] ?? null,
                'doctor_name' => $data['doctor_name'] ?? null,
                'sale_date' => $data['sale_date'],
                'subtotal' => $data['subtotal'] ?? $this->calculateSubtotal($data['items']),
                'discount_type' => $data['discount_type'],
                'discount' => $data['discount'] ?? 0,
                'tax_type' => $data['tax_type'],
                'tax' => $data['tax'] ?? 0,
                'shipping' => $data['shipping'] ?? 0,
                'other_charges' => $data['other_charges'] ?? 0,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'due_amount' => $this->calculateDue($data['grand_total'] ?? 0, $data['paid_amount'] ?? 0),
                'payment_status' => $this->calculatePaymentStatus($data['grand_total'] ?? 0, $data['paid_amount'] ?? 0),
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            if ($sale->status === 'completed') {
                $this->createSaleItems($sale, $data['items']);
                $this->stockService->deductStockFromSale($sale->load('items'));
            } else {
                $this->createDraftSaleItems($sale, $data['items']);
            }

            return $sale->load(['customer', 'items.medicine']);
        });
    }

    /**
     * Validate sale business rules.
     */
    // protected function validateSaleData(array $data): void
    // {

    //     if (empty($data['items'])) {
    //         throw ValidationException::withMessages([
    //             'items' => ['Sale must contain at least one medicine.']
    //         ]);
    //     }

    //     if ($data['status'] === 'completed') {
    //         $this->stockService->validateSaleItems($data['items']);
    //     }


    //     $grandTotal = (float) ($data['grand_total'] ?? 0);
    //     $paidAmount = (float) ($data['paid_amount'] ?? 0);

    //     if ($paidAmount > $grandTotal) {
    //         throw ValidationException::withMessages([
    //             'paid_amount' => ['Paid amount cannot be greater than the grand total.']
    //         ]);
    //     }
    // }
    protected function validateSaleData(array $data): void
    {
        if (empty($data['items'])) {
            throw ValidationException::withMessages([
                'items' => ['Sale must contain at least one medicine.']
            ]);
        }

        $grandTotal = $this->calculateGrandTotal($data);
        $paidAmount = (float) ($data['paid_amount'] ?? 0);

        if ($paidAmount > $grandTotal) {
            throw ValidationException::withMessages([
                'paid_amount' => ['Paid amount cannot be greater than the grand total.']
            ]);
        }

        if ($data['status'] === 'completed') {
            $this->stockService->validateSaleItems($data['items']);
        }
    }

    protected function calculateGrandTotal(array $data): float
    {
        $subtotal = $this->calculateSubtotal($data['items']);

        $discount = (float) ($data['discount'] ?? 0);
        $tax = (float) ($data['tax'] ?? 0);
        $shipping = (float) ($data['shipping'] ?? 0);
        $otherCharges = (float) ($data['other_charges'] ?? 0);

        if (($data['discount_type'] ?? 'fixed') === 'percentage') {
            $discountAmount = $subtotal * $discount / 100;
        } else {
            $discountAmount = $discount;
        }

        $afterDiscount = max($subtotal - $discountAmount, 0);

        if (($data['tax_type'] ?? 'fixed') === 'percentage') {
            $taxAmount = $afterDiscount * $tax / 100;
        } else {
            $taxAmount = $tax;
        }

        return max($afterDiscount + $taxAmount + $shipping + $otherCharges, 0);
    }

    /**
     * Create sale items using FIFO batches.
     */
    // protected function createSaleItems(Sale $sale, array $items): void
    // {
    //     foreach ($items as $item) {
    //         $allocations = $this->stockService->allocateFIFO(
    //             (int) $item['medicine_id'],
    //             (int) $item['quantity']
    //         );

    //         foreach ($allocations as $allocation) {

    //             $quantity = (int) $allocation['quantity'];
    //             $sellingPrice = (float) $item['selling_price'];
    //             $discount = (float) ($item['discount'] ?? 0);
    //             $tax = (float) ($item['tax'] ?? 0);

    //             SaleItem::create([
    //                 'sale_id' => $sale->id,
    //                 'medicine_id' => $allocation['medicine_id'],
    //                 'purchase_item_id' => $allocation['purchase_item_id'],
    //                 'batch_number' => $allocation['batch_number'],
    //                 'expiry_date' => $allocation['expiry_date'],
    //                 'quantity' => $quantity,
    //                 'free_quantity' => 0,
    //                 'purchase_price' => $allocation['purchase_price'],
    //                 'selling_price' => $sellingPrice,
    //                 'discount' => $discount,
    //                 'tax' => $tax,
    //                 'total' => $this->calculateItemTotal($quantity, $sellingPrice, $discount, $tax),
    //             ]);
    //         }
    //     }
    // }
    /**
     * Create completed sale items using FEFO batches.
     */
    // protected function createSaleItems(Sale $sale, array $items): void 
    // {

    //     foreach ($items as $item) {

    //         $allocations = $this->stockService->allocateFIFO(
    //             (int) $item['medicine_id'],
    //             (int) $item['quantity']
    //         );


    //         $remainingFree = (int) ($item['free_quantity'] ?? 0);


    //         foreach ($allocations as $allocation) {

    //             $quantity = (int) $allocation['quantity'];


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Free Quantity
    //             |--------------------------------------------------------------------------
    //             |
    //             | For now assign free quantity to the first allocation.
    //             |
    //             */

    //             $freeQuantity = $remainingFree;

    //             $remainingFree = 0;


    //             $sellingPrice = (float) $item['selling_price'];

    //             $discount = (float) ($item['discount'] ?? 0);

    //             $tax = (float) ($item['tax'] ?? 0);


    //             SaleItem::create([

    //                 'sale_id' => $sale->id,

    //                 'medicine_id' => $allocation['medicine_id'],

    //                 'purchase_item_id' => $allocation['purchase_item_id'],

    //                 'batch_number' => $allocation['batch_number'],

    //                 'expiry_date' => $allocation['expiry_date'],

    //                 'quantity' => $quantity,

    //                 'free_quantity' => $freeQuantity,

    //                 'purchase_price' => $allocation['purchase_price'],

    //                 'selling_price' => $sellingPrice,

    //                 'discount' => $discount,

    //                 'tax' => $tax,

    //                 'total' => $this->calculateItemTotal(
    //                     $quantity,
    //                     $sellingPrice,
    //                     $discount,
    //                     $tax
    //                 ),
    //             ]);
    //         }
    //     }
    // }
    /**
     * Create completed sale items using FIFO batches.
     */
    protected function createSaleItems(Sale $sale, array $items): void 
    {

        foreach ($items as $item) {

            $quantity =
                (int) $item['quantity'];

            $freeQuantity =
                (int) ($item['free_quantity'] ?? 0);

            $totalPhysicalQuantity =
                $quantity + $freeQuantity;


            /*
            |--------------------------------------------------------------------------
            | Allocate All Physical Units
            |--------------------------------------------------------------------------
            */

            $allocations =
                $this->stockService->allocateFIFO(
                    (int) $item['medicine_id'],
                    $totalPhysicalQuantity
                );


            $remainingPaid =
                $quantity;

            $remainingFree =
                $freeQuantity;


            foreach ($allocations as $allocation) {

                $allocationQuantity =
                    (int) $allocation['quantity'];


                /*
                |--------------------------------------------------------------------------
                | Paid Quantity
                |--------------------------------------------------------------------------
                */

                $paidQuantity =
                    min(
                        $remainingPaid,
                        $allocationQuantity
                    );


                $remainingPaid -=
                    $paidQuantity;


                /*
                |--------------------------------------------------------------------------
                | Free Quantity
                |--------------------------------------------------------------------------
                */

                $freeForAllocation =
                    min(
                        $remainingFree,
                        $allocationQuantity -
                        $paidQuantity
                    );


                $remainingFree -=
                    $freeForAllocation;


                /*
                |--------------------------------------------------------------------------
                | Create Sale Item
                |--------------------------------------------------------------------------
                */

                $sellingPrice =
                    (float) $item['selling_price'];

                $discount =
                    (float) ($item['discount'] ?? 0);

                $tax =
                    (float) ($item['tax'] ?? 0);


                SaleItem::create([

                    'sale_id' =>
                        $sale->id,

                    'medicine_id' =>
                        $allocation['medicine_id'],

                    'purchase_item_id' =>
                        $allocation['purchase_item_id'],

                    'batch_number' =>
                        $allocation['batch_number'],

                    'expiry_date' =>
                        $allocation['expiry_date'],

                    'quantity' =>
                        $paidQuantity,

                    'free_quantity' =>
                        $freeForAllocation,

                    'purchase_price' =>
                        $allocation['purchase_price'],

                    'selling_price' =>
                        $sellingPrice,

                    'discount' =>
                        $discount,

                    'tax' =>
                        $tax,

                    'total' =>
                        $this->calculateItemTotal(
                            $paidQuantity,
                            $sellingPrice,
                            $discount,
                            $tax
                        ),

                ]);
            }
        }
    }

    /**
     * Create items for draft sale.
     */
    // protected function createDraftSaleItems(Sale $sale, array $items): void
    // {
    //     // dd($items);
    //     foreach ($items as $item) {

    //         SaleItem::create([
    //             'sale_id' => $sale->id,
    //             'medicine_id' => $item['medicine_id'],
    //             'purchase_item_id' => $item['purchase_item_id'] ?? null,
    //             'batch_number' => $item['batch_number'] ?? null,
    //             'expiry_date' => $item['expiry_date'] ?? null,
    //             'quantity' => $item['quantity'],
    //             'free_quantity' => $item['free_quantity'] ?? 0,
    //             'purchase_price' => $item['purchase_price'] ?? 0,
    //             'selling_price' => $item['selling_price'],
    //             'discount' => $item['discount'] ?? 0,
    //             'tax' => $item['tax'] ?? 0,
    //             'total' => $item['total'] ?? 0,
    //         ]);
    //     }
    // }
    /**
     * Create draft sale items.
     *
     * Draft sales do not consume stock.
     */
    protected function createDraftSaleItems(Sale $sale, array $items): void 
    {

        foreach ($items as $item) {

            SaleItem::create([

                'sale_id' => $sale->id,

                'medicine_id' => $item['medicine_id'],

                'purchase_item_id' => null,

                'batch_number' => $item['batch_number'] ?? null,

                'expiry_date' => $item['expiry_date'] ?? null,

                'quantity' => $item['quantity'],

                'free_quantity' => $item['free_quantity'] ?? 0,

                'purchase_price' => $item['purchase_price'] ?? 0,

                'selling_price' => $item['selling_price'],

                'discount' => $item['discount'] ?? 0,

                'tax' => $item['tax'] ?? 0,

                'total' => $item['total'],
            ]);
        }
    }

    /**
     * Calculate item total.
     */
    protected function calculateItemTotal(int $quantity, float $sellingPrice, float $discount, float $tax): float
    {
        // dd('hi');
        $subtotal = $quantity * $sellingPrice;

        $total = $subtotal;

        if ($discount > 0) {
            $total -= $discount;
        }

        if ($tax > 0) {
            $total += $tax;
        }
        // dd('hi');
        return max(round($total, 2), 0);
    }

    /**
     * Calculate sale subtotal.
     */
    protected function calculateSubtotal(array $items): float
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += (float) ($item['total'] ?? (
                ((float) $item['quantity']) * ((float) $item['selling_price'])
            ));
        }

        return round($subtotal, 2);
    }

    /**
     * Calculate due amount.
     */
    protected function calculateDue(float $grandTotal, float $paidAmount): float
    {
        return round(max($grandTotal - $paidAmount, 0), 2);
    }

    /**
     * Calculate payment status.
     */
    protected function calculatePaymentStatus(float $grandTotal, float $paidAmount): string
    {
        if ($grandTotal <= 0) {
            return 'paid';
        }

        if ($paidAmount >= $grandTotal) {
            return 'paid';
        }

        if ($paidAmount > 0) {
            return 'partial';
        }

        return 'due';
    }

    /**
     * Generate unique sale invoice number.
     */
    protected function generateInvoiceNumber(): string
    {
        $lastSale = Sale::withTrashed()->latest('id')->first();

        $nextNumber = $lastSale ? $lastSale->id + 1 : 1;

        return 'SAL-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }



    protected function createLegacyReplacementSaleItems(Sale $sale,array $items): void 
    {
        foreach ($items as $item) {

            SaleItem::create([
                'sale_id' => $sale->id,
                'medicine_id' => $item['medicine_id'],
                'purchase_item_id' => null,
                'batch_number' => null,
                'expiry_date' => null,
                'quantity' => (int) $item['quantity'],
                'free_quantity' => (int) ($item['free_quantity'] ?? 0),
                'purchase_price' => (float) ($item['purchase_price'] ?? 0),
                'selling_price' => (float) $item['selling_price'],
                'discount' => (float) ($item['discount'] ?? 0),
                'tax' => (float) ($item['tax'] ?? 0),
                'total' => $this->calculateItemTotal(
                    (int) $item['quantity'],
                    (float) $item['selling_price'],
                    (float) ($item['discount'] ?? 0),
                    (float) ($item['tax'] ?? 0)
                ),
            ]);
        }
    }


    /**
     * Update an existing sale.
     */
    // public function update(Sale $sale, array $data): Sale
    // {
    //     return DB::transaction(function () use ($sale, $data) {
    //         $grandTotal = $this->calculateGrandTotal($data);
    //         $paidAmount = (float) ($data['paid_amount'] ?? 0);

    //         // $this->validateSaleData($data);
    //         // $wasCompleted = $sale->isCompleted();

    //         // if ($wasCompleted) {
    //         //     $this->stockService->restoreStockFromSale($sale->load('items'));
    //         // }
    //         $sale->load('items');

    //         $wasCompleted = $sale->isCompleted();

    //         if ($wasCompleted) {
    //             $this->stockService->restoreStockFromSale($sale);
    //         }

    //         $this->validateSaleData($data);

    //         $sale->items()->delete();

    //         $sale->update([
    //             'customer_id' => $data['customer_id'] ?? null,
    //             'doctor_name' => $data['doctor_name'] ?? null,
    //             'sale_date' => $data['sale_date'],
    //             'subtotal' => $data['subtotal'] ?? $this->calculateSubtotal($data['items']),
    //             'discount_type' => $data['discount_type'],
    //             'discount' => $data['discount'] ?? 0,
    //             'tax_type' => $data['tax_type'],
    //             'tax' => $data['tax'] ?? 0,
    //             'shipping' => $data['shipping'] ?? 0,
    //             'other_charges' => $data['other_charges'] ?? 0,
    //             'grand_total' => $grandTotal,
    //             'paid_amount' => $paidAmount,
    //             'due_amount' => $this->calculateDue($data['grand_total'] ?? 0, $data['paid_amount'] ?? 0),
    //             'payment_status' => $this->calculatePaymentStatus($data['grand_total'] ?? 0, $data['paid_amount'] ?? 0),
    //             'status' => $data['status'],
    //             'notes' => $data['notes'] ?? null,
    //             'updated_by' => Auth::id(),
    //         ]);

    //         if ($sale->status === 'completed') {
    //             $this->createSaleItems($sale, $data['items']);
    //             $this->stockService->deductStockFromSale($sale->load('items'));
    //         } else {
    //             $this->createDraftSaleItems($sale, $data['items']);
    //         }

    //         return $sale->load(['customer', 'items.medicine']);
    //     });
    // }


    // public function update(Sale $sale, array $data): Sale
    // {
    //     return DB::transaction(function () use ($sale, $data) {
    //         $sale->load('items');

    //         $wasCompleted = $sale->isCompleted();

    //         if ($wasCompleted) {
    //             $sale->items()->delete();
    //         }

    //         $grandTotal = $this->calculateGrandTotal($data);
    //         $paidAmount = (float) ($data['paid_amount'] ?? 0);

    //         $data['grand_total'] = $grandTotal;

    //         $this->validateSaleData($data);

    //         $sale->update([
    //             'customer_id' => $data['customer_id'] ?? null,
    //             'doctor_name' => $data['doctor_name'] ?? null,
    //             'sale_date' => $data['sale_date'],
    //             'subtotal' => $this->calculateSubtotal($data['items']),
    //             'discount_type' => $data['discount_type'],
    //             'discount' => $data['discount'] ?? 0,
    //             'tax_type' => $data['tax_type'],
    //             'tax' => $data['tax'] ?? 0,
    //             'shipping' => $data['shipping'] ?? 0,
    //             'other_charges' => $data['other_charges'] ?? 0,
    //             'grand_total' => $grandTotal,
    //             'paid_amount' => $paidAmount,
    //             'due_amount' => $this->calculateDue($grandTotal, $paidAmount),
    //             'payment_status' => $this->calculatePaymentStatus($grandTotal, $paidAmount),
    //             'status' => $data['status'],
    //             'notes' => $data['notes'] ?? null,
    //             'updated_by' => Auth::id(),
    //         ]);

    //         if ($sale->status === 'completed') {
    //             $this->createSaleItems($sale, $data['items']);
    //         } else {
    //             $this->createDraftSaleItems($sale, $data['items']);
    //         }

    //         return $sale->load(['customer', 'items.medicine']);
    //     });
    // }

    public function update(Sale $sale, array $data): Sale
    {
        return DB::transaction(function () use ($sale, $data) {

            $sale->load('items');

            $wasCompleted = $sale->isCompleted();

            // /*
            // |--------------------------------------------------------------------------
            // | 1. Restore stock from the OLD completed sale
            // |--------------------------------------------------------------------------
            // */
            // if ($wasCompleted) {
            //     $this->stockService->restoreStockFromSale($sale);
            // }

            // /*
            // |--------------------------------------------------------------------------
            // | 3. Delete old sale items
            // |--------------------------------------------------------------------------
            // |
            // | Batch stock becomes available again because stock availability
            // | is calculated from PurchaseItem - SaleItems.
            // |
            // */
            // $sale->items()->delete();

            // /*
            // |--------------------------------------------------------------------------
            // | 2. Validate the NEW sale data
            // |--------------------------------------------------------------------------
            // |
            // | IMPORTANT:
            // | We restore old stock BEFORE validation so the new quantity can
            // | use the stock previously consumed by this sale.
            // |
            // */
            // $this->validateSaleData($data);


            if ($wasCompleted) {
                $this->stockService->restoreStockFromSale($sale);
            }

            $hasLegacyItems = $sale->items->contains(function ($item) {
                return is_null($item->purchase_item_id);
            });

            $sale->items()->delete();

            if ($hasLegacyItems && $sale->status === 'completed') {

                // Legacy sale:
                // Its old stock consumption was not linked to purchase batches.
                // The restored medicine stock is now available for the replacement sale.
                //
                // Do NOT validate against FIFO batches here.

            } else {

                $this->validateSaleData($data);
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Calculate new totals
            |--------------------------------------------------------------------------
            */
            $grandTotal = $this->calculateGrandTotal($data);

            $paidAmount = (float) ($data['paid_amount'] ?? 0);

            $data['grand_total'] = $grandTotal;

            /*
            |--------------------------------------------------------------------------
            | 5. Update sale
            |--------------------------------------------------------------------------
            */
            $sale->update([
                'customer_id' => $data['customer_id'] ?? null,
                'doctor_name' => $data['doctor_name'] ?? null,
                'sale_date' => $data['sale_date'],
                'subtotal' => $this->calculateSubtotal($data['items']),
                'discount_type' => $data['discount_type'],
                'discount' => $data['discount'] ?? 0,
                'tax_type' => $data['tax_type'],
                'tax' => $data['tax'] ?? 0,
                'shipping' => $data['shipping'] ?? 0,
                'other_charges' => $data['other_charges'] ?? 0,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'due_amount' => $this->calculateDue(
                    $grandTotal,
                    $paidAmount
                ),
                'payment_status' => $this->calculatePaymentStatus(
                    $grandTotal,
                    $paidAmount
                ),
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | 6. Create NEW sale items
            |--------------------------------------------------------------------------
            */
            if ($sale->status === 'completed') {

                if ($hasLegacyItems) {
                    $this->createLegacyReplacementSaleItems(
                        $sale,
                        $data['items']
                    );
                } else {
                    $this->createSaleItems(
                        $sale,
                        $data['items']
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 7. Deduct stock for the NEW completed sale
                |--------------------------------------------------------------------------
                */
                $this->stockService->deductStockFromSale(
                    $sale->load('items')
                );

            } else {

                $this->createDraftSaleItems(
                    $sale,
                    $data['items']
                );
            }

            return $sale->fresh([
                'customer',
                'items.medicine',
                'items.purchaseItem',
            ]);
        });
    }

    /**
     * Cancel a sale and restore its stock.
     */
    public function cancel(Sale $sale): Sale
    {
        return DB::transaction(function () use ($sale) {

            if ($sale->isCancelled()) {
                throw ValidationException::withMessages([
                    'sale' => ['This sale is already cancelled.']
                ]);
            }

            if ($sale->isCompleted()) {
                $this->stockService->restoreStockFromSale($sale->load('items'));
            }

            $sale->update([
                'status' => 'cancelled',
                'updated_by' => Auth::id(),
            ]);

            return $sale->fresh(['customer', 'items.medicine']);
        });
    }

    /**
     * Delete a sale.
     */
    public function destroy(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {

            if ($sale->isCompleted()) {
                $this->stockService->restoreStockFromSale($sale->load('items'));
            }

            $sale->update([
                'deleted_by' => Auth::id(),
            ]);

            $sale->delete();
        });
    }

    /**
     * Restore a deleted sale.
     */
    public function restore(Sale $sale): Sale
    {
        return DB::transaction(function () use ($sale) {

            if (!$sale->trashed()) {
                throw ValidationException::withMessages([
                    'sale' => ['This sale is not deleted.']
                ]);
            }

            $sale->restore();

            $sale->update([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            if ($sale->isCompleted()) {
                $this->stockService->deductStockFromSale($sale->load('items'));
            }

            return $sale->fresh(['customer', 'items.medicine']);
        });
    }

    /**
     * Permanently delete a sale.
     */
    public function forceDelete(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {

            if (!$sale->trashed()) {
                throw ValidationException::withMessages([
                    'sale' => ['Only deleted sales can be permanently deleted.']
                ]);
            }

            $sale->items()->forceDelete();

            $sale->forceDelete();
        });
    }

    /**
     * Complete a draft sale.
     */
    // public function complete(Sale $sale): Sale
    // {
    //     return DB::transaction(function () use ($sale) {

    //         if (!$sale->isDraft()) {
    //             throw ValidationException::withMessages([
    //                 'sale' => ['Only draft sales can be completed.']
    //             ]);
    //         }

    //         $items = $sale->items()->get();

    //         $stockItems = $items->map(function ($item) {
    //             return [
    //                 'medicine_id' => $item->medicine_id,
    //                 'quantity' => $item->quantity,
    //             ];
    //         })->toArray();

    //         $this->stockService->validateSaleItems($stockItems);

            
    //         // $sale->items()->delete();

    //         // $requestItems = $items->map(function ($item) {
    //         //     return [
    //         //         'medicine_id' => $item->medicine_id,
    //         //         'quantity' => $item->quantity,
    //         //         'free_quantity' => $item->free_quantity,
    //         //         'selling_price' => $item->selling_price,
    //         //         'purchase_price' => $item->purchase_price,
    //         //         'discount' => $item->discount,
    //         //         'tax' => $item->tax,
    //         //         'total' => $item->total,
    //         //     ];
    //         // })->toArray();
            
    //         // $this->createSaleItems($sale, $requestItems);

    //         $sale->update([
    //             'status' => 'completed',
    //             'updated_by' => Auth::id(),
    //         ]);

    //         $this->stockService->deductStockFromSale($sale->load('items'));

    //         return $sale->fresh(['customer', 'items.medicine']);
    //     });
    // }
    /**
     * Complete a draft sale.
     */
    public function complete(Sale $sale): Sale
    {
        return DB::transaction(function () use ($sale) {

            if (!$sale->isDraft()) {

                throw ValidationException::withMessages([
                    'sale' => [
                        'Only draft sales can be completed.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Get Draft Items
            |--------------------------------------------------------------------------
            */

            $items = $sale->items()->get();


            if ($items->isEmpty()) {

                throw ValidationException::withMessages([
                    'items' => [
                        'Sale must contain at least one medicine.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Prepare Original Item Data
            |--------------------------------------------------------------------------
            |
            | Draft sale items do not have purchase_item_id.
            | They must be allocated now.
            |
            */

            $saleData = $items->map(function ($item) {

                return [

                    'medicine_id' =>
                        (int) $item->medicine_id,

                    'quantity' =>
                        (int) $item->quantity,

                    'free_quantity' =>
                        (int) ($item->free_quantity ?? 0),

                    'selling_price' =>
                        (float) $item->selling_price,

                    'purchase_price' =>
                        (float) ($item->purchase_price ?? 0),

                    'discount' =>
                        (float) ($item->discount ?? 0),

                    'tax' =>
                        (float) ($item->tax ?? 0),

                ];

            })->toArray();


            /*
            |--------------------------------------------------------------------------
            | Validate Sale
            |--------------------------------------------------------------------------
            */

            $this->validateSaleData([
                'items' => $saleData,
                'discount_type' => $sale->discount_type,
                'discount' => $sale->discount,
                'tax_type' => $sale->tax_type,
                'tax' => $sale->tax,
                'shipping' => $sale->shipping,
                'other_charges' => $sale->other_charges,
                'paid_amount' => $sale->paid_amount,
                'status' => 'completed',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Remove Draft Items
            |--------------------------------------------------------------------------
            */

            $sale->items()->delete();


            /*
            |--------------------------------------------------------------------------
            | Create FIFO-Based Completed Items
            |--------------------------------------------------------------------------
            */

            $this->createSaleItems(
                $sale,
                $saleData
            );


            /*
            |--------------------------------------------------------------------------
            | Complete Sale
            |--------------------------------------------------------------------------
            */

            $sale->update([

                'status' =>
                    'completed',

                'updated_by' =>
                    Auth::id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Deduct Global Medicine Stock
            |--------------------------------------------------------------------------
            */

            $this->stockService->deductStockFromSale(
                $sale->load('items')
            );


            /*
            |--------------------------------------------------------------------------
            | Return Fresh Sale
            |--------------------------------------------------------------------------
            */

            return $sale->fresh([
                'customer',
                'items.medicine',
                'items.purchaseItem',
            ]);
        });
    }

    /**
     * Restore stock only when a completed sale is cancelled or deleted.
     */
    public function restoreSaleStock(Sale $sale): void
    {
        if (!$sale->isCompleted()) {
            return;
        }

        $this->stockService->restoreStockFromSale($sale->load('items'));
    }

    /**
     * Recalculate sale totals.
     */
    public function calculateTotals(array $items, string $discountType = 'fixed', float $discount = 0, string $taxType = 'fixed', float $tax = 0, float $shipping = 0, float $otherCharges = 0): array
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $sellingPrice = (float) ($item['selling_price'] ?? 0);
            $itemDiscount = (float) ($item['discount'] ?? 0);
            $itemTax = (float) ($item['tax'] ?? 0);

            $itemSubtotal = $quantity * $sellingPrice;

            $itemSubtotal -= $itemDiscount;
            $itemSubtotal += $itemTax;

            $subtotal += max($itemSubtotal, 0);
        }

        $discountAmount = strtolower($discountType) === 'percentage'
            ? $subtotal * ($discount / 100)
            : $discount;

        $taxAmount = strtolower($taxType) === 'percentage'
            ? ($subtotal - $discountAmount) * ($tax / 100)
            : $tax;

        $grandTotal = $subtotal - $discountAmount + $taxAmount + $shipping + $otherCharges;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discountAmount, 2),
            'tax' => round($taxAmount, 2),
            'shipping' => round($shipping, 2),
            'other_charges' => round($otherCharges, 2),
            'grand_total' => round(max($grandTotal, 0), 2),
        ];
    }



        /**
     * Validate sale items before processing.
     */
    protected function validateSaleItemsForUpdate(array $items, ?Sale $sale = null): void
    {
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => ['Sale must contain at least one medicine.']
            ]);
        }

        $medicineIds = [];

        foreach ($items as $item) {
            $medicineId = (int) $item['medicine_id'];

            if (in_array($medicineId, $medicineIds, true)) {
                throw ValidationException::withMessages([
                    'items' => ['The same medicine cannot be added more than once.']
                ]);
            }

            $medicineIds[] = $medicineId;

            if ((int) ($item['quantity'] ?? 0) <= 0) {
                throw ValidationException::withMessages([
                    'items' => ['Medicine quantity must be greater than zero.']
                ]);
            }

            if ((float) ($item['selling_price'] ?? 0) <= 0) {
                throw ValidationException::withMessages([
                    'items' => ['Selling price must be greater than zero.']
                ]);
            }
        }
    }

    /**
     * Determine whether stock should be affected.
     */
    protected function shouldAffectStock(string $status): bool
    {
        return strtolower($status) === 'completed';
    }

    /**
     * Get sale items formatted for FIFO processing.
     */
    protected function prepareItemsForProcessing(array $items): array
    {
        return collect($items)->map(function ($item) {
            return [
                'medicine_id' => (int) $item['medicine_id'],
                'quantity' => (int) $item['quantity'],
                'free_quantity' => (int) ($item['free_quantity'] ?? 0),
                'purchase_price' => (float) ($item['purchase_price'] ?? 0),
                'selling_price' => (float) $item['selling_price'],
                'discount' => (float) ($item['discount'] ?? 0),
                'tax' => (float) ($item['tax'] ?? 0),
                'total' => (float) ($item['total'] ?? 0),
            ];
        })->values()->toArray();
    }

    /**
     * Calculate sale payment values.
     */
    protected function calculatePaymentValues(float $grandTotal, float $paidAmount): array
    {
        $grandTotal = round(max($grandTotal, 0), 2);
        $paidAmount = round(max($paidAmount, 0), 2);

        if ($paidAmount > $grandTotal) {
            throw ValidationException::withMessages([
                'paid_amount' => ['Paid amount cannot be greater than the grand total.']
            ]);
        }

        return [
            'paid_amount' => $paidAmount,
            'due_amount' => $this->calculateDue($grandTotal, $paidAmount),
            'payment_status' => $this->calculatePaymentStatus($grandTotal, $paidAmount),
        ];
    }

    /**
     * Recalculate and synchronize sale payment values.
     */
    public function syncPaymentValues(Sale $sale): Sale
    {
        $payment = $this->calculatePaymentValues(
            (float) $sale->grand_total,
            (float) $sale->paid_amount
        );

        $sale->update($payment);

        return $sale->fresh();
    }

    /**
     * Change paid amount.
     */
    public function updatePayment(Sale $sale, float $paidAmount): Sale
    {
        return DB::transaction(function () use ($sale, $paidAmount) {

            $payment = $this->calculatePaymentValues(
                (float) $sale->grand_total,
                $paidAmount
            );

            $payment['updated_by'] = Auth::id();

            $sale->update($payment);

            return $sale->fresh();
        });
    }

    /**
     * Recalculate sale totals from sale items.
     */
    public function recalculateSaleTotals(Sale $sale): Sale
    {
        $items = $sale->items()->get();

        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += (float) $item->total;
        }

        $discount = (float) $sale->discount;
        $tax = (float) $sale->tax;
        $shipping = (float) $sale->shipping;
        $otherCharges = (float) $sale->other_charges;

        $grandTotal = $subtotal;

        if (strtolower($sale->discount_type) === 'percentage') {
            $discountAmount = $subtotal * ($discount / 100);
        } else {
            $discountAmount = $discount;
        }

        $grandTotal -= $discountAmount;

        if (strtolower($sale->tax_type) === 'percentage') {
            $taxAmount = max($grandTotal, 0) * ($tax / 100);
        } else {
            $taxAmount = $tax;
        }

        $grandTotal += $taxAmount;
        $grandTotal += $shipping;
        $grandTotal += $otherCharges;

        $grandTotal = round(max($grandTotal, 0), 2);

        $payment = $this->calculatePaymentValues(
            $grandTotal,
            (float) $sale->paid_amount
        );

        $sale->update([
            'subtotal' => round($subtotal, 2),
            'grand_total' => $grandTotal,
            'discount' => round($discountAmount, 2),
            'tax' => round($taxAmount, 2),
            ...$payment,
            'updated_by' => Auth::id(),
        ]);

        return $sale->fresh(['customer', 'items.medicine', 'items.purchaseItem']);
    }

    /**
     * Delete sale items without affecting stock.
     */
    protected function deleteSaleItems(Sale $sale): void
    {
        $sale->items()->delete();
    }

    /**
     * Check whether a sale can be edited.
     */
    public function canEdit(Sale $sale): bool
    {
        return !$sale->trashed() && !$sale->isCancelled();
    }

    /**
     * Check whether a sale can be cancelled.
     */
    public function canCancel(Sale $sale): bool
    {
        return !$sale->trashed() && !$sale->isCancelled();
    }

    /**
     * Check whether a sale can be restored.
     */
    public function canRestore(Sale $sale): bool
    {
        return $sale->trashed();
    }

    /**
     * Check whether a sale can be force deleted.
     */
    public function canForceDelete(Sale $sale): bool
    {
        return $sale->trashed();
    }

    /**
     * Get sale with all required relationships.
     */
    public function getSale(Sale $sale): Sale
    {
        return $sale->load([
            'customer',
            'creator',
            'updater',
            'deleter',
            'items.medicine',
            'items.purchaseItem',
        ]);
    }

    /**
     * Get active medicines for sale creation.
     */
    public function getSaleMedicines()
    {
        return Medicine::where('status', 1)
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get customers for sale creation.
     */
    public function getCustomers()
    {
        return Customer::whereNull('deleted_at')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get available stock information for a medicine.
     */
    public function getMedicineStock(int $medicineId): array
    {
        $medicine = Medicine::findOrFail($medicineId);

        return [
            'medicine_id' => $medicine->id,
            'medicine_name' => $medicine->name,
            'current_stock' => (int) $medicine->current_stock,
            'batches' => $this->stockService->getAvailableBatches($medicineId),
        ];
    }

    /**
     * Safely execute a sale callback inside a transaction.
     */
    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }
}
