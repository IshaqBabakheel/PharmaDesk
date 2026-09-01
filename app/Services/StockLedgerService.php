<?php

namespace App\Services;

use App\Models\PurchaseItem;
use App\Models\PurchaseReturnItem;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use App\Models\StockAdjustmentItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;

// class StockLedgerService
// {
//     public function getLedger(array $filters = []): Collection
//     {
//         $purchaseItems = PurchaseItem::query()
//             ->with([
//                 'medicine:id,name',
//                 'purchase:id,purchase_number,purchase_date,status,deleted_at',
//             ])
//             ->when(
//                 !empty($filters['medicine_id']),
//                 fn($q) => $q->where('medicine_id', $filters['medicine_id'])
//             )
//             ->when(
//                 !empty($filters['batch_number']),
//                 fn($q) => $q->where(
//                     'batch_number',
//                     'like',
//                     '%' . $filters['batch_number'] . '%'
//                 )
//             )
//             ->get();

//         if ($purchaseItems->isEmpty()) {
//             return collect();
//         }

//         $purchaseItemIds = $purchaseItems->pluck('id');

//         $purchaseReturns = PurchaseReturnItem::query()
//             ->whereIn('purchase_item_id', $purchaseItemIds)
//             ->whereHas('purchaseReturn', function ($query) {
//                 $query->where('status', 'Completed')
//                     ->whereNull('deleted_at');
//             })
//             ->with('purchaseReturn:id,return_number,return_date,status,deleted_at')
//             ->get()
//             ->groupBy('purchase_item_id');

//         $sales = SaleItem::query()
//             ->whereIn('purchase_item_id', $purchaseItemIds)
//             ->whereHas('sale', function ($query) {
//                 $query->where('status', 'completed')
//                     ->whereNull('deleted_at');
//             })
//             ->with('sale:id,invoice_number,sale_date,status,deleted_at')
//             ->get()
//             ->groupBy('purchase_item_id');

//         $saleReturns = SaleReturnItem::query()
//             ->whereIn('purchase_item_id', $purchaseItemIds)
//             ->whereHas('saleReturn', function ($query) {
//                 $query->where('status', 'completed')
//                     ->whereNull('deleted_at');
//             })
//             ->with('saleReturn:id,return_number,return_date,status,deleted_at')
//             ->get()
//             ->groupBy('purchase_item_id');

//         $adjustments = StockAdjustmentItem::query()
//             ->whereIn('purchase_item_id', $purchaseItemIds)
//             ->whereHas('stockAdjustment', function ($query) {
//                 $query->where('status', 'completed')
//                     ->where('stock_applied', true)
//                     ->whereNull('deleted_at');
//             })
//             ->with('stockAdjustment:id,adjustment_number,type,adjustment_date,status,deleted_at')
//             ->get()
//             ->groupBy('purchase_item_id');

//         $ledger = collect();

//         foreach ($purchaseItems as $purchaseItem) {
//             $events = collect();

//             if (
//                 $purchaseItem->source === 'opening_stock'
//             ) {
//                 $events->push($this->event(
//                     $purchaseItem,
//                     'Opening Stock',
//                     $purchaseItem->created_at,
//                     'OPENING-' . $purchaseItem->id,
//                     $purchaseItem->quantity + $purchaseItem->free_quantity,
//                     0,
//                     'opening_stock'
//                 ));
//             }

//             if (
//                 $purchaseItem->source === 'adjustment'
//                 && $adjustments->has($purchaseItem->id)
//             ) {
//                 foreach ($adjustments->get($purchaseItem->id) as $adjustment) {
//                     if ($adjustment->stockAdjustment->type === 'increase') {
//                         $events->push($this->event(
//                             $purchaseItem,
//                             'Stock Adjustment',
//                             $adjustment->stockAdjustment->adjustment_date,
//                             $adjustment->stockAdjustment->adjustment_number,
//                             $adjustment->quantity,
//                             0,
//                             'adjustment'
//                         ));
//                     }
//                 }
//             }

//             if (
//                 $purchaseItem->source === 'purchase'
//                 && $purchaseItem->purchase
//                 && $purchaseItem->purchase->status === 'Completed'
//                 && !$purchaseItem->purchase->deleted_at
//             ) {
//                 $events->push($this->event(
//                     $purchaseItem,
//                     'Purchase',
//                     $purchaseItem->purchase->purchase_date,
//                     $purchaseItem->purchase->purchase_number,
//                     $purchaseItem->quantity + $purchaseItem->free_quantity,
//                     0,
//                     'purchase'
//                 ));
//             }

//             foreach ($purchaseReturns->get($purchaseItem->id, []) as $return) {
//                 $events->push($this->event(
//                     $purchaseItem,
//                     'Purchase Return',
//                     $return->purchaseReturn->return_date,
//                     $return->purchaseReturn->return_number,
//                     0,
//                     $return->quantity,
//                     'purchase_return'
//                 ));
//             }

//             foreach ($sales->get($purchaseItem->id, []) as $sale) {
//                 $events->push($this->event(
//                     $purchaseItem,
//                     'Sale',
//                     $sale->sale->sale_date,
//                     $sale->sale->invoice_number,
//                     0,
//                     $sale->quantity + $sale->free_quantity,
//                     'sale'
//                 ));
//             }

//             foreach ($saleReturns->get($purchaseItem->id, []) as $return) {
//                 $events->push($this->event(
//                     $purchaseItem,
//                     'Sale Return',
//                     $return->saleReturn->return_date,
//                     $return->saleReturn->return_number,
//                     $return->quantity + ($return->free_quantity ?? 0),
//                     0,
//                     'sale_return'
//                 ));
//             }

//             foreach ($adjustments->get($purchaseItem->id, []) as $adjustment) {
//                 if ($adjustment->stockAdjustment->type === 'decrease') {
//                     $events->push($this->event(
//                         $purchaseItem,
//                         'Stock Adjustment',
//                         $adjustment->stockAdjustment->adjustment_date,
//                         $adjustment->stockAdjustment->adjustment_number,
//                         0,
//                         $adjustment->quantity,
//                         'adjustment'
//                     ));
//                 }
//             }

//             $balance = 0;

//             foreach (
//                 $events->sortBy(function ($event) {
//                     return [
//                         Carbon::parse($event['date'])->timestamp,
//                         $event['_sequence'],
//                     ];
//                 })->values() as $event
//             ) {
//                 $balance += $event['in'] - $event['out'];
//                 $event['balance'] = max(0, $balance);
//                 unset($event['_sequence']);

//                 $ledger->push($event);
//             }
//         }

//         return $this->applyDateFilter(
//             $ledger,
//             $filters
//         )->sortByDesc(function ($item) {
//             return Carbon::parse($item['date'])->timestamp;
//         })->values();
//     }

//     protected function event(
//         PurchaseItem $purchaseItem,
//         string $transaction,
//         $date,
//         string $reference,
//         int|float $in,
//         int|float $out,
//         string $type
//     ): array {
//         return [
//             'date' => $date,
//             'medicine_id' => $purchaseItem->medicine_id,
//             'medicine' => $purchaseItem->medicine?->name ?? '-',
//             'purchase_item_id' => $purchaseItem->id,
//             'batch_number' => $purchaseItem->batch_number ?? '-',
//             'transaction' => $transaction,
//             'reference' => $reference,
//             'in' => (float) $in,
//             'out' => (float) $out,
//             'balance' => 0,
//             'type' => $type,
//             '_sequence' => $this->sequence($type),
//         ];
//     }

//     protected function sequence(string $type): int
//     {
//         return match ($type) {
//             'purchase', 'opening_stock', 'adjustment' => 1,
//             'purchase_return' => 2,
//             'sale' => 3,
//             'sale_return' => 4,
//             default => 5,
//         };
//     }

//     protected function applyDateFilter(
//         Collection $ledger,
//         array $filters
//     ): Collection {
//         if (!empty($filters['date_from'])) {
//             $from = Carbon::parse($filters['date_from'])->startOfDay();

//             $ledger = $ledger->filter(
//                 fn($item) => Carbon::parse($item['date'])->gte($from)
//             );
//         }

//         if (!empty($filters['date_to'])) {
//             $to = Carbon::parse($filters['date_to'])->endOfDay();

//             $ledger = $ledger->filter(
//                 fn($item) => Carbon::parse($item['date'])->lte($to)
//             );
//         }

//         return $ledger->values();
//     }

//     public function getBatchLedger(
//         PurchaseItem $purchaseItem
//     ): Collection {
//         return $this->getLedger([
//             'medicine_id' => $purchaseItem->medicine_id,
//             'batch_number' => $purchaseItem->batch_number,
//         ])->where(
//             'purchase_item_id',
//             $purchaseItem->id
//         )->values();
//     }
// }


class StockLedgerService
{
    public function getLedger(array $filters = []): Collection
    {
        $transactions = collect();

        $medicineId = $filters['medicine_id'] ?? null;
        $batchNumber = $filters['batch_number'] ?? null;

        $this->addPurchases(
            $transactions,
            $medicineId,
            $batchNumber
        );

        $this->addPurchaseReturns(
            $transactions,
            $medicineId,
            $batchNumber
        );

        $this->addSales(
            $transactions,
            $medicineId,
            $batchNumber
        );

        $this->addSaleReturns(
            $transactions,
            $medicineId,
            $batchNumber
        );

        $this->addAdjustments(
            $transactions,
            $medicineId,
            $batchNumber
        );

        $transactions = $transactions
            ->sortBy([
                ['date', 'asc'],
                ['sort_id', 'asc'],
            ])
            ->values();

        $runningBalances = [];

        $transactions = $transactions->map(
            function (array $row) use (&$runningBalances) {

                $key =
                    $row['medicine_id']
                    . '|' .
                    ($row['batch_number'] ?? '');

                $runningBalances[$key] =
                    ($runningBalances[$key] ?? 0)
                    + $row['in']
                    - $row['out'];

                $row['balance'] =
                    max(
                        0,
                        $runningBalances[$key]
                    );

                return $row;
            }
        );

        return $this->applyFilters(
            $transactions,
            $filters
        );
    }


    protected function addPurchases(
        Collection &$transactions,
        ?int $medicineId,
        ?string $batchNumber
    ): void {
        $items = PurchaseItem::query()
            ->where(function ($query) {
                $query
                    ->where('source', 'opening_stock')
                    ->orWhereHas('purchase', function ($query) {
                        $query
                            ->where('status', 'Completed')
                            ->whereNull('deleted_at');
                    });
            })
            ->when(
                $medicineId,
                fn ($q) => $q->where(
                    'medicine_id',
                    $medicineId
                )
            )
            ->when(
                $batchNumber,
                fn ($q) => $q->where(
                    'batch_number',
                    $batchNumber
                )
            )
            ->with([
                'purchase:id,purchase_number,purchase_date,status,deleted_at',
                'medicine:id,name',
            ])
            ->get();

        foreach ($items as $item) {

            $quantity =
                (int) $item->quantity
                + (int) $item->free_quantity;

            if ($quantity <= 0) {
                continue;
            }

            $isOpening =
                $item->source === 'opening_stock';

            $transactions->push([
                'date' => $isOpening
                    ? $item->created_at
                    : $item->purchase?->purchase_date,
                'medicine_id' => $item->medicine_id,
                'medicine' => $item->medicine?->name ?? '-',
                'batch_number' => $item->batch_number ?? '-',
                'transaction' => $isOpening
                    ? 'Opening Stock'
                    : 'Purchase',
                'reference' => $isOpening
                    ? 'Opening Stock'
                    : $item->purchase?->purchase_number,
                'in' => $quantity,
                'out' => 0,
                'sort_id' => $item->id,
            ]);
        }
    }


    protected function addPurchaseReturns(
        Collection &$transactions,
        ?int $medicineId,
        ?string $batchNumber
    ): void {
        $items = PurchaseReturnItem::query()
            ->whereHas('purchaseReturn', function ($query) {
                $query
                    ->where('status', 'Completed')
                    ->whereNull('deleted_at');
            })
            ->when(
                $medicineId,
                fn ($q) => $q->where(
                    'medicine_id',
                    $medicineId
                )
            )
            ->when(
                $batchNumber,
                fn ($q) => $q->where(
                    'batch_number',
                    $batchNumber
                )
            )
            ->with([
                'purchaseReturn:id,return_number,return_date,status,deleted_at',
                'medicine:id,name',
            ])
            ->get();

        foreach ($items as $item) {

            $quantity = (int) $item->quantity;

            if ($quantity <= 0) {
                continue;
            }

            $transactions->push([
                'date' =>
                    $item->purchaseReturn?->return_date,
                'medicine_id' => $item->medicine_id,
                'medicine' => $item->medicine?->name ?? '-',
                'batch_number' => $item->batch_number ?? '-',
                'transaction' => 'Purchase Return',
                'reference' =>
                    $item->purchaseReturn?->return_number,
                'in' => 0,
                'out' => $quantity,
                'sort_id' => $item->id,
            ]);
        }
    }


    protected function addSales(
        Collection &$transactions,
        ?int $medicineId,
        ?string $batchNumber
    ): void {
        $items = SaleItem::query()
            ->whereHas('sale', function ($query) {
                $query
                    ->where('status', 'completed')
                    ->whereNull('deleted_at');
            })
            ->when(
                $medicineId,
                fn ($q) => $q->where(
                    'medicine_id',
                    $medicineId
                )
            )
            ->when(
                $batchNumber,
                function ($q) use ($batchNumber) {
                    $q->whereHas(
                        'purchaseItem',
                        fn ($query) =>
                        $query->where(
                            'batch_number',
                            $batchNumber
                        )
                    );
                }
            )
            ->with([
                'sale:id,invoice_number,sale_date,status,deleted_at',
                'medicine:id,name',
                'purchaseItem:id,batch_number',
            ])
            ->get();

        foreach ($items as $item) {

            $quantity =
                (int) $item->quantity
                + (int) $item->free_quantity;

            if ($quantity <= 0) {
                continue;
            }

            $transactions->push([
                'date' =>
                    $item->sale?->sale_date,
                'medicine_id' => $item->medicine_id,
                'medicine' => $item->medicine?->name ?? '-',
                'batch_number' =>
                    $item->purchaseItem?->batch_number ?? '-',
                'transaction' => 'Sale',
                'reference' =>
                    $item->sale?->invoice_number,
                'in' => 0,
                'out' => $quantity,
                'sort_id' => $item->id,
            ]);
        }
    }


    protected function addSaleReturns(
        Collection &$transactions,
        ?int $medicineId,
        ?string $batchNumber
    ): void {
        $items = SaleReturnItem::query()
            ->whereHas('saleReturn', function ($query) {
                $query
                    ->where('status', 'completed')
                    ->whereNull('deleted_at');
            })
            ->when(
                $medicineId,
                fn ($q) => $q->where(
                    'medicine_id',
                    $medicineId
                )
            )
            ->when(
                $batchNumber,
                fn ($q) => $q->where(
                    'batch_number',
                    $batchNumber
                )
            )
            ->with([
                'saleReturn:id,return_number,return_date,status,deleted_at',
                'medicine:id,name',
            ])
            ->get();

        foreach ($items as $item) {

            $quantity =
                (int) $item->quantity
                + (int) ($item->free_quantity ?? 0);

            if ($quantity <= 0) {
                continue;
            }

            $transactions->push([
                'date' =>
                    $item->saleReturn?->return_date,
                'medicine_id' => $item->medicine_id,
                'medicine' => $item->medicine?->name ?? '-',
                'batch_number' => $item->batch_number ?? '-',
                'transaction' => 'Sale Return',
                'reference' =>
                    $item->saleReturn?->return_number,
                'in' => $quantity,
                'out' => 0,
                'sort_id' => $item->id,
            ]);
        }
    }


    protected function addAdjustments(
        Collection &$transactions,
        ?int $medicineId,
        ?string $batchNumber
    ): void {
        $items = StockAdjustmentItem::query()
            ->whereHas('stockAdjustment', function ($query) {
                $query
                    ->where('status', 'completed')
                    ->where('stock_applied', true)
                    ->whereNull('deleted_at');
            })
            ->when(
                $medicineId,
                fn ($q) => $q->where(
                    'medicine_id',
                    $medicineId
                )
            )
            ->when(
                $batchNumber,
                fn ($q) => $q->whereHas(
                    'purchaseItem',
                    fn ($query) =>
                    $query->where(
                        'batch_number',
                        $batchNumber
                    )
                )
            )
            ->with([
                'stockAdjustment:id,adjustment_number,adjustment_date,type,status,stock_applied,deleted_at',
                'medicine:id,name',
                'purchaseItem:id,batch_number',
            ])
            ->get();

        foreach ($items as $item) {

            $quantity = (int) $item->quantity;

            if ($quantity <= 0) {
                continue;
            }

            $isIncrease =
                $item->stockAdjustment?->type === 'increase';

            $transactions->push([
                'date' =>
                    $item->stockAdjustment?->adjustment_date,
                'medicine_id' => $item->medicine_id,
                'medicine' => $item->medicine?->name ?? '-',
                'batch_number' =>
                    $item->purchaseItem?->batch_number ?? '-',
                'transaction' => 'Stock Adjustment',
                'reference' =>
                    $item->stockAdjustment?->adjustment_number,
                'in' => $isIncrease ? $quantity : 0,
                'out' => $isIncrease ? 0 : $quantity,
                'sort_id' => $item->id,
            ]);
        }
    }


    protected function applyFilters(
        Collection $transactions,
        array $filters
    ): Collection {
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $transaction = $filters['transaction'] ?? null;

        return $transactions
            ->filter(function ($row) use (
                $dateFrom,
                $dateTo,
                $transaction
            ) {

                if ($dateFrom && $row['date']) {
                    if ($row['date']->format('Y-m-d') < $dateFrom) {
                        return false;
                    }
                }

                if ($dateTo && $row['date']) {
                    if ($row['date']->format('Y-m-d') > $dateTo) {
                        return false;
                    }
                }

                if (
                    $transaction
                    && $transaction !== 'all'
                    && $row['transaction'] !== $transaction
                ) {
                    return false;
                }

                return true;
            })
            ->values();
    }
}