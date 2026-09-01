<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Collection;

class PurchaseReportService
{
    public function getPurchases(array $filters = []): Collection
    {
        return Purchase::query()
            ->with([
                'supplier:id,name,phone',
            ])
            ->when(
                !empty($filters['date_from']),
                fn ($q) => $q->whereDate(
                    'purchase_date',
                    '>=',
                    $filters['date_from']
                )
            )
            ->when(
                !empty($filters['date_to']),
                fn ($q) => $q->whereDate(
                    'purchase_date',
                    '<=',
                    $filters['date_to']
                )
            )
            ->when(
                !empty($filters['supplier_id']),
                fn ($q) => $q->where(
                    'supplier_id',
                    $filters['supplier_id']
                )
            )
            ->when(
                !empty($filters['payment_status']),
                fn ($q) => $q->where(
                    'payment_status',
                    $filters['payment_status']
                )
            )
            ->when(
                !empty($filters['status']),
                fn ($q) => $q->where(
                    'status',
                    $filters['status']
                )
            )
            ->orderByDesc('purchase_date')
            ->orderByDesc('id')
            ->get();
    }

    public function getStatistics(array $filters = []): array
    {
        $query = Purchase::query()
            ->when(
                !empty($filters['date_from']),
                fn ($q) => $q->whereDate(
                    'purchase_date',
                    '>=',
                    $filters['date_from']
                )
            )
            ->when(
                !empty($filters['date_to']),
                fn ($q) => $q->whereDate(
                    'purchase_date',
                    '<=',
                    $filters['date_to']
                )
            )
            ->when(
                !empty($filters['supplier_id']),
                fn ($q) => $q->where(
                    'supplier_id',
                    $filters['supplier_id']
                )
            )
            ->when(
                !empty($filters['payment_status']),
                fn ($q) => $q->where(
                    'payment_status',
                    $filters['payment_status']
                )
            )
            ->when(
                !empty($filters['status']),
                fn ($q) => $q->where(
                    'status',
                    $filters['status']
                )
            );

        $totals = (clone $query)
            ->selectRaw('
                COUNT(*) as purchase_count,
                COALESCE(SUM(subtotal), 0) as subtotal,
                COALESCE(SUM(discount), 0) as discount,
                COALESCE(SUM(tax), 0) as tax,
                COALESCE(SUM(shipping), 0) as shipping,
                COALESCE(SUM(other_charges), 0) as other_charges,
                COALESCE(SUM(grand_total), 0) as grand_total,
                COALESCE(SUM(paid_amount), 0) as paid_amount,
                COALESCE(SUM(due_amount), 0) as due_amount
            ')
            ->first();

        $purchaseIds = (clone $query)->pluck('id');

        $quantityPurchased = 0;
        $freeQuantity = 0;

        if ($purchaseIds->isNotEmpty()) {
            $items = PurchaseItem::query()
                ->whereIn('purchase_id', $purchaseIds)
                ->selectRaw('
                    COALESCE(SUM(quantity), 0) as quantity,
                    COALESCE(SUM(free_quantity), 0) as free_quantity
                ')
                ->first();

            $quantityPurchased = (int) $items->quantity;
            $freeQuantity = (int) $items->free_quantity;
        }

        return [
            'purchase_count' => (int) $totals->purchase_count,
            'subtotal' => (float) $totals->subtotal,
            'discount' => (float) $totals->discount,
            'tax' => (float) $totals->tax,
            'shipping' => (float) $totals->shipping,
            'other_charges' => (float) $totals->other_charges,
            'grand_total' => (float) $totals->grand_total,
            'paid_amount' => (float) $totals->paid_amount,
            'due_amount' => (float) $totals->due_amount,
            'quantity_purchased' => $quantityPurchased,
            'free_quantity' => $freeQuantity,
        ];
    }

    public function getPurchaseItems(Purchase $purchase): Collection
    {
        return $purchase->load([
            'supplier',
            'items.medicine',
        ])->items;
    }
}