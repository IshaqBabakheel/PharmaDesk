<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseReportController extends Controller
{
    public function __construct(
        protected PurchaseReportService $purchaseReportService
    ) {}

    public function index()
    {
        abort_unless(
            auth()->user()->can('purchase-reports.view'),
            403
        );

        $suppliers = Supplier::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'purchase_reports.index',
            compact('suppliers')
        );
    }

    public function datatable(Request $request): JsonResponse
    {
        abort_unless(
            auth()->user()->can('purchase-reports.view'),
            403
        );

        $purchases = $this->purchaseReportService->getPurchases(
            $request->only([
                'date_from',
                'date_to',
                'supplier_id',
                'payment_status',
                'status',
            ])
        );

        $data = $purchases->map(function (Purchase $purchase, $index) {
            return [
                'DT_RowIndex' => $index + 1,
                'id' => $purchase->id,
                'purchase_number' => $purchase->purchase_number,
                'purchase_date' => $purchase->purchase_date?->format('d M Y'),
                'supplier' => $purchase->supplier?->name ?? '-',
                'grand_total' => number_format(
                    (float) $purchase->grand_total,
                    2
                ),
                'paid_amount' => number_format(
                    (float) $purchase->paid_amount,
                    2
                ),
                'due_amount' => number_format(
                    (float) $purchase->due_amount,
                    2
                ),
                'payment_status' => $purchase->payment_status,
                'status' => $purchase->status,
            ];
        })->values();

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        abort_unless(
            auth()->user()->can('purchase-reports.view'),
            403
        );

        return response()->json([
            'success' => true,
            'statistics' => $this->purchaseReportService->getStatistics(
                $request->only([
                    'date_from',
                    'date_to',
                    'supplier_id',
                    'payment_status',
                    'status',
                ])
            ),
        ]);
    }

    public function show(int $purchaseId)
    {
        abort_unless(
            auth()->user()->can('purchase-reports.view'),
            403
        );

        $purchase = Purchase::findOrFail($purchaseId);

        $items = $this->purchaseReportService
            ->getPurchaseItems($purchase);

        return view(
            'purchase_reports.show',
            compact(
                'purchase',
                'items'
            )
        );
    }
}