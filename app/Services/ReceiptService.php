<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Support\Collection;

class ReceiptService
{
    public function getSaleData(Sale $sale): array
    {
        $sale->load([
            'customer',
            'items.medicine',
            'items.purchaseItem',
            'payments' => fn ($query) =>
                $query
                    ->whereNull('deleted_at')
                    ->orderBy('payment_date')
                    ->orderBy('id'),
        ]);

        return [
            'sale' => $sale,
            'customer' => $sale->customer,
            'items' => $sale->items,
            'payments' => $sale->payments,
            'payment_methods' =>
                $this->paymentMethods($sale->payments),
            'total_paid' =>
                $this->totalPaid($sale->payments),
            'settings' =>
                Setting::pluck('value', 'key')->toArray(),
        ];
    }

    protected function paymentMethods(
        Collection $payments
    ): Collection {
        return $payments
            ->map(
                fn ($payment) =>
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $payment->method
                    )
                )
            )
            ->unique()
            ->values();
    }

    protected function totalPaid(
        Collection $payments
    ): float {
        return (float) $payments->sum('amount');
    }
}