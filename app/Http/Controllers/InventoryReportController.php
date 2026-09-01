<?php

namespace App\Http\Controllers;

use App\Models\MedicineCategory;
use App\Models\Medicine;
use App\Services\InventoryReportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryReportController extends Controller
{
    public function __construct(
        protected InventoryReportService $inventoryService
    ) {}

    public function index()
    {
        abort_unless(
            auth()->user()->can('inventory-reports.view'),
            403
        );

        $medicines = Medicine::query()
            ->where('status', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        $categories = MedicineCategory::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'inventory_reports.index',
            compact(
                'medicines',
                'categories'
            )
        );
    }

    public function medicine(int $medicineId)
    {
        abort_unless(
            auth()->user()->can('inventory-reports.view'),
            403
        );

        $medicine = Medicine::query()
            ->with([
                'category:id,name',
                'type:id,name',
                'unit:id,name',
                'manufacturer:id,name',
            ])
            ->findOrFail($medicineId);

        $batches = $this->inventoryService->getMedicineBatches($medicine->id);

        $summary = [
            'stock' => $batches->sum('available_quantity'),
            'stock_cost' => $batches->sum('stock_cost'),
            'retail_value' => $batches->sum('retail_value'),
            'potential_profit' => $batches->sum('potential_profit'),
            'batches' => $batches->count(),
            'expired_batches' => $batches
                ->where('expiry_status', 'expired')
                ->count(),
            'near_expiry_batches' => $batches
                ->whereIn('expiry_status', [
                    'critical',
                    'warning',
                    'upcoming',
                ])
                ->count(),
        ];

        return view(
            'inventory_reports.medicine',
            compact(
                'medicine',
                'batches',
                'summary'
            )
        );
    }

    public function datatable(Request $request): JsonResponse
    {
        abort_unless(
            auth()->user()->can('inventory-reports.view'),
            403
        );

        $items = $this->inventoryService->getInventory(
            $request->only([
                'medicine_id',
                'medicine_category_id',
                'stock_status',
            ])
        );

        $data = $items->map(
            function ($item, $index) {
                return [
                    'DT_RowIndex' => $index + 1,
                    'medicine_id' => $item['medicine_id'],
                    'medicine' => $item['medicine'],
                    'category' => $item['medicine_category'],
                    'batch_count' => $item['batch_count'],
                    'batch_stock' => number_format($item['batch_stock'], 0),
                    'purchase_price' => number_format($item['purchase_price'], 2),
                    'selling_price' => number_format($item['selling_price'], 2),
                    'stock_cost' => number_format($item['stock_cost'], 2),
                    'retail_value' => number_format($item['retail_value'], 2),
                    'potential_profit' => number_format($item['potential_profit'], 2),
                    'nearest_expiry' => $item['nearest_expiry'] ?: '-',
                    'stock_status' => $item['stock_status'],
                ];
            }
        )->values();

        return response()->json([
            'draw' => (int) $request->input(
                'draw',
                1
            ),
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        abort_unless(
            auth()->user()->can('inventory-reports.view'),
            403
        );

        return response()->json([
            'success' => true,
            'statistics' => $this->inventoryService->getStatistics(
                $request->only([
                    'medicine_id',
                    'medicine_category_id',
                    'stock_status',
                ])
            ),
        ]);
    }
}
