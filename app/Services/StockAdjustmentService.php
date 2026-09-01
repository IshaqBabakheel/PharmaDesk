<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\PurchaseItem;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockAdjustmentService
{
    /**
     * Create a stock adjustment.
     */
    public function store(array $data): StockAdjustment
    {
        return DB::transaction(function () use ($data) {
            /*
            |--------------------------------------------------------------------------
            | Validate Adjustment
            |--------------------------------------------------------------------------
            */
            $this->validateAdjustmentItems($data['type'], $data['items']);


            /*
            |--------------------------------------------------------------------------
            | Create Adjustment
            |--------------------------------------------------------------------------
            */
            $adjustment = StockAdjustment::create([
                'type' => $data['type'],
                'adjustment_date' => $data['adjustment_date'],
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'stock_applied' => false,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Adjustment Items
            |--------------------------------------------------------------------------
            */
            foreach ($data['items'] as $item) {
                $this->createAdjustmentItem($adjustment, $item);
            }


            /*
            |--------------------------------------------------------------------------
            | Apply Stock Immediately For Completed Adjustment
            |--------------------------------------------------------------------------
            */

            if ($adjustment->isCompleted()) {
                $this->applyStock($adjustment);
            }


            return $adjustment->fresh([
                'items.medicine',
                'items.purchaseItem',
            ]);
        });
    }


    /**
     * Update a stock adjustment.
     */
    public function update(StockAdjustment $adjustment, array $data): StockAdjustment 
    {

        return DB::transaction(function () use ($adjustment, $data) 
        {

            /*
            |--------------------------------------------------------------------------
            | Only Draft Adjustments Can Be Updated
            |--------------------------------------------------------------------------
            */

            if (!$adjustment->isDraft()) {

                throw ValidationException::withMessages([
                    'status' => [
                        'Only draft stock adjustments can be updated.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Validate New Data
            |--------------------------------------------------------------------------
            */

            $this->validateAdjustmentItems(
                $data['type'],
                $data['items']
            );


            /*
            |--------------------------------------------------------------------------
            | Remove Old Items
            |--------------------------------------------------------------------------
            */

            $adjustment->items()->delete();


            /*
            |--------------------------------------------------------------------------
            | Update Header
            |--------------------------------------------------------------------------
            */

            $adjustment->update([

                'type' =>
                    $data['type'],

                'adjustment_date' =>
                    $data['adjustment_date'],

                'reason' =>
                    $data['reason'],

                'notes' =>
                    $data['notes'] ?? null,

                'status' =>
                    $data['status'] ?? 'draft',

                'updated_by' =>
                    Auth::id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Create New Items
            |--------------------------------------------------------------------------
            */

            foreach ($data['items'] as $item) {

                $this->createAdjustmentItem(
                    $adjustment,
                    $item
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Apply If Updated Directly To Completed
            |--------------------------------------------------------------------------
            */

            if (
                $adjustment->isCompleted() &&
                !$adjustment->isStockApplied()
            ) {

                $this->applyStock(
                    $adjustment
                );
            }


            return $adjustment->fresh([
                'items.medicine',
                'items.purchaseItem',
            ]);
        });
    }


    /**
     * Complete a draft stock adjustment.
     */
    public function complete(StockAdjustment $adjustment): StockAdjustment 
    {

        return DB::transaction(function () use ($adjustment) {

            if (!$adjustment->isDraft()) {

                throw ValidationException::withMessages([
                    dd('i am inside the through'),
                    'status' => [
                        'Only draft stock adjustments can be completed.'
                    ],
                ]);
            }

            $adjustment->load('items');


            /*
            |--------------------------------------------------------------------------
            | Validate Again Before Applying Stock
            |--------------------------------------------------------------------------
            |
            | Important because stock may have changed after the draft
            | was created.
            |
            */
            $items = $adjustment->items
                ->map(function ($item) {
                    return [
                        'medicine_id' => $item->medicine_id,
                        'purchase_item_id' => $item->purchase_item_id,
                        'quantity' => $item->quantity,
                    ];

                })
                ->toArray();

            $this->validateAdjustmentItems($adjustment->type, $items);


            /*
            |--------------------------------------------------------------------------
            | Update Status
            |--------------------------------------------------------------------------
            */
            $adjustment->update([
                'status' => 'completed',
                'updated_by' => Auth::id(),
            ]);
            

            /*
            |--------------------------------------------------------------------------
            | Apply Stock
            |--------------------------------------------------------------------------
            */
            $this->applyStock($adjustment);

            return $adjustment->fresh([
                'items.medicine',
                'items.purchaseItem',
            ]);
        });
    }


    /**
     * Cancel a stock adjustment.
     */
    public function cancel(StockAdjustment $adjustment): StockAdjustment 
    {

        return DB::transaction(function () use ($adjustment) {

            if ($adjustment->isCancelled()) {

                throw ValidationException::withMessages([
                    'status' => [
                        'This stock adjustment is already cancelled.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Restore Stock If It Was Applied
            |--------------------------------------------------------------------------
            */

            if ($adjustment->isStockApplied()) {

                $this->restoreStock(
                    $adjustment
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Cancel
            |--------------------------------------------------------------------------
            */

            $adjustment->update([

                'status' =>
                    'cancelled',

                'updated_by' =>
                    Auth::id(),

            ]);


            return $adjustment->fresh([
                'items.medicine',
                'items.purchaseItem',
            ]);
        });
    }


    /**
     * Apply stock effect of an adjustment.
     */
    public function applyStock(StockAdjustment $adjustment): void 
    {

        if ($adjustment->isStockApplied()) {
            return;
        }


        $adjustment->loadMissing([
            'items.medicine',
            'items.purchaseItem',
        ]);


        foreach ($adjustment->items as $item) {

            if ($item->quantity <= 0) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Increase
            |--------------------------------------------------------------------------
            */

            if ($adjustment->type === 'increase') {
                /*
                |--------------------------------------------------------------------------
                | Create a new inventory batch
                |--------------------------------------------------------------------------
                */
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => null,
                    'source' => 'adjustment',
                    'medicine_id' => $item->medicine_id,
                    'batch_number' => $item->batch_number ??
                        (
                            'ADJ-' .
                            $adjustment->id .
                            '-' .
                            $item->medicine_id
                        ),
                    'expiry_date' => $item->expiry_date,
                    'quantity' => $item->quantity,
                    'free_quantity' => 0,
                    'purchase_price' => $item->purchase_price ?? 0,
                    'selling_price' => $item->selling_price ?? 0,
                    'discount' => 0,
                    'tax' => 0,
                    'total' => $item->quantity * 
                        (
                            (float) (
                                $item->purchase_price ?? 0
                            )
                        ),

                ]);


                /*
                |--------------------------------------------------------------------------
                | Link Adjustment Item To Created Batch
                |--------------------------------------------------------------------------
                */
                $item->update([
                    'purchase_item_id' => $purchaseItem->id,
                    'batch_number' => $purchaseItem->batch_number,
                    'expiry_date' => $purchaseItem->expiry_date,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Increase Medicine Stock
                |--------------------------------------------------------------------------
                */
                $medicine = Medicine::lockForUpdate()
                        ->findOrFail($item->medicine_id);


                $medicine->increment(
                    'current_stock',
                    $item->quantity
                );


                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Decrease
            |--------------------------------------------------------------------------
            */

            $purchaseItem =
                PurchaseItem::lockForUpdate()
                    ->findOrFail(
                        $item->purchase_item_id
                    );


            /*
            |--------------------------------------------------------------------------
            | Get Actual Available Batch Quantity
            |--------------------------------------------------------------------------
            */

            $available =
                $this->getAvailableBatches(
                    $item->medicine_id
                )
                ->firstWhere(
                    'id',
                    $purchaseItem->id
                );


            $availableQuantity =
                (int) (
                    $available?->available_quantity ?? 0
                );


            if (
                $item->quantity >
                $availableQuantity
            ) {

                $medicine =
                    Medicine::findOrFail(
                        $item->medicine_id
                    );


                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name} does not have enough stock in the selected batch."
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Decrease Medicine Stock
            |--------------------------------------------------------------------------
            */

            $medicine =
                Medicine::lockForUpdate()
                    ->findOrFail(
                        $item->medicine_id
                    );


            if (
                $medicine->current_stock <
                $item->quantity
            ) {

                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name} does not have enough current stock."
                    ],
                ]);
            }


            $medicine->decrement('current_stock', $item->quantity);
        }


        /*
        |--------------------------------------------------------------------------
        | Mark Applied
        |--------------------------------------------------------------------------
        */
        $adjustment->update(['stock_applied' => true]);
    }


    /**
     * Restore stock effect of an adjustment.
     */
    public function restoreStock(StockAdjustment $adjustment): void 
    {

        if (!$adjustment->isStockApplied()) {
            return;
        }

        $adjustment->loadMissing(['items']);

        foreach ($adjustment->items as $item) {

            /*
            |--------------------------------------------------------------------------
            | Increase Adjustment
            |--------------------------------------------------------------------------
            |
            | It created stock, so cancellation removes that stock.
            |
            */

            if ($adjustment->type === 'increase') {

                $medicine =
                    Medicine::lockForUpdate()
                        ->findOrFail(
                            $item->medicine_id
                        );


                if (
                    $medicine->current_stock <
                    $item->quantity
                ) {

                    throw ValidationException::withMessages([
                        'items' => [
                            "{$medicine->name} does not have enough current stock to reverse this adjustment."
                        ],
                    ]);
                }


                $medicine->decrement(
                    'current_stock',
                    $item->quantity
                );


                /*
                |--------------------------------------------------------------------------
                | Remove Adjustment-Created Batch
                |--------------------------------------------------------------------------
                */

                $purchaseItem = $item->purchaseItem;

                if ($purchaseItem) {

                    $purchaseItem->delete();

                }

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Decrease Adjustment
            |--------------------------------------------------------------------------
            |
            | It removed stock, so cancellation restores that stock.
            |
            */
            $medicine = Medicine::lockForUpdate()->findOrFail($item->medicine_id);

            $medicine->increment('current_stock', $item->quantity);
        }


        /*
        |--------------------------------------------------------------------------
        | Mark Stock As Not Applied
        |--------------------------------------------------------------------------
        */

        $adjustment->update([
            'stock_applied' => false,
        ]);
    }


    /**
     * Create a stock adjustment item.
     */
    private function createAdjustmentItem(StockAdjustment $adjustment,array $item): StockAdjustmentItem 
    {
        /*
        |--------------------------------------------------------------------------
        | Medicine
        |--------------------------------------------------------------------------
        */
        $medicine = Medicine::findOrFail($item['medicine_id']);


        /*
        |--------------------------------------------------------------------------
        | Purchase Batch
        |--------------------------------------------------------------------------
        */
        $purchaseItem = null;

        if (!empty($item['purchase_item_id'])) 
        {
            $purchaseItem = PurchaseItem::findOrFail($item['purchase_item_id']);

            if ($purchaseItem->medicine_id != $medicine->id) 
            {
                throw ValidationException::withMessages([
                    'items' => [
                        "The selected batch does not belong to {$medicine->name}."
                    ],
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Batch Information
        |--------------------------------------------------------------------------
        */

        $batchNumber =
            $item['batch_number']
            ??
            $purchaseItem?->batch_number;


        $expiryDate =
            $item['expiry_date']
            ??
            $purchaseItem?->expiry_date;


        $purchasePrice =
            $item['purchase_price']
            ??
            $purchaseItem?->purchase_price
            ??
            $medicine->purchase_price
            ??
            0;


        $sellingPrice =
            $item['selling_price']
            ??
            $purchaseItem?->selling_price
            ??
            $medicine->selling_price
            ??
            0;


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        return StockAdjustmentItem::create([
            'stock_adjustment_id' => $adjustment->id,
            'medicine_id' => $medicine->id,
            'purchase_item_id' => $purchaseItem?->id,
            'batch_number' => $batchNumber,
            'expiry_date' => $expiryDate,
            'quantity' => $item['quantity'],
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'notes' => $item['notes'] ?? null,
        ]);
    }


    /**
     * Get available FIFO batches for a medicine.
     *
     * Uses the existing StockService as the source of truth.
     */
    public function getAvailableBatches(int $medicineId) {
        return app(StockService::class)->getAvailableBatches($medicineId);
    }


    /**
     * Validate stock adjustment items.
     */
    private function validateAdjustmentItems(string $type, array $items): void 
    {

        if (empty($items)) {

            throw ValidationException::withMessages([
                'items' => [
                    'At least one stock adjustment item is required.'
                ],
            ]);
        }


        $submittedKeys = [];


        foreach ($items as $index => $item) {

            /*
            |--------------------------------------------------------------------------
            | Medicine
            |--------------------------------------------------------------------------
            */

            if (empty($item['medicine_id'])) {

                throw ValidationException::withMessages([
                    "items.$index.medicine_id" => [
                        'Medicine is required.'
                    ],
                ]);
            }


            $medicine =
                Medicine::find(
                    $item['medicine_id']
                );


            if (!$medicine) {

                throw ValidationException::withMessages([
                    "items.$index.medicine_id" => [
                        'Selected medicine does not exist.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $quantity =
                (int) (
                    $item['quantity'] ?? 0
                );


            if ($quantity <= 0) {

                throw ValidationException::withMessages([
                    "items.$index.quantity" => [
                        'Adjustment quantity must be greater than zero.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Decrease Requires Batch
            |--------------------------------------------------------------------------
            */

            if ($type === 'decrease' && empty($item['purchase_item_id'])) 
            {
                throw ValidationException::withMessages([
                    "items.$index.purchase_item_id" => [
                        'A stock batch is required for a decrease adjustment.'
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Batch
            |--------------------------------------------------------------------------
            */

            if (!empty($item['purchase_item_id'])) {

                $purchaseItem =
                    PurchaseItem::find(
                        $item['purchase_item_id']
                    );


                if (!$purchaseItem) {

                    throw ValidationException::withMessages([
                        "items.$index.purchase_item_id" => [
                            'Selected stock batch does not exist.'
                        ],
                    ]);
                }


                if (
                    $purchaseItem->medicine_id !=
                    $medicine->id
                ) {

                    throw ValidationException::withMessages([
                        "items.$index.purchase_item_id" => [
                            'Selected stock batch does not belong to the selected medicine.'
                        ],
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Validate Available Batch Quantity For Decrease
                |--------------------------------------------------------------------------
                */

                if ($type === 'decrease') {

                    $batch = $this->getAvailableBatches($medicine->id)
                        ->firstWhere('id', $purchaseItem->id);


                    $available =
                        (int) (
                            $batch?->available_quantity ?? 0
                        );


                    if ($quantity > $available) {

                        throw ValidationException::withMessages([
                            "items.$index.quantity" => [
                                "Only {$available} units are available in the selected batch."
                            ],
                        ]);
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Medicine + Batch Rows
            |--------------------------------------------------------------------------
            */

            $key =
                $medicine->id .
                ':' .
                ($item['purchase_item_id'] ?? 'new');


            if (
                in_array(
                    $key,
                    $submittedKeys,
                    true
                )
            ) {

                throw ValidationException::withMessages([
                    "items.$index.medicine_id" => [
                        'The same medicine and batch cannot be added more than once.'
                    ],
                ]);
            }


            $submittedKeys[] = $key;
        }
    }
}