<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseReturnService
{
    public function store(array $data): PurchaseReturn
    {
        return DB::transaction(function () use ($data) {
            $purchase = Purchase::findOrFail($data['purchase_id']);
            
            $this->validateReturnQuantities(
                $purchase,
                (int) $data['supplier_id'],
                $data['status'],
                $data['items'],
            );

            $purchaseReturn = PurchaseReturn::create([
                'purchase_id' => $purchase->id,
                'supplier_id' => $data['supplier_id'],
                'return_date' => $data['return_date'],
                'subtotal' => $data['subtotal'],
                'discount_type' => $data['discount_type'],
                'discount' => $data['discount'] ?? 0,
                'tax_type' => $data['tax_type'],
                'tax' => $data['tax'] ?? 0,
                'grand_total' => $data['grand_total'],
                'status' => $data['status'],
                'stock_applied' => false,
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            foreach ($data['items'] as $item) {

                $purchaseItem = PurchaseItem::findOrFail(
                    $item['purchase_item_id']
                );

                PurchaseReturnItem::create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'purchase_item_id' => $purchaseItem->id,
                    'medicine_id' => $purchaseItem->medicine_id,
                    'batch_number' => $purchaseItem->batch_number,
                    'expiry_date' => $purchaseItem->expiry_date,
                    'quantity' => $item['quantity'],
                    'purchase_price' => $purchaseItem->purchase_price,
                    'total' => $item['total'],
                ]);
            }

            if ($purchaseReturn->status === 'Completed') {
                $this->applyStock($purchaseReturn);
            }

            return $purchaseReturn->fresh([
                'purchase',
                'supplier',
                'items.medicine',
                'items.purchaseItem',
            ]);
        });
    }

    // stock apply
    public function applyStock(PurchaseReturn $purchaseReturn): void
    {
        if ($purchaseReturn->stock_applied) {
            return;
        }

        $purchaseReturn->loadMissing('items');

        foreach ($purchaseReturn->items as $item) {

            $medicine = Medicine::lockForUpdate()
                ->findOrFail($item->medicine_id);

            if ($medicine->current_stock < $item->quantity) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name} does not have enough stock to complete this return."
                    ],
                ]);
            }

            $medicine->decrement(
                'current_stock',
                $item->quantity
            );
        }

        $purchaseReturn->update([
            'stock_applied' => true,
        ]);
    }

    // restore stock
    public function restoreStock(PurchaseReturn $purchaseReturn): void
    {
        if (!$purchaseReturn->stock_applied) {
            return;
        }

        $purchaseReturn->loadMissing('items');

        foreach ($purchaseReturn->items as $item) {

            Medicine::lockForUpdate()
                ->findOrFail($item->medicine_id)
                ->increment(
                    'current_stock',
                    $item->quantity
                );
        }

        $purchaseReturn->update([
            'stock_applied' => false,
        ]);
    }

    /**
     * Get returnable purchase items.
     */
    public function purchaseItems(Purchase $purchase)
    {
        $purchase->load('items.medicine');

        return $purchase->items->map(function ($item) {

            $returned = PurchaseReturnItem::query()
                ->where('purchase_item_id', $item->id)
                ->whereHas('purchaseReturn', function ($query) {
                    $query
                        ->where('status', 'Completed')
                        ->whereNull('deleted_at');
                })
                ->sum('quantity');

            $remaining = max(0, (int) $item->quantity - (int) $returned);

            return [
                'purchase_item_id' => $item->id,

                'medicine' => [
                    'id' => $item->medicine?->id,
                    'name' => $item->medicine?->name,
                ],

                'batch_number' => $item->batch_number,

                'expiry_date' => optional($item->expiry_date)->format('Y-m-d'),

                'quantity' => (int) $item->quantity,

                'purchase_price' => (float) $item->purchase_price,

                'returned_quantity' => (int) $returned,

                'remaining_quantity' => $remaining,
            ];
        });
    }

    /**
     * Validate purchase return before saving.
     */
    public function validateReturnQuantities(Purchase $purchase, int $supplierId,string $status, array $items,?PurchaseReturn $purchaseReturn = null): void 
    {
        /*
        |--------------------------------------------------------------------------
        | Purchase Validation
        |--------------------------------------------------------------------------
        */
        if ($purchase->trashed()) {
            throw ValidationException::withMessages([
                'purchase_id' => 'The selected purchase has been deleted.'
            ]);
        }

        if ($purchase->status === 'Cancelled') {
            throw ValidationException::withMessages([
                'purchase_id' => 'Cancelled purchases cannot be returned.'
            ]);
        }

        if ( $purchase->supplier_id != $supplierId) 
        {
            throw ValidationException::withMessages([
                'supplier_id' =>
                'Selected supplier does not belong to this purchase.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate Purchase Item Check
        |--------------------------------------------------------------------------
        */
        $purchaseItems = [];

        foreach ($items as $item) {
            if (in_array($item['purchase_item_id'], $purchaseItems)) {
                throw ValidationException::withMessages([
                    'items' => [
                        'The same medicine cannot be added more than once.'
                    ]
                ]);
            }
            $purchaseItems[] = $item['purchase_item_id'];
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Every Item
        |--------------------------------------------------------------------------
        */
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => [
                    'Please add at least one medicine.'
                ]
            ]);
        }

        foreach ($items as $item) {
            $purchaseItem = PurchaseItem::with('medicine')->findOrFail($item['purchase_item_id']);
            if ($item['quantity'] <= 0) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$purchaseItem->medicine->name}: Quantity must be greater than zero."
                    ]
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Batch Validation
            |--------------------------------------------------------------------------
            */
            if ($purchaseItem->batch_number != $item['batch_number']) 
            {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$purchaseItem->medicine->name}: Invalid batch selected."
                    ]
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Expiry Validation
            |--------------------------------------------------------------------------
            */
            if (optional($purchaseItem->expiry_date)->format('Y-m-d') != ($item['expiry_date'] ?: null)) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$purchaseItem->medicine->name}: Expiry date does not match."
                    ]
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Purchase Item Belongs To Purchase
            |--------------------------------------------------------------------------
            */
            if ($purchaseItem->purchase_id != $purchase->id) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$purchaseItem->medicine->name} does not belong to the selected purchase."
                    ]
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Medicine Validation
            |--------------------------------------------------------------------------
            */
            if ($purchaseItem->medicine_id != $item['medicine_id']) {
                throw ValidationException::withMessages([
                    'items' => [
                        "Invalid medicine selected."
                    ]
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Previously Returned Quantity
            |--------------------------------------------------------------------------
            */
            $returnedQty = PurchaseReturnItem::where('purchase_item_id', $purchaseItem->id)
            ->whereHas('purchaseReturn', function ($query) use ($purchaseReturn) {
                if ($purchaseReturn) {
                    $query->where('id', '!=', $purchaseReturn->id);
                }
            })
            ->sum('quantity');

            /*
            |--------------------------------------------------------------------------
            | Remaining Quantity
            |--------------------------------------------------------------------------
            */
            $remainingQty = $purchaseItem->quantity - $returnedQty;
            if ($item['quantity'] > $remainingQty) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$purchaseItem->medicine->name}: only {$remainingQty} can be returned."
                    ]
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Stock Validation
            |--------------------------------------------------------------------------
            */
            $medicine = $purchaseItem->medicine;
            if ($status === 'Completed' && $medicine->current_stock < $item['quantity']) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name} does not have enough stock."
                    ]
                ]);
            }
        }
    }
}