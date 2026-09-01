<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturnItem;
use App\Models\StockAdjustmentItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use Illuminate\Validation\ValidationException;

class StockService
{
    /**
     * Get current available stock.
     */
    // public function getAvailableStock(int $medicineId): int
    // {
    //     return (int) Medicine::findOrFail($medicineId)->current_stock;
    // }
    /**
     * Get total available batch stock.
     */
    public function getAvailableStock(int $medicineId): int
    {
        return (int) $this->getAvailableBatches($medicineId)
            ->sum('available_quantity');
    }

    /**
     * Validate available stock.
     */
    // public function validateStock(int $medicineId, int $quantity): void
    // {
    //     $available = $this->getAvailableStock($medicineId);
    //     if ($quantity > $available) {
    //         $medicine = Medicine::findOrFail($medicineId);

    //         throw ValidationException::withMessages([
    //             'items' => [
    //                 sprintf(
    //                     'Insufficient stock for "%s". Available: %s, Requested: %s. Please reduce the quantity or add more stock.',
    //                     $medicine->name,
    //                     number_format($available),
    //                     number_format($quantity)
    //                 )
    //             ]
    //         ]);
    //     }
    // }
    /**
     * Validate available batch stock.
     */
    public function validateStock(int $medicineId, int $quantity): bool
    {
        $available = $this->getAvailableBatches($medicineId)
            ->sum('available_quantity');


        if ($quantity > $available) {

            $medicine = Medicine::findOrFail($medicineId);

            throw ValidationException::withMessages([
                'items' => [
                    "{$medicine->name} does not have enough stock available."
                ]
            ]);
        }


        return true;
    }

    /**
     * Get FIFO batches.
     */
    // public function getAvailableBatches(int $medicineId)
    // {
    //     $purchaseItems = PurchaseItem::where('medicine_id', $medicineId)
    //         ->orderBy('expiry_date')
    //         ->orderBy('id')
    //         ->get();

    //     $availableBatches = collect();

    //     foreach ($purchaseItems as $purchaseItem) {

    //         $returned = PurchaseReturnItem::where('purchase_item_id', $purchaseItem->id)->sum('quantity');

    //         $sold = SaleItem::where('purchase_item_id', $purchaseItem->id)->sum('quantity');

    //         $available = $purchaseItem->quantity - $returned - $sold;

    //         if ($available <= 0) {
    //             continue;
    //         }

    //         $purchaseItem->available_quantity = $available;

    //         $availableBatches->push($purchaseItem);
    //     }

    //     return $availableBatches;
    // }
    /**
     * Get available medicine batches using FEFO.
     *
     * Only stock from completed sales is considered sold.
     */
    // public function getAvailableBatches(int $medicineId)
    // {
    //     $purchaseItems = PurchaseItem::query()
    //         ->where('medicine_id', $medicineId)
    //         ->where(function ($query) {
    //             $query
    //                 ->whereNull('expiry_date')
    //                 ->orWhereDate('expiry_date', '>=', now()->toDateString());
    //         })
    //         ->orderByRaw('expiry_date IS NULL')
    //         ->orderBy('expiry_date', 'asc')
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     $availableBatches = collect();

    //     foreach ($purchaseItems as $purchaseItem) {

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Purchase Returns
    //         |--------------------------------------------------------------------------
    //         */

    //         $returned = PurchaseReturnItem::where(
    //             'purchase_item_id',
    //             $purchaseItem->id
    //         )->sum('quantity');


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Completed Sales Only
    //         |--------------------------------------------------------------------------
    //         */

    //         $sold = SaleItem::where(
    //             'purchase_item_id',
    //             $purchaseItem->id
    //         )
    //             ->whereHas('sale', function ($query) {
    //                 $query->where('status', 'completed');
    //             })
    //             ->sum('quantity');


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Available Quantity
    //         |--------------------------------------------------------------------------
    //         */

    //         $available = (int) $purchaseItem->quantity
    //             - (int) $returned
    //             - (int) $sold;


    //         if ($available <= 0) {
    //             continue;
    //         }


    //         $purchaseItem->available_quantity = $available;

    //         $availableBatches->push($purchaseItem);
    //     }

    //     return $availableBatches;
    // }
    /**
     * Get available FIFO batches for a medicine.
     */
    // public function getAvailableBatches(int $medicineId)
    // {
    //     return PurchaseItem::query()
    //         ->where('medicine_id', $medicineId)
    //         ->where(function ($query) {
    //             $query->where('quantity', '>', 0)
    //                 ->orWhere('free_quantity', '>', 0);
    //         })
    //         ->orderByRaw('expiry_date IS NULL')
    //         ->orderBy('expiry_date')
    //         ->orderBy('id')
    //         ->get()
    //         ->map(function ($purchaseItem) {

    //             $received = (int) $purchaseItem->quantity
    //                 + (int) $purchaseItem->free_quantity;

    //             $returned = PurchaseReturnItem::where(
    //                 'purchase_item_id',
    //                 $purchaseItem->id
    //             )->sum('quantity');

    //             $sold = SaleItem::where(
    //                 'purchase_item_id',
    //                 $purchaseItem->id
    //             )->sum('quantity');

    //             $available = $received - $returned - $sold;

    //             $purchaseItem->available_quantity = max(0, $available);

    //             return $purchaseItem;
    //         })
    //         ->filter(function ($purchaseItem) {
    //             return $purchaseItem->available_quantity > 0;
    //         })
    //         ->values();
    // }
    /**
     * Get available FIFO batches for a medicine.
     */
    // public function getAvailableBatches(int $medicineId)
    // {
    //     return PurchaseItem::query()
    //         ->where('medicine_id', $medicineId)
    //         ->where(function ($query) {
    //             $query->where('quantity', '>', 0)
    //                 ->orWhere('free_quantity', '>', 0);
    //         })
    //         ->orderByRaw('expiry_date IS NULL')
    //         ->orderBy('expiry_date')
    //         ->orderBy('id')
    //         ->get()
    //         ->map(function ($purchaseItem) {

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Received stock
    //             |--------------------------------------------------------------------------
    //             */

    //             $received = (int) $purchaseItem->quantity
    //                 + (int) $purchaseItem->free_quantity;


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Purchase returns
    //             |--------------------------------------------------------------------------
    //             */

    //             $returned = PurchaseReturnItem::where(
    //                 'purchase_item_id',
    //                 $purchaseItem->id
    //             )->sum('quantity');


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Sales
    //             |--------------------------------------------------------------------------
    //             */

    //             $sold = SaleItem::where(
    //                 'purchase_item_id',
    //                 $purchaseItem->id
    //             )->sum('quantity');


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Sale returns
    //             |--------------------------------------------------------------------------
    //             */

    //             $saleReturned = SaleReturnItem::where(
    //                 'purchase_item_id',
    //                 $purchaseItem->id
    //             )
    //                 ->whereHas('saleReturn', function ($query) {
    //                     $query->where('status', 'completed');
    //                 })
    //                 ->sum('quantity');


    //             /*
    //             |--------------------------------------------------------------------------
    //             | Available batch stock
    //             |--------------------------------------------------------------------------
    //             */

    //             $available =
    //                 $received
    //                 - $returned
    //                 - $sold
    //                 + $saleReturned;


    //             $purchaseItem->available_quantity = max(
    //                 0,
    //                 $available
    //             );

    //             return $purchaseItem;
    //         })
    //         ->filter(function ($purchaseItem) {
    //             return $purchaseItem->available_quantity > 0;
    //         })
    //         ->values();
    // }
    /**
     * Get available FIFO batches for a medicine.
     */
    public function getAvailableBatches(int $medicineId)
    {
        return PurchaseItem::query()
            ->where('medicine_id', $medicineId)

            ->where(function ($query) {
                $query
                    ->where('quantity', '>', 0)
                    ->orWhere('free_quantity', '>', 0);
            })

            ->orderByRaw('expiry_date IS NULL')
            ->orderBy('expiry_date')
            ->orderBy('id')

            ->get()

            ->map(function ($purchaseItem) {

                /*
                |--------------------------------------------------------------------------
                | Received
                |--------------------------------------------------------------------------
                */
                $received = (int) $purchaseItem->quantity + (int) $purchaseItem->free_quantity;


                /*
                |--------------------------------------------------------------------------
                | Completed Purchase Returns
                |--------------------------------------------------------------------------
                */

                $returned = PurchaseReturnItem::query()
                    ->where('purchase_item_id',$purchaseItem->id)
                    ->whereHas('purchaseReturn', function ($query) {
                        $query
                            ->where('status', 'Completed')
                            ->whereNull('deleted_at');

                    })
                    ->sum('quantity');


                /*
                |--------------------------------------------------------------------------
                | Completed Sales
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | Paid + free quantities both consume physical stock.
                |
                */
                $sold = SaleItem::query()
                    ->where('purchase_item_id', $purchaseItem->id)
                    ->whereHas('sale', function ($query) {
                        $query
                            ->where('status', 'completed')
                            ->whereNull('deleted_at');
                    })
                    ->selectRaw(
                        'COALESCE(SUM(quantity), 0)
                        + COALESCE(SUM(free_quantity), 0) as total'
                    )
                    ->value('total');

                /*
                |--------------------------------------------------------------------------
                | Completed Sale Returns
                |--------------------------------------------------------------------------
                */

                $saleReturned = SaleReturnItem::query()
                    ->where('purchase_item_id', $purchaseItem->id)
                    ->whereHas('saleReturn', function ($query) {
                        $query
                            ->where('status', 'completed')
                            ->whereNull('deleted_at');
                    })
                    ->selectRaw(
                        'COALESCE(SUM(quantity), 0)
                        + COALESCE(SUM(free_quantity), 0) as total'
                    )
                    ->value('total');

                /*
                |--------------------------------------------------------------------------
                | Completed Stock Decrease Adjustments
                |--------------------------------------------------------------------------
                */
                $stockDecreased = StockAdjustmentItem::query()
                    ->where('purchase_item_id', $purchaseItem->id)
                    ->whereHas('stockAdjustment', function ($query) {
                        
                        $query
                            ->where('type', 'decrease')
                            ->where('status', 'completed')
                            ->where('stock_applied', true)
                            ->whereNull('deleted_at');
                    })
                    ->sum('quantity');    

                /*
                |--------------------------------------------------------------------------
                | Available
                |--------------------------------------------------------------------------
                */

                $available =
                    $received
                    - (int) $returned
                    - (int) $sold
                    + (int) $saleReturned
                    - (int) $stockDecreased;


                $purchaseItem->available_quantity = max(
                    0,
                    $available
                );


                return $purchaseItem;

            })

            ->filter(function ($purchaseItem) {

                return $purchaseItem->available_quantity > 0;

            })

            ->values();
    }

    /**
     * FIFO allocation.
     */
    // public function allocateFIFO(int $medicineId, int $quantity): array
    // {

    //     $remaining = $quantity;

    //     $allocated = [];

    //     $batches = $this->getAvailableBatches($medicineId);

    //     foreach ($batches as $batch) {

    //         if ($remaining <= 0) {
    //             break;
    //         }
    //         $take = min($remaining, $batch->available_quantity);

    //         $allocated[] = [
    //             'purchase_item_id' => $batch->id,
    //             'medicine_id' => $batch->medicine_id,
    //             'batch_number' => $batch->batch_number,
    //             'expiry_date' => $batch->expiry_date,
    //             'purchase_price' => $batch->purchase_price,
    //             'quantity' => $take,
    //         ];

    //         $remaining -= $take;
    //     }

    //     if ($remaining > 0) {

    //         $medicine = Medicine::findOrFail($medicineId);

    //         throw ValidationException::withMessages([
    //             'items' => [
    //                 "{$medicine->name} does not have enough stock."
    //             ]
    //         ]);
    //     }

    //     return $allocated;
    // }
    /**
     * Allocate stock using FEFO.
     */
    // public function allocateFIFO(int $medicineId, int $quantity): array
    // {
    //     $remaining = $quantity;

    //     $allocated = [];

    //     $batches = $this->getAvailableBatches($medicineId);


    //     foreach ($batches as $batch) {

    //         if ($remaining <= 0) {
    //             break;
    //         }


    //         $available = (int) $batch->available_quantity;

    //         $take = min(
    //             $remaining,
    //             $available
    //         );


    //         if ($take <= 0) {
    //             continue;
    //         }


    //         $allocated[] = [
    //             'purchase_item_id' => $batch->id,
    //             'medicine_id' => $batch->medicine_id,
    //             'batch_number' => $batch->batch_number,
    //             'expiry_date' => $batch->expiry_date,
    //             'purchase_price' => $batch->purchase_price,
    //             'quantity' => $take,
    //         ];


    //         $remaining -= $take;
    //     }


    //     if ($remaining > 0) {

    //         $medicine = Medicine::findOrFail($medicineId);

    //         throw ValidationException::withMessages([
    //             'items' => [
    //                 "{$medicine->name} does not have enough batch stock available."
    //             ]
    //         ]);
    //     }


    //     return $allocated;
    // }
    /**
     * Allocate stock using FIFO batches.
     */
    // public function allocateFIFO(int $medicineId, int $quantity): array
    // {
    //     $remaining = (int) $quantity;

    //     if ($remaining <= 0) {
    //         throw ValidationException::withMessages([
    //             'items' => [
    //                 'Sale quantity must be greater than zero.'
    //             ]
    //         ]);
    //     }

    //     $allocated = [];

    //     $batches = $this->getAvailableBatches($medicineId);

    //     foreach ($batches as $batch) {

    //         if ($remaining <= 0) {
    //             break;
    //         }

    //         $take = min(
    //             $remaining,
    //             (int) $batch->available_quantity
    //         );

    //         if ($take <= 0) {
    //             continue;
    //         }

    //         $allocated[] = [
    //             'purchase_item_id' => $batch->id,
    //             'medicine_id' => $batch->medicine_id,
    //             'batch_number' => $batch->batch_number,
    //             'expiry_date' => $batch->expiry_date,
    //             'purchase_price' => $batch->purchase_price,
    //             'quantity' => $take,
    //         ];

    //         $remaining -= $take;
    //     }

    //     if ($remaining > 0) {

    //         $medicine = Medicine::findOrFail($medicineId);

    //         throw ValidationException::withMessages([
    //             'items' => [
    //                 "{$medicine->name} does not have enough stock."
    //             ]
    //         ]);
    //     }

    //     return $allocated;
    // }
    /**
     * Allocate stock using FIFO batches.
     */
    public function allocateFIFO(int $medicineId, int $quantity): array 
    {
        $remaining =
            (int) $quantity;


        if ($remaining <= 0) {

            throw ValidationException::withMessages([
                'items' => [
                    'Sale quantity must be greater than zero.'
                ],
            ]);
        }


        $allocated = [];


        $batches =
            $this->getAvailableBatches(
                $medicineId
            );


        foreach ($batches as $batch) {

            if ($remaining <= 0) {
                break;
            }


            $available =
                (int) $batch->available_quantity;


            $take =
                min(
                    $remaining,
                    $available
                );


            if ($take <= 0) {
                continue;
            }


            $allocated[] = [

                'purchase_item_id' =>
                    $batch->id,

                'medicine_id' =>
                    $batch->medicine_id,

                'batch_number' =>
                    $batch->batch_number,

                'expiry_date' =>
                    $batch->expiry_date,

                'purchase_price' =>
                    $batch->purchase_price,

                'quantity' =>
                    $take,

            ];


            $remaining -=
                $take;
        }


        if ($remaining > 0) {

            $medicine =
                Medicine::findOrFail(
                    $medicineId
                );


            throw ValidationException::withMessages([
                'items' => [
                    "{$medicine->name} does not have enough stock."
                ],
            ]);
        }


        return $allocated;
    }

    /**
     * Deduct medicine stock.
     */
    public function deductMedicineStock(int $medicineId, int $quantity): void
    {
        Medicine::whereKey($medicineId)->decrement('current_stock', $quantity);
    }

    /**
     * Restore medicine stock.
     */
    public function restoreMedicineStock(int $medicineId, int $quantity): void
    {
        Medicine::whereKey($medicineId)->increment('current_stock', $quantity);
    }

    /**
     * Deduct stock after sale.
     */
    // public function deductStockFromSale(Sale $sale): void
    // {
    //     foreach ($sale->items as $item) {

    //         $this->deductMedicineStock(
    //             $item->medicine_id,
    //             $item->quantity + $item->free_quantity
    //         );
    //     }
    // }
    /**
     * Deduct stock after completing a sale.
     */
    // public function deductStockFromSale(Sale $sale): void
    // {
    //     $sale->loadMissing('items');


    //     foreach ($sale->items as $item) {

    //         $medicine = Medicine::lockForUpdate()
    //             ->findOrFail($item->medicine_id);


    //         $quantity = (int) $item->quantity;

    //         $freeQuantity = (int) ($item->free_quantity ?? 0);

    //         $totalQuantity = $quantity + $freeQuantity;


    //         if ($medicine->current_stock < $totalQuantity) {

    //             throw ValidationException::withMessages([
    //                 'items' => [
    //                     "{$medicine->name} does not have enough stock."
    //                 ]
    //             ]);
    //         }


    //         $medicine->decrement(
    //             'current_stock',
    //             $totalQuantity
    //         );
    //     }
    // }
    /**
     * Deduct stock after completing a sale.
     */
    public function deductStockFromSale(Sale $sale): void
    {
        $sale->loadMissing('items');

        foreach ($sale->items as $item) {

            $medicine = Medicine::lockForUpdate()
                ->findOrFail($item->medicine_id);

            $totalQuantity =
                (int) $item->quantity
                + (int) ($item->free_quantity ?? 0);


            if (
                $medicine->current_stock
                < $totalQuantity
            ) {

                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name} does not have enough stock."
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
     * Restore stock.
     */
    // public function restoreStockFromSale(Sale $sale): void
    // {
    //     foreach ($sale->items as $item) {

    //         $this->restoreMedicineStock(
    //             $item->medicine_id,
    //             $item->quantity + $item->free_quantity
    //         );
    //     }
    // }
    /**
     * Restore stock from a completed sale.
     */
    public function restoreStockFromSale(Sale $sale): void
    {
        $sale->loadMissing('items');


        foreach ($sale->items as $item) {

            $medicine = Medicine::lockForUpdate()
                ->findOrFail($item->medicine_id);


            $quantity = (int) $item->quantity;

            $freeQuantity = (int) ($item->free_quantity ?? 0);

            $totalQuantity = $quantity + $freeQuantity;


            $medicine->increment(
                'current_stock',
                $totalQuantity
            );
        }
    }

    /**
     * Validate multiple sale items.
     */
    public function validateSaleItems(array $items): void
    {
        $duplicates = [];

        foreach ($items as $item) {

            if (in_array($item['medicine_id'], $duplicates)) {

                throw ValidationException::withMessages([
                    'items' => [
                        'The same medicine cannot be added more than once.'
                    ]
                ]);
            }

            $duplicates[] = $item['medicine_id'];

            $medicine = Medicine::findOrFail($item['medicine_id']);

            if (!$medicine->status) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name} is inactive."
                    ]
                ]);
            }

            if ($item['quantity'] <= 0) {
                throw ValidationException::withMessages([
                    'items' => [
                        "{$medicine->name}: Quantity must be greater than zero."
                    ]
                ]);
            }

            $this->validateStock($medicine->id, $item['quantity']);
        }
    }
    
}