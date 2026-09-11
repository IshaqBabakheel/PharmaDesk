<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturnItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Build all dashboard data in one place.
     */
    public function getDashboardData(string $period = 'today'): array
    {
        [$from, $to, $periodLabel] = $this->periodRange($period);

        $sales = $this->salesMetrics($from, $to);
        $purchases = $this->purchaseMetrics($from, $to);

        $inventory = $this->inventoryMetrics();
        $alerts = $this->alertMetrics();
        $profit = $this->profitMetrics($from, $to);

        return [
            'period' => $period,
            'periodLabel' => $periodLabel,
            'today' => Carbon::today(),

            'sales' => $sales,
            'purchases' => $purchases,
            'inventory' => $inventory,
            'alerts' => $alerts,
            'profit' => $profit,

            'salesChart' => $this->salesChart($period),

            'recentSales' => $this->recentSales(8),
            'topMedicines' => $this->topMedicines($from, $to, 5),

            'customerDue' => $this->customerDueMetrics(),
            'supplierDue' => $this->supplierDueMetrics(),
        ];
    }

    protected function periodRange(string $period): array
    {
        return match ($period) {
            'week' => [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
                'This Week',
            ],
            'month' => [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
                'This Month',
            ],
            'year' => [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
                'This Year',
            ],
            default => [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay(),
                'Today',
            ],
        };
    }

    protected function salesMetrics(Carbon $from, Carbon $to): array
    {
        $query = Sale::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->whereBetween('sale_date', [$from, $to]);

        $gross = (float) $query->sum('grand_total');
        $paid = (float) $query->sum('paid_amount');
        $due = (float) $query->sum('due_amount');
        $count = (int) $query->count();

        $returns = (float) SaleReturnItem::query()
            ->whereHas('saleReturn', function ($q) use ($from, $to) {
                $q->where('status', 'completed')
                    ->whereNull('deleted_at')
                    ->whereBetween('return_date', [$from, $to]);
            })
            ->sum('total');

        $net = max($gross - $returns, 0);

        return [
            'count' => $count,
            'gross' => $gross,
            'net' => $net,
            'paid' => $paid,
            'due' => $due,
            'returns' => $returns,
        ];
    }

    protected function purchaseMetrics(Carbon $from, Carbon $to): array
    {
        $query = Purchase::query()
            ->whereIn('status', ['Completed', 'completed'])
            ->whereNull('deleted_at')
            ->whereBetween('purchase_date', [$from, $to]);

        return [
            'count' => (int) $query->count(),
            'total' => (float) $query->sum('grand_total'),
            'paid' => (float) $query->sum('paid_amount'),
            'due' => (float) $query->sum('due_amount'),
        ];
    }

    protected function inventoryMetrics(): array
    {
        $totalMedicines = (int) Medicine::query()->count();

        $lowStock = (int) Medicine::query()
            ->whereColumn('current_stock', '<=', 'reorder_level')
            ->where('status', true)
            ->count();

        $outOfStock = (int) Medicine::query()
            ->where('current_stock', '<=', 0)
            ->where('status', true)
            ->count();

        $inStock = max($totalMedicines - $outOfStock, 0);

        return [
            'total_medicines' => $totalMedicines,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'in_stock' => $inStock,
            'stock_units' => (float) Medicine::query()->sum('current_stock'),
        ];
    }

    protected function alertMetrics(): array
    {
        $today = Carbon::today();
        $expiryDays = (int) env('NOTIFICATION_EXPIRY_DAYS', 30);
        $expiryLimit = $today->copy()->addDays($expiryDays);

        $lowStockMedicines = Medicine::query()
            ->whereColumn('current_stock', '<=', 'reorder_level')
            ->where('status', true)
            ->orderBy('current_stock')
            ->limit(6)
            ->get([
                'id',
                'name',
                'medicine_code',
                'current_stock',
                'reorder_level',
            ]);

        $expiryBatches = $this->expiryBatches($today, $expiryLimit, false, 6);
        $expiredBatches = $this->expiryBatches($today, $today, true, 6);

        return [
            'low_stock_count' => Medicine::query()
                ->whereColumn('current_stock', '<=', 'reorder_level')
                ->where('status', true)
                ->count(),

            'expiry_count' => $this->expiryBatchQuery($today, $expiryLimit, false)->count(),

            'expired_count' => $this->expiryBatchQuery($today, $today, true)->count(),

            'low_stock' => $lowStockMedicines,
            'expiry_batches' => $expiryBatches,
            'expired_batches' => $expiredBatches,
        ];
    }

    /**
     * Purchase batches with positive remaining stock.
     *
     * Availability follows the existing PharmaDesk inventory architecture:
     * received + free quantity - purchase returns - sold quantity + sale returns.
     */
    protected function expiryBatchQuery(
        Carbon $from,
        Carbon $to,
        bool $expired = false
    ) {
        $query = PurchaseItem::query()
            ->with('medicine')
            ->whereNotNull('expiry_date')
            ->when($expired, function ($q) use ($from) {
                $q->whereDate('expiry_date', '<', $from->toDateString());
            }, function ($q) use ($from, $to) {
                $q->whereDate('expiry_date', '>=', $from->toDateString())
                    ->whereDate('expiry_date', '<=', $to->toDateString());
            })
            ->select('purchase_items.*')
            ->selectSub(
                PurchaseReturnItem::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn(
                        'purchase_return_items.purchase_item_id',
                        'purchase_items.id'
                    )
                    ->whereHas('purchaseReturn', function ($q) {
                        $q->where('status', 'Completed')
                            ->whereNull('deleted_at');
                    }),
                'returned_quantity'
            )
            ->selectSub(
                SaleItem::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn(
                        'sale_items.purchase_item_id',
                        'purchase_items.id'
                    )
                    ->whereHas('sale', function ($q) {
                        $q->where('status', 'completed')
                            ->whereNull('deleted_at');
                    }),
                'sold_quantity'
            )
            ->selectSub(
                SaleReturnItem::query()
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn(
                        'sale_return_items.purchase_item_id',
                        'purchase_items.id'
                    )
                    ->whereHas('saleReturn', function ($q) {
                        $q->where('status', 'completed')
                            ->whereNull('deleted_at');
                    }),
                'sale_returned_quantity'
            )
            ->orderBy('expiry_date')
            ->orderBy('id');

        return $query->get()->filter(function ($item) {
            $received = (float) $item->quantity + (float) $item->free_quantity;
            $returned = (float) $item->returned_quantity;
            $sold = (float) $item->sold_quantity;
            $saleReturned = (float) $item->sale_returned_quantity;

            $available = $received - $returned - $sold + $saleReturned;

            $item->dashboard_available_quantity = max($available, 0);

            return $item->dashboard_available_quantity > 0;
        })->values();
    }

    protected function expiryBatches(
        Carbon $from,
        Carbon $to,
        bool $expired,
        int $limit
    ): Collection {
        return $this->expiryBatchQuery($from, $to, $expired)
            ->take($limit)
            ->values();
    }

    protected function profitMetrics(Carbon $from, Carbon $to): array
    {
        $sales = Sale::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->whereBetween('sale_date', [$from, $to])
            ->with('items:id,sale_id,quantity,free_quantity,purchase_price,total');

        $revenue = 0.0;
        $cost = 0.0;

        foreach ($sales->get() as $sale) {
            foreach ($sale->items as $item) {
                $revenue += (float) $item->total;

                $units = (float) $item->quantity + (float) $item->free_quantity;
                $cost += $units * (float) $item->purchase_price;
            }
        }

        $grossProfit = $revenue - $cost;
        $expenses = $this->expenseTotal($from, $to);
        $netProfit = $grossProfit - $expenses;

        return [
            'revenue' => $revenue,
            'cost' => $cost,
            'gross_profit' => $grossProfit,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'margin' => $revenue > 0
                ? ($grossProfit / $revenue) * 100
                : 0,
        ];
    }

    /**
     * Expense table/columns can vary between deployments.
     *
     * The current PharmaDesk implementation uses the amount column
     * for P&L as established in the expense architecture.
     */
    protected function expenseTotal(Carbon $from, Carbon $to): float
    {
        if (!class_exists(\App\Models\Expense::class)) {
            return 0.0;
        }

        $query = \App\Models\Expense::query()
            ->whereBetween('expense_date', [$from, $to]);

        // Do not force an approval lifecycle that may not exist.
        if ($this->modelHasColumn(\App\Models\Expense::class, 'status')) {
            $query->where(function ($q) {
                $q->whereNull('status')
                    ->orWhereNotIn('status', ['cancelled', 'rejected']);
            });
        }

        return (float) $query->sum('amount');
    }

    protected function recentSales(int $limit = 8): Collection
    {
        return Sale::query()
            ->with('customer:id,name')
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->latest('sale_date')
            ->latest('id')
            ->limit($limit)
            ->get([
                'id',
                'invoice_number',
                'customer_id',
                'sale_date',
                'grand_total',
                'paid_amount',
                'due_amount',
                'payment_status',
            ]);
    }

    protected function topMedicines(
        Carbon $from,
        Carbon $to,
        int $limit = 5
    ): Collection {
        return SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('medicines', 'medicines.id', '=', 'sale_items.medicine_id')
            ->where('sales.status', 'completed')
            ->whereNull('sales.deleted_at')
            ->whereBetween('sales.sale_date', [$from, $to])
            ->select([
                'sale_items.medicine_id',
                'medicines.name',
                'medicines.medicine_code',
                DB::raw('SUM(sale_items.quantity) as quantity'),
                DB::raw('SUM(sale_items.total) as revenue'),
            ])
            ->groupBy(
                'sale_items.medicine_id',
                'medicines.name',
                'medicines.medicine_code'
            )
            ->orderByDesc('quantity')
            ->limit($limit)
            ->get();
    }

    protected function customerDueMetrics(): array
    {
        $query = Sale::query()
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->where('due_amount', '>', 0)
            ->whereNotNull('customer_id');

        return [
            'amount' => (float) $query->sum('due_amount'),
            'accounts' => (int) $query->distinct('customer_id')->count('customer_id'),
        ];
    }

    protected function supplierDueMetrics(): array
    {
        $query = Purchase::query()
            ->whereIn('status', ['Completed', 'completed'])
            ->whereNull('deleted_at')
            ->where('due_amount', '>', 0)
            ->whereNotNull('supplier_id');

        return [
            'amount' => (float) $query->sum('due_amount'),
            'accounts' => (int) $query->distinct('supplier_id')->count('supplier_id'),
        ];
    }

    protected function salesChart(string $period): array
    {
        return match ($period) {
            'today' => $this->salesChartToday(),

            'week' => $this->salesChartDays(7),

            'month' => $this->salesChartCurrentMonth(),

            'year' => $this->salesChartMonths(12),

            default => $this->salesChartToday(),
        };
    }

    protected function salesChartToday(): array
    {
        $start = Carbon::today()->startOfDay();
        $end = Carbon::today()->endOfDay();

        $sales = Sale::query()
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$start, $end])
            ->selectRaw('HOUR(sale_date) as hour, COALESCE(SUM(grand_total), 0) as total')
            ->groupByRaw('HOUR(sale_date)')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $chart = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $value = (float) ($sales[$hour]->total ?? 0);

            $chart[] = [
                'label' => Carbon::createFromTime($hour)->format('g A'),
                'full_label' => Carbon::createFromTime($hour)->format('h:00 A'),
                'value' => $value,
            ];
        }

        return $chart;
    }

    protected function salesChartDays(int $days): array
    {
        $start = Carbon::today()->subDays($days - 1)->startOfDay();
        $end = Carbon::today()->endOfDay();

        $sales = Sale::query()
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$start, $end])
            ->selectRaw('DATE(sale_date) as sale_day, COALESCE(SUM(grand_total), 0) as total')
            ->groupByRaw('DATE(sale_date)')
            ->orderBy('sale_day')
            ->get()
            ->keyBy('sale_day');

        $chart = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::today()->subDays($days - 1 - $i);
            $key = $date->toDateString();

            $value = (float) ($sales[$key]->total ?? 0);

            $chart[] = [
                'label' => $date->format('d M'),
                'full_label' => $date->format('l, d M Y'),
                'value' => $value,
            ];
        }

        return $chart;
    }

    protected function salesChartCurrentMonth(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $sales = Sale::query()
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$start, $end])
            ->selectRaw(
                'DATE(sale_date) as sale_day, COALESCE(SUM(grand_total), 0) as total'
            )
            ->groupByRaw('DATE(sale_date)')
            ->orderBy('sale_day')
            ->get()
            ->keyBy('sale_day');

        $chart = [];

        $daysInMonth = $start->daysInMonth;
        $today = Carbon::today();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create(
                $start->year,
                $start->month,
                $day
            );

            // Don't show future dates in the current month.
            if ($date->greaterThan($today)) {
                break;
            }

            $key = $date->toDateString();

            $value = (float) ($sales[$key]->total ?? 0);

            $chart[] = [
                'label' => $date->format('d'),
                'full_label' => $date->format('l, d M Y'),
                'value' => $value,
            ];
        }

        return $chart;
    }

    protected function salesChartMonths(int $months): array
    {
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear();

        $sales = Sale::query()
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$start, $end])
            ->selectRaw('YEAR(sale_date) as sale_year, MONTH(sale_date) as sale_month, COALESCE(SUM(grand_total), 0) as total')
            ->groupByRaw('YEAR(sale_date), MONTH(sale_date)')
            ->orderBy('sale_year')
            ->orderBy('sale_month')
            ->get();

        $sales = $sales->keyBy(function ($item) {
            return sprintf('%04d-%02d', $item->sale_year, $item->sale_month);
        });

        $chart = [];

        for ($month = 1; $month <= $months; $month++) {
            $date = Carbon::create(Carbon::now()->year, $month, 1);
            $key = $date->format('Y-m');

            $value = (float) ($sales[$key]->total ?? 0);

            $chart[] = [
                'label' => $date->format('M'),
                'full_label' => $date->format('F Y'),
                'value' => $value,
            ];
        }

        return $chart;
    }

    protected function modelHasColumn(string $modelClass, string $column): bool
    {
        $model = new $modelClass;

        return DB::getSchemaBuilder()->hasColumn(
            $model->getTable(),
            $column
        );
    }
}
