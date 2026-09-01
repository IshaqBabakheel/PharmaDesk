<?php

namespace App\Http\Controllers;

use App\Services\FinancialReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function __construct(
        protected FinancialReportService $financialReportService
    ) {}

    public function index()
    {
        abort_unless(
            auth()->user()->can('financial-reports.view'),
            403
        );

        return view('financial_reports.index');
    }

    public function summary(
        Request $request
    ): JsonResponse {
        abort_unless(
            auth()->user()->can('financial-reports.view'),
            403
        );

        return response()->json([
            'success' => true,
            'summary' =>
                $this->financialReportService->getSummary(
                    $request->only([
                        'date_from',
                        'date_to',
                    ])
                ),
        ]);
    }

    public function datatable(
        Request $request
    ): JsonResponse {
        abort_unless(
            auth()->user()->can('financial-reports.view'),
            403
        );

        $transactions =
            $this->financialReportService->getTransactions(
                $request->only([
                    'date_from',
                    'date_to',
                ])
            );

        $data = $transactions
            ->map(function ($transaction, $index) {
                return [
                    'DT_RowIndex' => $index + 1,
                    'date' =>
                        \Carbon\Carbon::parse(
                            $transaction['date']
                        )->format('d M Y'),

                    'type' =>
                        $transaction['type'],

                    'reference' =>
                        $transaction['reference'],

                    'description' =>
                        $transaction['description'],

                    'in' =>
                        number_format(
                            $transaction['in'],
                            2
                        ),

                    'out' =>
                        number_format(
                            $transaction['out'],
                            2
                        ),
                ];
            })
            ->values();

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
}