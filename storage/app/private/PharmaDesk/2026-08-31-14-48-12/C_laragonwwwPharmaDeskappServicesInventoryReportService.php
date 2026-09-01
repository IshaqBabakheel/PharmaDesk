<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturnItem;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use App\Models\StockAdjustmentItem;
use App\Services\StockService;
use Illuminate\Support\Collection;

class InventoryReportService
{
    public function __construct(
        protected StockService $stockService
    ) {}

    public function getInventory(array $filters = []): Collection
    {
        $medicines = Medicine::query()
            ->where('status', true)
            ->when(!empty($filters['medicine_id']),
                fn ($q) => $q->where('id',
                    $filters['medicine_id']
                )
            )
            ->when(!empty($filters['medicine_category_id']),
                fn ($q) => $q->where(
                    'medicine_category_id',
                    $filters['medicine_category_id']
                )
            )
            ->with([
                'category:id,name',
                'type:id,name',
                'unit:id,name',
                'manufacturer:id,name',
            ])
            ->orderBy('name')
            ->get();

        if ($medicines->isEmpty()) {
            return collect();
        }

        $medicineIds = $medicines->pluck('id');

        $purchaseItems = PurchaseItem::query()
            ->whereIn('medicine_id', $medicineIds)
            ->with(['purchase:id,status,deleted_at'])
            ->get()
            ->groupBy('medicine_id');

        $purchaseItemIds = PurchaseItem::query()
            ->whereIn('medicine_id', $medicineIds)
            ->pluck('id');

        $purchaseReturns = PurchaseReturnItem::query()
            ->whereIn(
                'purchase_item_id',
                $purchaseItemIds
            )
            ->whereHas('purchaseReturn',
                function ($query) {
                    $query
                        ->where('status', 'Completed')
                        ->whereNull('deleted_at');
                }
            )
            ->get()
            ->groupBy('purchase_item_id');

        $sales = SaleItem::query()
            ->whereIn(
                'purchase_item_id',
                $purchaseItemIds
            )
            ->whereHas('sale',
                function ($query) {
                    $query
                        ->where('status', 'completed')
                        ->whereNull('deleted_at');
                }
            )
            ->get()
            ->groupBy('purchase_item_id');

        $saleReturns = SaleReturnItem::query()
            ->whereIn(
                'purchase_item_id',
                $purchaseItemIds
            )
            ->whereHas('saleReturn',
                function ($query) {
                    $query
                        ->where('status', 'completed')
                        ->whereNull('deleted_at');
                }
            )
            ->get()
            ->groupBy('purchase_item_id');

        $adjustments = StockAdjustmentItem::query()
            ->whereIn(
                'purchase_item_id',
                $purchaseItemIds
            )
            ->whereHas('stockAdjustment',
                function ($query) {
                    $query
                        ->where('status', 'completed')
                        ->where('stock_applied', true)
                        ->whereNull('deleted_at');
                }
            )
            ->with('stockAdjustment:id,type,status,deleted_at')
            ->get()
            ->groupBy('purchase_item_id');

        return $medicines
            ->map(function ($medicine) use (
                $purchaseItems,
                $purchaseReturns,
                $sales,
                $saleReturns,
                $adjustments,
            ) {
                $items = $purchaseItems->get(
                    $medicine->id,
                    collect()
                );

                $totalStock = 0;
                $batchCount = 0;
                $stockCost = 0;
                $retailValue = 0;
                $nearestExpiry = null;

                foreach ($items as $purchaseItem) {
                    $received =
                        (int) $purchaseItem->quantity
                        + (int) $purchaseItem->free_quantity;

                    if (
                        $purchaseItem->source === 'purchase'
                        && (
                            !$purchaseItem->purchase ||
                            $purchaseItem->purchase->status !== 'Completed' ||
                            $purchaseItem->purchase->deleted_at
                        )
                    ) {
                        $received = 0;
                    }

                    $returned =
                        (int) $purchaseReturns
                            ->get(
                                $purchaseItem->id,
                                collect()
                            )
                            ->sum('quantity');

                    $sold =
                        (int) $sales
                            ->get(
                                $purchaseItem->id,
                                collect()
                            )
                            ->sum(function ($item) {
                                return (int) $item->quantity
                                    + (int) $item->free_quantity;
                            });

                    $saleReturned =
                        (int) $saleReturns
                            ->get(
                                $purchaseItem->id,
                                collect()
                            )
                            ->sum(function ($item) {
                                return (int) $item->quantity
                                    + (int) ($item->free_quantity ?? 0);
                            });

                    $decreased =
                        (int) $adjustments
                            ->get(
                                $purchaseItem->id,
                                collect()
                            )
                            ->filter(function ($item) {
                                return $item->stockAdjustment?->type === 'decrease';
                            })
                            ->sum('quantity');

                    $available = max(
                        0,
                        $received
                        - $returned
                        - $sold
                        + $saleReturned
                        - $decreased
                    );

                    if ($available <= 0) {
                        continue;
                    }

                    $batchCount++;

                    $totalStock += $available;

                    $purchasePrice = (float) $purchaseItem->purchase_price;

                    $sellingPrice = (float) $purchaseItem->selling_price;

                    $stockCost += $available * $purchasePrice;

                    $retailValue += $available * $sellingPrice;

                    if (
                        $purchaseItem->expiry_date
                        && (
                            !$nearestExpiry
                            || $purchaseItem->expiry_date
                                ->lt($nearestExpiry)
                        )
                    ) {
                        $nearestExpiry = $purchaseItem->expiry_date;
                    }
                }

                $minimumStock =(float) (
                    $medicine->minimum_stock
                    ?? 0
                );

                $status = $this->stockStatus($totalStock, $minimumStock);
                $potentialProfit = $retailValue - $stockCost;

                return [
                    'medicine_id' => $medicine->id,
                    'medicine' => $medicine->name,
                    'medicine_category' => $medicine->category?->name ?? '-',
                    'type' => $medicine->type?->name ?? '-',
                    'unit' => $medicine->unit?->name ?? '-',
                    'manufacturer' => $medicine->manufacturer?->name ?? '-',
                    'current_stock' => (float) $medicine->current_stock,
                    'batch_stock' => $totalStock,
                    'batch_count' => $batchCount,
                    'purchase_price' => (float) ($medicine->purchase_price ?? 0),
                    'selling_price' => (float) ($medicine->selling_price ?? 0),
                    'stock_cost' => $stockCost,
                    'retail_value' => $retailValue,
                    'potential_profit' => $potentialProfit,
                    'nearest_expiry' => $nearestExpiry?->format('Y-m-d'),
                    'minimum_stock' => $minimumStock,
                    'stock_status' => $status,
                ];
            })
            ->filter(function ($item) use ($filters) {
                return $this->passesFilter(
                    $item,
                    $filters['stock_status'] ?? 'all'
                );
            })
            ->values();
    }

    public function getMedicineBatches(int $medicineId): Collection
    {
        return $this->stockService
            ->getAvailableBatches($medicineId)
            ->map(function ($purchaseItem) {

                $expiryDate = $purchaseItem->expiry_date;
                $daysRemaining = null;
                $expiryStatus = 'none';

                if ($expiryDate) {
                    $daysRemaining = now()->startOfDay()
                        ->diffInDays($expiryDate->startOfDay(), false);

                    $expiryStatus = match (true) {
                        $daysRemaining < 0 => 'expired',
                        $daysRemaining <= 7 => 'critical',
                        $daysRemaining <= 30 => 'warning',
                        $daysRemaining <= 90 => 'upcoming',
                        default => 'safe',
                    };
                }

                $available = (int) $purchaseItem->available_quantity;
                $purchasePrice = (float) $purchaseItem->purchase_price;
                $sellingPrice = (float) $purchaseItem->selling_price;

                return [
                    'purchase_item_id' => $purchaseItem->id,
                    'batch_number' => $purchaseItem->batch_number,
                    'expiry_date' => $expiryDate?->format('Y-m-d'),
                    'days_remaining' => $daysRemaining,
                    'expiry_status' => $expiryStatus,
                    'available_quantity' => $available,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'stock_cost' => $available * $purchasePrice,
                    'retail_value' => $available * $sellingPrice,
                    'potential_profit' => $available * ($sellingPrice - $purchasePrice),
                    'source' => $purchaseItem->source,
                    'purchase_number' => $purchaseItem->purchase?->purchase_number,
                ];
            })
            ->values();
    }

    public function getStatistics(array $filters = []): array 
    {
        $items = $this->getInventory($filters);

        return [
            'medicines' => $items->count(),
            'total_stock' => $items->sum('batch_stock'),
            'stock_cost' => $items->sum('stock_cost'),
            'retail_value' => $items->sum('retail_value'),
            'potential_profit' => $items->sum('potential_profit'),
            'low_stock' => $items->where('stock_status','low')->count(),
            'out_of_stock' => $items->where('stock_status','out')->count(),
        ];
    }

    protected function stockStatus(float $stock, float $minimumStock): string 
    {
        if ($stock <= 0) {
            return 'out';
        }

        if (
            $minimumStock > 0
            && $stock <= $minimumStock
        ) {
            return 'low';
        }

        return 'in';
    }

    protected function passesFilter(array $item, string $filter): bool 
    {
        return match ($filter) {
            'in' =>
                $item['stock_status'] === 'in',

            'low' =>
                $item['stock_status'] === 'low',

            'out' =>
                $item['stock_status'] === 'out',

            default =>
                true,
        };
    }
}