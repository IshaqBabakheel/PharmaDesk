<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Services\SalesReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function __construct(
        protected SalesReportService $salesReportService
    ) {}

    public function index()
    {
        abort_unless(
            auth()->user()->can('sales-reports.view'),
            403
        );

        $customers = Customer::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'sales_reports.index',
            compact('customers')
        );
    }

    public function datatable(
        Request $request
    ): JsonResponse {
        abort_unless(
            auth()->user()->can('sales-reports.view'),
            403
        );

        $sales = $this->salesReportService->getSales(
            $request->only([
                'date_from',
                'date_to',
                'customer_id',
                'payment_status',
                'status',
            ])
        );

        $data = $sales->map(function (Sale $sale, $index) {
            return [
                'DT_RowIndex' => $index + 1,
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'sale_date' => $sale->sale_date?->format('d M Y'),
                'customer' =>
                $sale->customer?->name
                    ?? 'Walk-in Customer',
                'grand_total' =>
                number_format(
                    (float) $sale->grand_total,
                    2
                ),
                'paid_amount' =>
                number_format(
                    (float) $sale->paid_amount,
                    2
                ),
                'due_amount' =>
                number_format(
                    (float) $sale->due_amount,
                    2
                ),
                'payment_status' =>
                $sale->payment_status,
                'status' =>
                $sale->status,
            ];
        })->values();

        return response()->json([
            'draw' =>
            (int) $request->input('draw', 1),
            'recordsTotal' =>
            $data->count(),
            'recordsFiltered' =>
            $data->count(),
            'data' =>
            $data,
        ]);
    }

    public function statistics(
        Request $request
    ): JsonResponse {
        abort_unless(
            auth()->user()->can('sales-reports.view'),
            403
        );

        return response()->json([
            'success' => true,
            'statistics' =>
            $this->salesReportService->getStatistics(
                $request->only([
                    'date_from',
                    'date_to',
                    'customer_id',
                    'payment_status',
                    'status',
                ])
            ),
        ]);
    }

    public function show(int $saleId)
    {
        abort_unless(
            auth()->user()->can('sales-reports.view'),
            403
        );

        $sale = Sale::findOrFail($saleId);

        $items = $this->salesReportService
            ->getSaleItems($sale);

        return view(
            'sales_reports.show',
            compact(
                'sale',
                'items'
            )
        );
    }
}
