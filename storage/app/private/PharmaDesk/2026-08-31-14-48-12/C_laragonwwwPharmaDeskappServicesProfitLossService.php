<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Illuminate\Support\Collection;

class ProfitLossService
{
    /*
    |--------------------------------------------------------------------------
    | Overall Profit & Loss Summary
    |--------------------------------------------------------------------------
    |
    | Formula:
    |
    | Net Sales
    |     = Sales Revenue
    |     - Sales Discounts
    |     - Sale Returns
    |     + Sale Return Discounts
    |
    | Gross Profit
    |     = Net Sales - COGS
    |
    | Net Profit
    |     = Gross Profit - Operating Expenses
    |
    */

    public function getSummary(array $filters = []): array
    {
        $sales = $this->salesQuery($filters);

        $saleReturns = $this->saleReturnsQuery($filters);

        $expenses = $this->expensesQuery($filters);

        /*
        |--------------------------------------------------------------------------
        | Sales
        |--------------------------------------------------------------------------
        */

        $salesRevenue = (float) $sales->sum('subtotal');

        $salesDiscount = (float) $sales->sum('discount');

        /*
        |--------------------------------------------------------------------------
        | Sale Returns
        |--------------------------------------------------------------------------
        */

        $saleReturnTotal = (float) $saleReturns->sum('subtotal');

        $saleReturnDiscount = (float) $saleReturns->sum('discount');

        /*
        |--------------------------------------------------------------------------
        | Net Sales
        |--------------------------------------------------------------------------
        */

        $netSales =
            $salesRevenue
            - $salesDiscount
            - $saleReturnTotal
            + $saleReturnDiscount;

        /*
        |--------------------------------------------------------------------------
        | Cost of Goods Sold
        |--------------------------------------------------------------------------
        */

        $cogs = $this->calculateCOGS($filters);

        /*
        |--------------------------------------------------------------------------
        | Gross Profit
        |--------------------------------------------------------------------------
        */

        $grossProfit = $netSales - $cogs;

        /*
        |--------------------------------------------------------------------------
        | Operating Expenses
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | P&L uses the full expense amount, not paid_amount.
        |
        | Example:
        | Expense = 100,000
        | Paid    = 40,000
        | Due     = 60,000
        |
        | P&L expense = 100,000
        |
        */

        $operatingExpenses = (float) $expenses->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Net Profit
        |--------------------------------------------------------------------------
        */

        $netProfit =
            $grossProfit
            - $operatingExpenses;

        /*
        |--------------------------------------------------------------------------
        | Gross Margin
        |--------------------------------------------------------------------------
        */

        $grossMargin = $netSales > 0
            ? ($grossProfit / $netSales) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Net Margin
        |--------------------------------------------------------------------------
        */

        $netMargin = $netSales > 0
            ? ($netProfit / $netSales) * 100
            : 0;

        return [
            'sales_revenue' => $salesRevenue,

            'sales_discount' => $salesDiscount,

            'sale_returns' => $saleReturnTotal,

            'sale_return_discount' => $saleReturnDiscount,

            'net_sales' => $netSales,

            'cogs' => $cogs,

            'gross_profit' => $grossProfit,

            'gross_margin' => $grossMargin,

            'operating_expenses' => $operatingExpenses,

            'net_profit' => $netProfit,

            'net_margin' => $netMargin,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Monthly Profit & Loss
    |--------------------------------------------------------------------------
    */

    public function getMonthlySummary(array $filters = []): Collection
    {
        /*
        |--------------------------------------------------------------------------
        | Sales
        |--------------------------------------------------------------------------
        */

        $sales = $this->salesQuery($filters)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Sale Returns
        |--------------------------------------------------------------------------
        */

        $returns = $this->saleReturnsQuery($filters)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenses = $this->expensesQuery($filters)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Build Month List
        |--------------------------------------------------------------------------
        |
        | A month can contain:
        |
        | - Sales only
        | - Returns only
        | - Expenses only
        | - Sales + expenses
        | - All three
        |
        | Therefore all three sources must participate
        | in building the month collection.
        |
        */

        $months = collect();

        $months = $months
            ->merge(
                $sales
                    ->pluck('sale_date')
                    ->filter()
                    ->map(
                        fn($date) =>
                        $date->format('Y-m')
                    )
            );

        $months = $months
            ->merge(
                $returns
                    ->pluck('return_date')
                    ->filter()
                    ->map(
                        fn($date) =>
                        $date->format('Y-m')
                    )
            );

        $months = $months
            ->merge(
                $expenses
                    ->pluck('expense_date')
                    ->filter()
                    ->map(
                        fn($date) =>
                        $date->format('Y-m')
                    )
            );

        $months = $months
            ->unique()
            ->sort()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Calculate Each Month
        |--------------------------------------------------------------------------
        */

        return $months
            ->map(function ($month) use (
                $sales,
                $returns,
                $expenses
            ) {

                /*
                |--------------------------------------------------------------------------
                | Month Sales
                |--------------------------------------------------------------------------
                */

                $monthSales = $sales
                    ->filter(
                        fn($sale) =>
                        $sale->sale_date
                            ->format('Y-m') === $month
                    )
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | Month Returns
                |--------------------------------------------------------------------------
                */

                $monthReturns = $returns
                    ->filter(
                        fn($return) =>
                        $return->return_date
                            ->format('Y-m') === $month
                    )
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | Month Expenses
                |--------------------------------------------------------------------------
                */

                $monthExpenses = $expenses
                    ->filter(
                        fn($expense) =>
                        $expense->expense_date
                            ->format('Y-m') === $month
                    )
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | Revenue
                |--------------------------------------------------------------------------
                */

                $revenue =
                    (float) $monthSales->sum(
                        'subtotal'
                    );


                /*
                |--------------------------------------------------------------------------
                | Sales Discount
                |--------------------------------------------------------------------------
                */

                $discount =
                    (float) $monthSales->sum(
                        'discount'
                    );


                /*
                |--------------------------------------------------------------------------
                | Sale Return
                |--------------------------------------------------------------------------
                */

                $returnSubtotal =
                    (float) $monthReturns->sum(
                        'subtotal'
                    );


                /*
                |--------------------------------------------------------------------------
                | Sale Return Discount
                |--------------------------------------------------------------------------
                */

                $returnDiscount =
                    (float) $monthReturns->sum(
                        'discount'
                    );


                /*
                |--------------------------------------------------------------------------
                | Net Sales
                |--------------------------------------------------------------------------
                */

                $netSales =
                    $revenue
                    - $discount
                    - $returnSubtotal
                    + $returnDiscount;


                /*
                |--------------------------------------------------------------------------
                | COGS
                |--------------------------------------------------------------------------
                */

                $cogs =
                    $this->calculateCOGSForSaleIds(
                        $monthSales->pluck('id'),
                        $monthReturns->pluck('id')
                    );


                /*
                |--------------------------------------------------------------------------
                | Gross Profit
                |--------------------------------------------------------------------------
                */

                $grossProfit =
                    $netSales - $cogs;


                /*
                |--------------------------------------------------------------------------
                | Operating Expenses
                |--------------------------------------------------------------------------
                */

                $operatingExpenses =
                    (float) $monthExpenses->sum(
                        'amount'
                    );


                /*
                |--------------------------------------------------------------------------
                | Net Profit
                |--------------------------------------------------------------------------
                */

                $netProfit =
                    $grossProfit
                    - $operatingExpenses;


                /*
                |--------------------------------------------------------------------------
                | Gross Margin
                |--------------------------------------------------------------------------
                */

                $grossMargin =
                    $netSales > 0
                    ? ($grossProfit / $netSales) * 100
                    : 0;


                /*
                |--------------------------------------------------------------------------
                | Net Margin
                |--------------------------------------------------------------------------
                */

                $netMargin =
                    $netSales > 0
                    ? ($netProfit / $netSales) * 100
                    : 0;


                return [
                    'month' => $month,

                    'sales' => $revenue,

                    'returns' => $returnSubtotal,

                    'discount' => $discount,

                    'net_sales' => $netSales,

                    'cogs' => $cogs,

                    'gross_profit' => $grossProfit,

                    'operating_expenses' => $operatingExpenses,

                    'net_profit' => $netProfit,

                    'margin' => $grossMargin,

                    'gross_margin' => $grossMargin,

                    'net_margin' => $netMargin,
                ];
            })
            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | COGS
    |--------------------------------------------------------------------------
    */

    protected function calculateCOGS(array $filters = []): float
    {

        $saleIds =
            $this->salesQuery($filters)
            ->pluck('id');


        $returnIds =
            $this->saleReturnsQuery($filters)
            ->pluck('id');


        return $this->calculateCOGSForSaleIds(
            $saleIds,
            $returnIds
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COGS By Sale IDs
    |--------------------------------------------------------------------------
    */

    protected function calculateCOGSForSaleIds(Collection $saleIds, Collection $returnIds): float
    {

        $cogs = 0;


        /*
        |--------------------------------------------------------------------------
        | Sales COGS
        |--------------------------------------------------------------------------
        */

        if ($saleIds->isNotEmpty()) {

            $salesCogs =
                SaleItem::query()
                ->whereIn(
                    'sale_id',
                    $saleIds
                )
                ->selectRaw(
                    'COALESCE(
                            SUM(
                                (
                                    quantity
                                    + COALESCE(free_quantity, 0)
                                )
                                * purchase_price
                            ),
                            0
                        ) as total'
                )
                ->value('total');

            $cogs += (float) ($salesCogs ?? 0);
        }


        /*
        |--------------------------------------------------------------------------
        | Returned Goods
        |--------------------------------------------------------------------------
        |
        | Returned goods come back into inventory,
        | therefore their COGS is reversed.
        |
        */

        if ($returnIds->isNotEmpty()) {

            $returnedCogs =
                SaleReturnItem::query()
                ->whereIn(
                    'sale_return_id',
                    $returnIds
                )
                ->selectRaw(
                    'COALESCE(
                            SUM(
                                (
                                    quantity
                                    + COALESCE(free_quantity, 0)
                                )
                                * purchase_price
                            ),
                            0
                        ) as total'
                )
                ->value('total');

            $cogs -= (float) ($returnedCogs ?? 0);
        }


        return max(
            0,
            (float) $cogs
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sales Query
    |--------------------------------------------------------------------------
    */

    protected function salesQuery(array $filters = [])
    {

        return Sale::query()
            ->where(
                'status',
                'completed'
            )
            ->whereNull(
                'deleted_at'
            )

            ->when(
                $filters['date_from'] ?? null,
                fn($q, $date) =>
                $q->whereDate(
                    'sale_date',
                    '>=',
                    $date
                )
            )

            ->when(
                $filters['date_to'] ?? null,
                fn($q, $date) =>
                $q->whereDate(
                    'sale_date',
                    '<=',
                    $date
                )
            )

            ->when(
                $filters['customer_id'] ?? null,
                fn($q, $customerId) =>
                $q->where(
                    'customer_id',
                    $customerId
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Sale Returns Query
    |--------------------------------------------------------------------------
    */

    protected function saleReturnsQuery(array $filters = [])
    {

        return SaleReturn::query()
            ->where(
                'status',
                'completed'
            )
            ->whereNull(
                'deleted_at'
            )

            ->when(
                $filters['date_from'] ?? null,
                fn($q, $date) =>
                $q->whereDate(
                    'return_date',
                    '>=',
                    $date
                )
            )

            ->when(
                $filters['date_to'] ?? null,
                fn($q, $date) =>
                $q->whereDate(
                    'return_date',
                    '<=',
                    $date
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Operating Expenses Query
    |--------------------------------------------------------------------------
    |
    | Only Completed expenses affect P&L.
    |
    | Draft:
    |   ignored
    |
    | Cancelled:
    |   ignored
    |
    | Completed:
    |   included
    |
    */

    protected function expensesQuery(array $filters = [])
    {

        return Expense::query()
            ->where(
                'status',
                'Completed'
            )
            ->whereNull(
                'deleted_at'
            )

            ->when(
                $filters['date_from'] ?? null,
                fn($q, $date) =>
                $q->whereDate(
                    'expense_date',
                    '>=',
                    $date
                )
            )

            ->when(
                $filters['date_to'] ?? null,
                fn($q, $date) =>
                $q->whereDate(
                    'expense_date',
                    '<=',
                    $date
                )
            );
    }
}
