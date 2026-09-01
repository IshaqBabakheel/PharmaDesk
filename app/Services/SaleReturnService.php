<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleReturnService
{
    public function __construct(
        protected StockService $stockService
    ) {
    }

    /**
     * Get completed sales that can potentially be returned.
     */
    public function getReturnableSales()
    {
        return Sale::query()
            ->with(['customer', 'items.medicine', 'items.purchaseItem'])
            ->where('status', 'completed')
            ->latest('sale_date')
            ->latest('id')
            ->get();
    }

    /**
     * Get a sale return with required relationships.
     */
    public function getSaleReturn(SaleReturn $saleReturn): SaleReturn
    {
        return $saleReturn->load([
            'sale.customer',
            'customer',
            'creator',
            'updater',
            'deleter',
            'items.medicine',
            'items.saleItem',
            'items.purchaseItem',
        ]);
    }

    /**
     * Store a new sale return.
     */
    public function store(array $data): SaleReturn
    {

        return DB::transaction(function () use ($data) {
            $this->validateReturnData($data);

            $sale = Sale::with('items')
                ->findOrFail($data['sale_id']);

            $this->validateSaleForReturn($sale);

            $subtotal = $this->calculateSubtotal($data['items']);

            $discount = (float) ($data['discount'] ?? 0);
            $tax = (float) ($data['tax'] ?? 0);
            $otherCharges = (float) ($data['other_charges'] ?? 0);

            $grandTotal =
                $subtotal
                - $discount
                + $tax
                + $otherCharges;

            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,

                'customer_id' =>
                    $data['customer_id']
                    ?? $sale->customer_id,

                'return_number' =>
                    $this->generateReturnNumber(),

                'return_date' => $data['return_date'],

                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'other_charges' => $otherCharges,
                'grand_total' => $grandTotal,

                'status' =>
                    $data['status']
                    ?? 'completed',

                'notes' =>
                    $data['notes']
                    ?? null,

                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->createReturnItems(
                $saleReturn,
                $sale,
                $data['items']
            );

            if ($saleReturn->status === 'completed') {
                $this->restoreReturnedStock($saleReturn);
            }

            return $this->getSaleReturn($saleReturn);
        });
    }

    /**
     * Alias for compatibility if create() is used elsewhere.
     */
    public function create(array $data): SaleReturn
    {
        return $this->store($data);
    }

    /**
     * Update an existing sale return.
     */
    public function update(SaleReturn $saleReturn, array $data): SaleReturn 
    {
        // dd('hi');
        return DB::transaction(function () use ($saleReturn, $data) {

            $saleReturn->load('items');

            /*
             * Remove the old stock effect first.
             */
            if ($saleReturn->status === 'completed') {
                $this->removeReturnStockEffect($saleReturn);
            }

            $sale = Sale::with('items')->findOrFail($data['sale_id']);

            $this->validateReturnData($data, $saleReturn);


            $this->validateSaleForReturn($sale);

            $saleReturn->items()->delete();

            $subtotal = $this->calculateSubtotal($data['items']);

            $discount = (float) ($data['discount'] ?? 0);
            $tax = (float) ($data['tax'] ?? 0);
            $otherCharges = (float) ($data['other_charges'] ?? 0);

            $grandTotal = $subtotal - $discount + $tax + $otherCharges;

            $saleReturn->update([
                'sale_id' => $sale->id,
                'customer_id' => $data['customer_id'] ?? $sale->customer_id,
                'return_date' => $data['return_date'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'other_charges' => $otherCharges,
                'grand_total' => $grandTotal,
                'status' => $data['status'] ?? 'completed',
                'notes' => $data['notes'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            $this->createReturnItems(
                $saleReturn,
                $sale,
                $data['items']
            );

            if ($saleReturn->status === 'completed') {
                $this->restoreReturnedStock($saleReturn);
            }

            return $this->getSaleReturn($saleReturn);
        });
    }


    /**
     * Complete a draft sale return.
     */
    public function complete(SaleReturn $saleReturn): SaleReturn 
    {

        return DB::transaction(function () use ($saleReturn) {

            $saleReturn->load([
                'items',
                'sale.items',
            ]);

            /*
            * A return can only be completed once.
            */
            if ($saleReturn->status !== 'draft') {

                throw ValidationException::withMessages([
                    'status' => [
                        'Only draft sale returns can be completed.'
                    ],
                ]);
            }

            /*
            * Make sure the sale is still valid.
            */
            $this->validateSaleForReturn(
                $saleReturn->sale
            );

            /*
            * Rebuild validation data from the
            * existing return itself.
            */
            $data = [
                'sale_id' => $saleReturn->sale_id,

                'items' => $saleReturn->items
                    ->map(function ($item) {

                        return [
                            'sale_item_id' => $item->sale_item_id,
                            'quantity' => $item->quantity,
                            'free_quantity' => $item->free_quantity,
                        ];

                    })
                    ->values()
                    ->toArray(),
            ];

            /*
            * Validate AGAIN at completion time.
            *
            * This is important because inventory may have
            * changed since the draft was created.
            */
            $this->validateReturnData(
                $data,
                null
            );

            /*
            * Restore the returned stock.
            */
            $this->restoreReturnedStock(
                $saleReturn
            );

            /*
            * Change status only AFTER stock restoration
            * succeeds.
            */
            $saleReturn->update([
                'status' => 'completed',
                'updated_by' => Auth::id(),
            ]);

            return $this->getSaleReturn(
                $saleReturn->fresh()
            );
        });
    }

    /**
     * Cancel a sale return.
     */
    public function cancel(SaleReturn $saleReturn): SaleReturn 
    {

        return DB::transaction(function () use ($saleReturn) {

            $saleReturn->load('items');

            /*
            * Already cancelled.
            */
            if ($saleReturn->status === 'cancelled') {

                throw ValidationException::withMessages([
                    'status' => [
                        'This sale return is already cancelled.'
                    ],
                ]);
            }

            /*
            * Completed return has already restored stock.
            *
            * Therefore remove its stock effect before
            * changing the status.
            */
            if ($saleReturn->status === 'completed') {

                $this->removeReturnStockEffect(
                    $saleReturn
                );
            }

            /*
            * Draft returns have no stock effect,
            * so nothing needs to be restored/reversed.
            */
            $saleReturn->update([
                'status' => 'cancelled',
                'updated_by' => Auth::id(),
            ]);

            return $this->getSaleReturn(
                $saleReturn->fresh()
            );
        });
    }
   
    /**
     * Delete a sale return.
     */
    public function destroy(SaleReturn $saleReturn): void
    {
        DB::transaction(function () use ($saleReturn) {

            /*
            * Lock the sale return before modifying it.
            */
            $saleReturn = SaleReturn::with('items')
                ->lockForUpdate()
                ->findOrFail($saleReturn->id);

            /*
            * A completed return increased medicine stock.
            *
            * When deleting the return, remove that stock effect.
            */
            if ($saleReturn->status === 'completed') {
                $this->removeReturnStockEffect($saleReturn);
            }

            /*
            * Record who deleted the return.
            */
            $saleReturn->deleted_by = Auth::id();
            $saleReturn->updated_by = Auth::id();

            $saleReturn->save();

            /*
            * Soft delete the sale return.
            */
            $saleReturn->delete();
        });
    }

    /**
     * Restore a soft-deleted sale return.
     */
    public function restore(SaleReturn $saleReturn): SaleReturn
    {
        return DB::transaction(function () use ($saleReturn) {

            /*
            * Make sure we are working with the trashed record.
            */
            $saleReturn = SaleReturn::withTrashed()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($saleReturn->id);
            /*

            * It must actually be trashed.
            */
            if (!$saleReturn->trashed()) {
                throw ValidationException::withMessages([
                    'sale_return' => [
                        'This sale return is not deleted.'
                    ],
                ]);
            }

            /*
            * Restore the parent record.
            */
            $saleReturn->restore();

            /*
            * Clear deleted_by.
            */
            $saleReturn->deleted_by = null;
            $saleReturn->updated_by = Auth::id();

            $saleReturn->save();

            /*
            * A completed return previously had its stock effect
            * removed during deletion.
            *
            * Therefore restore the stock effect now.
            */
            if ($saleReturn->status === 'completed') {
                $saleReturn->load('items');

                $this->restoreReturnedStock($saleReturn);
            }

            /*
            * Return a fresh model.
            */
            return $this->getSaleReturn(
                $saleReturn->fresh([
                    'sale',
                    'customer',
                    'items.medicine',
                    'items.saleItem',
                    'items.purchaseItem',
                ])
            );
        });
    }

    /**
     * Permanently delete a sale return.
     */
    public function forceDelete(SaleReturn $saleReturn): void
    {
        DB::transaction(function () use ($saleReturn) {

            /*
            * We need the trashed record.
            */
            $saleReturn = SaleReturn::withTrashed()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($saleReturn->id);

            /*
            * If the return is still completed, its stock effect
            * must be removed before permanent deletion.
            *
            * IMPORTANT:
            * If destroy() was already called, the stock effect has
            * already been removed, so the record will normally be
            * trashed before reaching this method.
            *
            * We therefore only remove stock here if the record is
            * NOT already trashed.
            */
            if (
                !$saleReturn->trashed()
                && $saleReturn->status === 'completed'
            ) {
                $this->removeReturnStockEffect($saleReturn);
            }

            /*
            * Delete child items permanently.
            *
            * Use forceDelete() when SaleReturnItem uses SoftDeletes.
            */
            foreach ($saleReturn->items as $item) {

                if (method_exists($item, 'forceDelete')) {
                    $item->forceDelete();
                } else {
                    $item->delete();
                }
            }

            /*
            * Permanently delete the sale return itself.
            */
            $saleReturn->forceDelete();
        });
    }

    /**
     * Validate the selected sale.
     */
    protected function validateSaleForReturn(?Sale $sale): void
    {
        if (!$sale || !$sale->isCompleted()) {
            throw ValidationException::withMessages([
                'sale_id' => [
                    'Only completed sales can have returns.'
                ],
            ]);
        }

        if ($sale->items->isEmpty()) {
            throw ValidationException::withMessages([
                'sale_id' => [
                    'This sale has no items available for return.'
                ],
            ]);
        }
    }

    /**
     * Create return items from original sale items.
     */
    protected function createReturnItems(SaleReturn $saleReturn, Sale $sale,array $items): void 
    {
        foreach ($items as $item) {

            $saleItem = SaleItem::with('purchaseItem')
                ->where('sale_id', $sale->id)
                ->findOrFail($item['sale_item_id']);

            $returnQuantity =
                (int) ($item['quantity'] ?? 0);

            $returnFreeQuantity =
                (int) ($item['free_quantity'] ?? 0);

            /*
             * Already returned quantities from OTHER completed returns.
             */
            $alreadyReturned = SaleReturnItem::query()
                ->where('sale_item_id', $saleItem->id)
                ->whereHas('saleReturn', function ($query) use ($saleReturn) {
                    $query
                        ->where('id', '!=', $saleReturn->id)
                        ->where('status', 'completed');
                })
                ->sum('quantity');
            // dd($alreadyReturned);    

            $alreadyReturnedFree = SaleReturnItem::query()
                ->where('sale_item_id', $saleItem->id)
                ->whereHas('saleReturn', function ($query) use ($saleReturn) {
                    $query
                        ->where('id', '!=', $saleReturn->id)
                        ->where('status', 'completed');
                })
                ->sum('free_quantity');

            if ($alreadyReturned + $returnQuantity > $saleItem->quantity) 
            {
                throw ValidationException::withMessages([
                    'items' => [
                        "Return quantity for {$saleItem->medicine?->name} exceeds the remaining sold quantity."
                    ],
                ]);
            }

            if ($alreadyReturnedFree + $returnFreeQuantity > $saleItem->free_quantity) 
            {
                throw ValidationException::withMessages([
                    'items' => [
                        "Return free quantity for {$saleItem->medicine?->name} exceeds the remaining free quantity."
                    ],
                ]);
            }

            SaleReturnItem::create([
                'sale_return_id' => $saleReturn->id,

                'sale_item_id' => $saleItem->id,

                'medicine_id' => $saleItem->medicine_id,

                'purchase_item_id' =>
                    $saleItem->purchase_item_id,

                'batch_number' =>
                    $saleItem->batch_number,

                'expiry_date' =>
                    $saleItem->expiry_date,

                'quantity' => $returnQuantity,

                'free_quantity' =>
                    $returnFreeQuantity,

                'purchase_price' =>
                    $saleItem->purchase_price,

                'selling_price' =>
                    $saleItem->selling_price,

                'discount' =>
                    $saleItem->discount,

                'tax' =>
                    $saleItem->tax,

                'total' => $this->calculateItemTotal(
                    $returnQuantity,
                    (float) $saleItem->selling_price,
                    (float) $saleItem->discount,
                    (float) $saleItem->tax
                ),
            ]);
        }
    }

    /**
     * Restore returned stock into Medicine.current_stock.
     *
     * IMPORTANT:
     * purchase_item_id may be NULL for legacy sales/opening-stock
     * sales, but the medicine stock still needs to be restored.
     */
    protected function restoreReturnedStock(SaleReturn $saleReturn): void 
    {
        $saleReturn->load('items');

        foreach ($saleReturn->items as $item) {

            $medicine = $item->medicine()->lockForUpdate()->firstOrFail();

            $totalQuantity = (int) $item->quantity + (int) $item->free_quantity;

            if ($totalQuantity <= 0) {
                continue;
            }

            $medicine->increment('current_stock', $totalQuantity);
        }
    }

    /**
     * Remove stock effect of a completed return.
     */
    protected function removeReturnStockEffect(SaleReturn $saleReturn): void 
    {
        $saleReturn->load('items');

        foreach ($saleReturn->items as $item) {

            $medicine = $item->medicine()
                ->lockForUpdate()
                ->firstOrFail();

            $totalQuantity =
                (int) $item->quantity
                + (int) $item->free_quantity;

            if ($totalQuantity <= 0) {
                continue;
            }

            if ($medicine->current_stock < $totalQuantity) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name} does not have enough current stock to reverse this sale return."
                    ],
                ]);
            }

            $medicine->decrement(
                'current_stock',
                $totalQuantity
            );
        }
    }

    /**
     * Validate sale return data.
     */
    protected function validateReturnData(array $data, ?SaleReturn $currentReturn = null): void 
    {

        /*
        |--------------------------------------------------------------------------
        | 1. Sale is required
        |--------------------------------------------------------------------------
        */
        if (empty($data['sale_id'])) {

            throw ValidationException::withMessages([
                'sale_id' => [
                    'Sale is required.'
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Sale must exist
        |--------------------------------------------------------------------------
        */
        $sale = Sale::with('items')->find($data['sale_id']);
        if (!$sale) {
            throw ValidationException::withMessages([
                'sale_id' => [
                    'The selected sale does not exist.'
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Items must exist
        |--------------------------------------------------------------------------
        */
        if (empty($data['items']) || !is_array($data['items'])) 
        {
            throw ValidationException::withMessages([
                'items' => [
                    'Sale return must contain at least one item.'
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Get available sale items
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | getSaleItemsForReturn() must calculate:
        |
        | sold quantity
        | -
        | previously returned quantity
        |
        | When editing, the CURRENT return is excluded from historical
        | returned quantities.
        |
        */
        $availableItems = $this->getSaleItemsForReturn($sale, $currentReturn)->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | 5. Get valid sale item IDs
        |--------------------------------------------------------------------------
        */

        $saleItemIds = $sale->items()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | 6. Track duplicate items
        |--------------------------------------------------------------------------
        */

        $submittedSaleItemIds = [];

        /*
        |--------------------------------------------------------------------------
        | 7. Track whether anything is actually being returned
        |--------------------------------------------------------------------------
        */

        $hasReturnItem = false;


        /*
        |--------------------------------------------------------------------------
        | 8. Validate every submitted row
        |--------------------------------------------------------------------------
        */

        foreach ($data['items'] as $index => $item) {

            /*
            |--------------------------------------------------------------------------
            | Sale item ID
            |--------------------------------------------------------------------------
            */

            if (empty($item['sale_item_id'])) {

                throw ValidationException::withMessages([
                    "items.$index.sale_item_id" => [
                        'Each return row must belong to a sale item.'
                    ],
                ]);
            }


            $saleItemId = (int) $item['sale_item_id'];


            /*
            |--------------------------------------------------------------------------
            | Sale item must belong to selected sale
            |--------------------------------------------------------------------------
            */

            if (!in_array($saleItemId, $saleItemIds, true)) {

                throw ValidationException::withMessages([
                    "items.$index.sale_item_id" => [
                        'The selected sale item does not belong to this sale.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate sale item IDs
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $saleItemId,
                    $submittedSaleItemIds,
                    true
                )
            ) {

                throw ValidationException::withMessages([
                    "items.$index.sale_item_id" => [
                        'The same sale item cannot be submitted more than once.'
                    ],
                ]);
            }

            $submittedSaleItemIds[] = $saleItemId;


            /*
            |--------------------------------------------------------------------------
            | Normalize quantities
            |--------------------------------------------------------------------------
            */

            $quantity = (int) (
                $item['quantity'] ?? 0
            );

            $freeQuantity = (int) (
                $item['free_quantity'] ?? 0
            );


            /*
            |--------------------------------------------------------------------------
            | Negative quantity validation
            |--------------------------------------------------------------------------
            */

            if ($quantity < 0) {

                throw ValidationException::withMessages([
                    "items.$index.quantity" => [
                        'Return quantity cannot be negative.'
                    ],
                ]);
            }


            if ($freeQuantity < 0) {

                throw ValidationException::withMessages([
                    "items.$index.free_quantity" => [
                        'Return free quantity cannot be negative.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            |
            | 0 + 0 is valid for an individual row.
            |
            | The edit form submits ALL sale items.
            |
            | Therefore a row with no return is simply ignored.
            |--------------------------------------------------------------------------
            */

            if (
                $quantity === 0 &&
                $freeQuantity === 0
            ) {

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | At least one actual return exists
            |--------------------------------------------------------------------------
            */

            $hasReturnItem = true;


            /*
            |--------------------------------------------------------------------------
            | Get original sale item
            |--------------------------------------------------------------------------
            */

            $saleItem = $sale->items()
                ->where('id', $saleItemId)
                ->first();

            if (!$saleItem) {

                throw ValidationException::withMessages([
                    "items.$index.sale_item_id" => [
                        'The selected sale item could not be found.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Get available item
            |--------------------------------------------------------------------------
            */
            $availableItem = $availableItems->get($saleItemId);


            /*
            |--------------------------------------------------------------------------
            | No remaining quantity at all
            |--------------------------------------------------------------------------
            */

            if (!$availableItem) {

                throw ValidationException::withMessages([
                    "items.$index.quantity" => [
                        'This sale item has no quantity remaining for return.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate remaining quantities
            |--------------------------------------------------------------------------
            */
            $remainingQuantity = max(0, (int) $availableItem->remaining_quantity);
            $remainingFreeQuantity = max(0, (int) $availableItem->remaining_free_quantity);


            /*
            |--------------------------------------------------------------------------
            | Paid quantity cannot exceed remaining quantity
            |--------------------------------------------------------------------------
            */
            if ($quantity > $remainingQuantity) {

                throw ValidationException::withMessages([
                    "items.$index.quantity" => [
                        "Only {$remainingQuantity} unit(s) remain available for return."
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Free quantity cannot exceed remaining free quantity
            |--------------------------------------------------------------------------
            */

            if (
                $freeQuantity >
                $remainingFreeQuantity
            ) {

                throw ValidationException::withMessages([
                    "items.$index.free_quantity" => [
                        "Only {$remainingFreeQuantity} free unit(s) remain available for return."
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Safety check against originally sold quantity
            |--------------------------------------------------------------------------
            */

            if (
                $quantity >
                (int) $saleItem->quantity
            ) {

                throw ValidationException::withMessages([
                    "items.$index.quantity" => [
                        "Return quantity cannot exceed the sold quantity of {$saleItem->quantity}."
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Safety check against originally sold free quantity
            |--------------------------------------------------------------------------
            */

            if (
                $freeQuantity >
                (int) ($saleItem->free_quantity ?? 0)
            ) {

                throw ValidationException::withMessages([
                    "items.$index.free_quantity" => [
                        "Return free quantity cannot exceed the free quantity sold of {$saleItem->free_quantity}."
                    ],
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 9. At least one item must actually be returned
        |--------------------------------------------------------------------------
        */

        if (!$hasReturnItem) {

            throw ValidationException::withMessages([
                'items' => [
                    'At least one item must have a return quantity or return free quantity greater than zero.'
                ],
            ]);
        }
    }

    /**
     * Calculate subtotal.
     */
    protected function calculateSubtotal(array $items): float
    {
        return collect($items)->sum(function ($item) {

            $saleItem = SaleItem::findOrFail(
                $item['sale_item_id']
            );

            $quantity =
                (int) ($item['quantity'] ?? 0);

            return $quantity
                * (float) $saleItem->selling_price;
        });
    }

    /**
     * Calculate item total.
     */
    protected function calculateItemTotal(int $quantity,float $sellingPrice,float $discount,float $tax): float 
    {
        $subtotal = $quantity * $sellingPrice;

        return $subtotal - $discount + $tax;
    }

    /**
     * Generate unique return number.
     */
    protected function generateReturnNumber(): string
    {
        do {
            $number =
                'SRET-' . random_int(100000, 999999);

        } while (
            SaleReturn::withTrashed()
                ->where('return_number', $number)
                ->exists()
        );

        return $number;
    }

    /**
     * Get sale items prepared for a sale return.
     *
     * For create:
     * - Counts all completed returns.
     *
     * For edit:
     * - Counts completed returns except the current sale return.
     */
    public function getSaleItemsForReturn(Sale $sale, ?SaleReturn $currentReturn = null) 
    {
        $sale->load([
            'customer',
            'items.medicine',
        ]);

        $saleItemIds = $sale->items->pluck('id');

        /*
        * Get quantities already returned through completed returns.
        *
        * When editing, exclude the current return because its
        * quantities are being edited rather than treated as
        * an additional return.
        */
        $returned = SaleReturnItem::query()
            ->selectRaw('
                sale_item_id,
                SUM(quantity) as returned_quantity,
                SUM(free_quantity) as returned_free_quantity
            ')
            ->whereIn('sale_item_id', $saleItemIds)
            ->whereHas('saleReturn', function ($query) {

                $query->where('status', 'completed');

            })
            ->groupBy('sale_item_id')
            ->get()
            ->keyBy('sale_item_id');

        return $sale->items->map(function ($item) use ($returned) {

            $returnData = $returned->get($item->id);

            $returnedQuantity = (int) (
                $returnData?->returned_quantity ?? 0
            );
            // dd($returnedQuantity);

            $returnedFreeQuantity = (int) (
                $returnData?->returned_free_quantity ?? 0
            );

            $item->returned_quantity = $returnedQuantity;

            $item->returned_free_quantity = $returnedFreeQuantity;

            $item->remaining_quantity = max(
                0,
                (int) $item->quantity - $returnedQuantity
            );

            $item->remaining_free_quantity = max(
                0,
                (int) $item->free_quantity - $returnedFreeQuantity
            );

            return $item;
        });
    }
    
    
}
