<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Support\Collection;

class FinancialReportService
{
    public function getSummary(array $filters = []): array
    {
        $sales = Sale::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'sale_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'sale_date',
                    '<=',
                    $date
                )
            );

        $saleReturns = SaleReturn::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '<=',
                    $date
                )
            );

        $purchases = Purchase::query()
            ->where('status', 'Completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'purchase_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'purchase_date',
                    '<=',
                    $date
                )
            );

        $purchaseReturns = PurchaseReturn::query()
            ->where('status', 'Completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '<=',
                    $date
                )
            );

        $receipts = Payment::query()
            ->where('type', 'receipt')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'payment_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'payment_date',
                    '<=',
                    $date
                )
            );

        $supplierPayments = Payment::query()
            ->where('type', 'payment')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'payment_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'payment_date',
                    '<=',
                    $date
                )
            );

        $salesTotal = (float) $sales->sum('grand_total');
        $saleReturnsTotal = (float) $saleReturns->sum('grand_total');

        $purchaseTotal = (float) $purchases->sum('grand_total');
        $purchaseReturnsTotal = (float) $purchaseReturns->sum('grand_total');

        $receiptsTotal = (float) $receipts->sum('amount');
        $supplierPaymentsTotal =
            (float) $supplierPayments->sum('amount');

        $netSales =
            max(
                $salesTotal - $saleReturnsTotal,
                0
            );

        $netPurchases =
            max(
                $purchaseTotal - $purchaseReturnsTotal,
                0
            );

        $netCashFlow = $receiptsTotal - $supplierPaymentsTotal;

        return [
            'sales_total' => $salesTotal,
            'sale_returns' => $saleReturnsTotal,
            'net_sales' => $netSales,

            'purchase_total' => $purchaseTotal,
            'purchase_returns' => $purchaseReturnsTotal,
            'net_purchases' => $netPurchases,

            'receipts' => $receiptsTotal,
            'supplier_payments' => $supplierPaymentsTotal,

            'customer_due' => $this->getCurrentCustomerDue(),

            'supplier_due' => $this->getCurrentSupplierDue(),

            'net_cash_flow' => $netCashFlow,
        ];
    }

    public function getTransactions(array $filters = []): Collection 
    {
        $transactions = collect();

        Sale::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'sale_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'sale_date',
                    '<=',
                    $date
                )
            )
            ->get([
                'id',
                'invoice_number',
                'sale_date',
                'grand_total',
            ])
            ->each(function ($sale) use ($transactions) {
                $transactions->push([
                    'date' => $sale->sale_date,
                    'type' => 'Sale',
                    'reference' => $sale->invoice_number,
                    'description' => 'Customer sale',
                    'in' => (float) $sale->grand_total,
                    'out' => 0,
                ]);
            });

        SaleReturn::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '<=',
                    $date
                )
            )
            ->get([
                'id',
                'return_number',
                'return_date',
                'grand_total',
            ])
            ->each(function ($return) use ($transactions) {
                $transactions->push([
                    'date' => $return->return_date,
                    'type' => 'Sale Return',
                    'reference' => $return->return_number,
                    'description' => 'Customer sale return',
                    'in' => 0,
                    'out' => (float) $return->grand_total,
                ]);
            });

        Purchase::query()
            ->where('status', 'Completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'purchase_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'purchase_date',
                    '<=',
                    $date
                )
            )
            ->get([
                'id',
                'purchase_number',
                'purchase_date',
                'grand_total',
            ])
            ->each(function ($purchase) use ($transactions) {
                $transactions->push([
                    'date' => $purchase->purchase_date,
                    'type' => 'Purchase',
                    'reference' => $purchase->purchase_number,
                    'description' => 'Supplier purchase',
                    'in' => 0,
                    'out' => (float) $purchase->grand_total,
                ]);
            });

        PurchaseReturn::query()
            ->where('status', 'Completed')
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'return_date',
                    '<=',
                    $date
                )
            )
            ->get([
                'id',
                'return_number',
                'return_date',
                'grand_total',
            ])
            ->each(function ($return) use ($transactions) {
                $transactions->push([
                    'date' => $return->return_date,
                    'type' => 'Purchase Return',
                    'reference' => $return->return_number,
                    'description' => 'Supplier purchase return',
                    'in' => (float) $return->grand_total,
                    'out' => 0,
                ]);
            });

        Payment::query()
            ->whereNull('deleted_at')
            ->when(
                $filters['date_from'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'payment_date',
                    '>=',
                    $date
                )
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($q, $date) => $q->whereDate(
                    'payment_date',
                    '<=',
                    $date
                )
            )
            ->get([
                'id',
                'payment_number',
                'payment_date',
                'type',
                'amount',
            ])
            ->each(function ($payment) use ($transactions) {
                $transactions->push([
                    'date' => $payment->payment_date,
                    'type' =>
                        $payment->type === 'receipt'
                            ? 'Receipt'
                            : 'Supplier Payment',
                    'reference' =>
                        $payment->payment_number,
                    'description' =>
                        $payment->type === 'receipt'
                            ? 'Customer payment received'
                            : 'Supplier payment made',
                    'in' =>
                        $payment->type === 'receipt'
                            ? (float) $payment->amount
                            : 0,
                    'out' =>
                        $payment->type === 'payment'
                            ? (float) $payment->amount
                            : 0,
                ]);
            });

        return $transactions
            ->sortByDesc('date')
            ->values();
    }

    protected function getCurrentCustomerDue(): float
    {
        return (float) Sale::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->sum('due_amount');
    }

    protected function getCurrentSupplierDue(): float
    {
        return (float) Purchase::query()
            ->where('status', 'Completed')
            ->whereNull('deleted_at')
            ->sum('due_amount');
    }
}