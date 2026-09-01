<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesReportService
{
    public function getSales(array $filters = []): Collection
    {
        return Sale::query()
            ->with([
                'customer:id,name,phone',
            ])
            ->when(
                !empty($filters['date_from']),
                fn($q) => $q->whereDate(
                    'sale_date',
                    '>=',
                    $filters['date_from']
                )
            )
            ->when(
                !empty($filters['date_to']),
                fn($q) => $q->whereDate(
                    'sale_date',
                    '<=',
                    $filters['date_to']
                )
            )
            ->when(
                !empty($filters['customer_id']),
                fn($q) => $q->where(
                    'customer_id',
                    $filters['customer_id']
                )
            )
            ->when(
                !empty($filters['payment_status']),
                fn($q) => $q->where(
                    'payment_status',
                    $filters['payment_status']
                )
            )
            ->when(
                !empty($filters['status']),
                fn($q) => $q->where(
                    'status',
                    $filters['status']
                )
            )
            ->when(
                $filters['include_cancelled'] ?? false,
                fn($q) => $q,
                fn($q) => $q->where(
                    'status',
                    '!=',
                    'cancelled'
                )
            )
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->get();
    }

    public function getStatistics(array $filters = []): array
    {
        $query = Sale::query()
            ->when(
                !empty($filters['date_from']),
                fn($q) => $q->whereDate(
                    'sale_date',
                    '>=',
                    $filters['date_from']
                )
            )
            ->when(
                !empty($filters['date_to']),
                fn($q) => $q->whereDate(
                    'sale_date',
                    '<=',
                    $filters['date_to']
                )
            )
            ->when(
                !empty($filters['customer_id']),
                fn($q) => $q->where(
                    'customer_id',
                    $filters['customer_id']
                )
            )
            ->when(
                !empty($filters['payment_status']),
                fn($q) => $q->where(
                    'payment_status',
                    $filters['payment_status']
                )
            )
            ->when(
                !empty($filters['status']),
                fn($q) => $q->where(
                    'status',
                    $filters['status']
                )
            )
            ->when(
                $filters['include_cancelled'] ?? false,
                fn($q) => $q,
                fn($q) => $q->where(
                    'status',
                    '!=',
                    'cancelled'
                )
            );

        $totals = (clone $query)
            ->selectRaw('
                COUNT(*) as sales_count,
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

        $saleIds = (clone $query)->pluck('id');

        $quantitySold = 0;
        $freeQuantity = 0;

        if ($saleIds->isNotEmpty()) {
            $items = SaleItem::query()
                ->whereIn('sale_id', $saleIds)
                ->selectRaw('
                    COALESCE(SUM(quantity), 0) as quantity,
                    COALESCE(SUM(free_quantity), 0) as free_quantity
                ')
                ->first();

            $quantitySold = (int) $items->quantity;
            $freeQuantity = (int) $items->free_quantity;
        }

        return [
            'sales_count' => (int) $totals->sales_count,
            'subtotal' => (float) $totals->subtotal,
            'discount' => (float) $totals->discount,
            'tax' => (float) $totals->tax,
            'shipping' => (float) $totals->shipping,
            'other_charges' => (float) $totals->other_charges,
            'grand_total' => (float) $totals->grand_total,
            'paid_amount' => (float) $totals->paid_amount,
            'due_amount' => (float) $totals->due_amount,
            'quantity_sold' => $quantitySold,
            'free_quantity' => $freeQuantity,
        ];
    }

    public function getSaleItems(Sale $sale): Collection
    {
        return $sale->load([
            'customer',
            'items.medicine',
            'items.purchaseItem',
        ])->items;
    }
}
