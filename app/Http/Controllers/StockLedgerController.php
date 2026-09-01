<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\PurchaseItem;
use App\Services\StockLedgerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// class StockLedgerController extends Controller
// {
//     public function __construct(
//         protected StockLedgerService $stockLedgerService
//     ) {}

//     public function index()
//     {
//         abort_unless(
//             auth()->user()->can('stock-ledger.view'),
//             403
//         );

//         $medicines = Medicine::query()
//             ->where('status', true)
//             ->orderBy('name')
//             ->get(['id', 'name']);

//         return view(
//             'stock_ledger.index',
//             compact('medicines')
//         );
//     }

//     public function datatable(Request $request): JsonResponse
//     {
//         abort_unless(
//             auth()->user()->can('stock-ledger.view'),
//             403
//         );

//         $ledger = $this->stockLedgerService->getLedger(
//             $request->only([
//                 'medicine_id',
//                 'batch_number',
//                 'date_from',
//                 'date_to',
//             ])
//         );

//         $data = $ledger->map(function ($row, $index) {
//             return [
//                 'DT_RowIndex' => $index + 1,
//                 'date' => Carbon::parse($row['date'])->format('d M Y'),
//                 'medicine' => $row['medicine'],
//                 'purchase_item_id' => $row['purchase_item_id'],
//                 'batch_number' => $row['batch_number'],
//                 'transaction' => $row['transaction'],
//                 'reference' => $row['reference'],
//                 'in' => number_format($row['in'], 0),
//                 'out' => number_format($row['out'], 0),
//                 'balance' => number_format($row['balance'], 0),
//                 'type' => $row['type'],
//             ];
//         })->values();

//         return response()->json([
//             'draw' => (int) $request->input('draw', 1),
//             'recordsTotal' => $data->count(),
//             'recordsFiltered' => $data->count(),
//             'data' => $data,
//         ]);
//     }

//     public function batch(PurchaseItem $purchaseItem)
//     {
//         abort_unless(
//             auth()->user()->can('stock-ledger.view'),
//             403
//         );

//         $ledger = $this->stockLedgerService->getBatchLedger(
//             $purchaseItem
//         );

//         $purchaseItem->load('medicine');

//         return view('stock_ledger.batch', compact(
//             'purchaseItem',
//             'ledger'
//         ));
//     }
// }


class StockLedgerController extends Controller
{
    public function __construct(
        protected StockLedgerService $stockLedgerService
    ) {}

    public function index()
    {
        abort_unless(
            auth()->user()->can('stock-ledger.view'),
            403
        );

        $medicines = Medicine::query()
            ->where('status', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'stock_ledger.index',
            compact('medicines')
        );
    }

    public function datatable(
        Request $request
    ): JsonResponse {
        abort_unless(
            auth()->user()->can('stock-ledger.view'),
            403
        );

        $data = $this->stockLedgerService
            ->getLedger(
                $request->only([
                    'medicine_id',
                    'batch_number',
                    'date_from',
                    'date_to',
                    'transaction',
                ])
            )
            ->map(
                function ($row, $index) {
                    return [
                        'DT_RowIndex' => $index + 1,
                        'date' =>
                            $row['date']
                                ? $row['date']
                                    ->format('d M Y')
                                : '-',
                        'medicine' =>
                            $row['medicine'],
                        'medicine_id' =>
                            $row['medicine_id'],
                        'batch_number' =>
                            $row['batch_number'],
                        'transaction' =>
                            $row['transaction'],
                        'reference' =>
                            $row['reference'] ?? '-',
                        'in' =>
                            number_format(
                                $row['in'],
                                0
                            ),
                        'out' =>
                            number_format(
                                $row['out'],
                                0
                            ),
                        'balance' =>
                            number_format(
                                $row['balance'],
                                0
                            ),
                    ];
                }
            )
            ->values();

        return response()->json([
            'draw' =>
                (int) $request->input(
                    'draw',
                    1
                ),

            'recordsTotal' =>
                $data->count(),

            'recordsFiltered' =>
                $data->count(),

            'data' =>
                $data,
        ]);
    }

    public function medicine(
        int $medicineId
    ) {
        abort_unless(
            auth()->user()->can('stock-ledger.view'),
            403
        );

        $medicine = Medicine::findOrFail(
            $medicineId
        );

        return view(
            'stock_ledger.medicine',
            compact('medicine')
        );
    }

    public function batch(
        Request $request,
        int $medicineId,
        string $batchNumber
    ) {
        abort_unless(
            auth()->user()->can('stock-ledger.view'),
            403
        );

        $medicine = Medicine::findOrFail(
            $medicineId
        );

        $ledger =
            $this->stockLedgerService
                ->getLedger([
                    'medicine_id' => $medicineId,
                    'batch_number' => $batchNumber,
                    'date_from' =>
                        $request->date_from,
                    'date_to' =>
                        $request->date_to,
                ]);

        return view(
            'stock_ledger.batch',
            compact(
                'medicine',
                'batchNumber',
                'ledger'
            )
        );
    }
}