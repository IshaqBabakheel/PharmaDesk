<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\ProfitLossService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public function __construct(
        protected ProfitLossService $profitLossService
    ) {}


    /*
    |--------------------------------------------------------------------------
    | Profit & Loss Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        abort_unless(
            auth()->user()->can('profit-loss.view'),
            403
        );

        $customers = Customer::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'profit_loss.index',
            compact('customers')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Summary API
    |--------------------------------------------------------------------------
    */

    public function summary(Request $request): JsonResponse
    {

        abort_unless(
            auth()->user()->can('profit-loss.view'),
            403
        );

        return response()->json([
            'success' => true,

            'summary' =>
            $this->profitLossService
                ->getSummary(
                    $request->only([
                        'date_from',
                        'date_to',
                        'customer_id',
                    ])
                ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Monthly API
    |--------------------------------------------------------------------------
    */

    public function monthly(Request $request): JsonResponse
    {

        abort_unless(
            auth()->user()->can('profit-loss.view'),
            403
        );

        return response()->json([
            'success' => true,

            'data' =>
            $this->profitLossService
                ->getMonthlySummary(
                    $request->only([
                        'date_from',
                        'date_to',
                        'customer_id',
                    ])
                ),
        ]);
    }
}
