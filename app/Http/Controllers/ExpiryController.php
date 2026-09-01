<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Services\ExpiryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpiryController extends Controller
{
    public function __construct(protected ExpiryService $expiryService) {}

    /**
     * Display the expiry management page.
     */
    public function index()
    {
        abort_unless(auth()->user()->can('expiry.view'), 403);

        return view('expiry.index');
    }

    /**
     * Return expiry batches for DataTables.
     */
    public function datatable(Request $request): JsonResponse
    {
        abort_unless(auth()->user()->can('expiry.view'), 403);

        $filter = $request->input('filter', 'all');
        $items = $this->expiryService->getExpiryBatches($filter);
        $data = $items->map(
            function ($item, $index) {
                return [
                    'DT_RowIndex' => $index + 1,
                    'id' => $item->id,
                    'medicine' => $item->medicine?->name ?? '-',
                    'batch_number' => $item->batch_number ?? '-',
                    'expiry_date' => $item->expiry_date?->format('d M Y') ?? '-',
                    'available_quantity' => number_format((int) $item->available_quantity),
                    'days_remaining' => $item->days_remaining,
                    'expiry_status' => $item->expiry_status,
                ];
            }
        )->values();

        return response()->json([
            'draw' =>
            (int) $request->input(
                'draw',
                1
            ),
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }

    /**
     * Return expiry statistics.
     */
    public function statistics(): JsonResponse
    {
        abort_unless(auth()->user()->can('expiry.view'), 403);

        return response()->json([
            'success' => true,
            'statistics' => $this->expiryService->getStatistics(),
        ]);
    }

    /**
     * Return batches for one medicine.
     */
    public function medicine(Medicine $medicine): JsonResponse
    {
        abort_unless(auth()->user()->can('expiry.view'), 403);

        $items = $this->expiryService->getExpiryBatches('all')
            ->where('medicine_id', $medicine->id)
            ->values();


        return response()->json([
            'success' => true,

            'medicine' => [
                'id' => $medicine->id,
                'name' => $medicine->name,
            ],

            'batches' => $items->map(
                function ($item) {

                    return [
                        'id' => $item->id,
                        'batch_number' => $item->batch_number ?? '-',
                        'expiry_date' => $item->expiry_date?->format('Y-m-d'),
                        'available_quantity' => (int) ($item->available_quantity ?? 0),
                        'days_remaining' => $item->days_remaining,
                        'expiry_status' => $item->expiry_status,
                    ];
                }
            )->values(),
        ]);
    }
}
