<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleReturnRequest;
use App\Http\Requests\UpdateSaleReturnRequest;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Services\SaleReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SaleReturnController extends Controller
{
    protected SaleReturnService $saleReturnService;

    public function __construct(SaleReturnService $saleReturnService)
    {
        $this->saleReturnService = $saleReturnService;
    }

    /**
     * Display a listing of sale returns.
     */
    public function index()
    {
        $this->authorize('viewAny', SaleReturn::class);

        $totalReturns = SaleReturn::count();
        $completedReturns = SaleReturn::where('status', 'completed')->count();
        $draftReturns = SaleReturn::where('status', 'draft')->count();
        $cancelledReturns = SaleReturn::where('status', 'cancelled')->count();
        $totalAmount = SaleReturn::sum('grand_total');

        return view('sale_returns.index', compact(
            'totalReturns',
            'completedReturns',
            'draftReturns',
            'cancelledReturns',
            'totalAmount'
        ));
    }

    /**
     * Return sale returns for DataTables.
     */
    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = SaleReturn::with([
            'sale',
            'customer',
            'creator',
        ])->select('sale_returns.*');

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()

            ->addColumn('return_number', function (SaleReturn $row) {
                return '<strong>' . e($row->return_number) . '</strong>';
            })

            ->addColumn('invoice_number', function (SaleReturn $row) {
                return $row->sale?->invoice_number ?? '-';
            })

            ->addColumn('customer', function (SaleReturn $row) {
                return $row->customer?->name ?? 'Walk-in Customer';
            })

            ->editColumn('return_date', function (SaleReturn $row) {
                return $row->return_date?->format('d M Y') ?? '-';
            })

            ->addColumn('total', function (SaleReturn $row) {
                return number_format((float) $row->grand_total, 2);
            })

            ->addColumn('status', function (SaleReturn $row) {
                return $row->status;
            })

            ->addColumn('created_by', function (SaleReturn $row) {
                if ($row->trashed()) {
                    return $row->deleter?->name ?? 'System';
                }

                return $row->creator?->name ?? '-';
            })

            ->addColumn('action', function (SaleReturn $row) {
                return view('components.action-dropdown', [
                    'row' => $row,
                    'module' => 'sale-returns',
                    'show' => true,
                    'edit' => false,
                    'complete' => !$row->trashed() && $row->status === 'draft',
                    'cancel' => !$row->trashed() && $row->status === 'draft',
                    'delete' => !$row->trashed(),
                    'restore' => request('filter') === 'trashed',
                    'forceDelete' => request('filter') === 'trashed',
                ]);
            })

            ->rawColumns([
                'return_number',
                'status',
                'action',
            ])

            ->make(true);
    }

    /**
     * Show the form for creating a new sale return.
     */
    // public function create()
    // {
    //     $this->authorize('create', SaleReturn::class);

    //     $sales = $this->saleReturnService->getReturnableSales();

    //     return view('sale_returns.create', compact('sales'));
    // }
    /**
     * Show the form for creating a new sale return.
     */
    public function create(Request $request)
    {
        $this->authorize('create', SaleReturn::class);

        // ✅ Get sale_id from query parameter (shortcut mode)
        $saleId = $request->query('sale');
        $selectedSale = null;
        $lockedSale = false;

        if ($saleId) {
            $selectedSale = Sale::with(['items.medicine', 'customer'])
                ->where('status', 'completed')
                ->findOrFail($saleId);
            
            // Check if sale has any returnable items
            $hasReturnableItems = $selectedSale->items->contains(function ($item) {
                return $item->quantity > ($item->returned_quantity ?? 0);
            });

            if (!$hasReturnableItems) {
                return redirect()
                    ->route('sale-returns.index')
                    ->with('error', 'This sale has no items available for return.');
            }

            $lockedSale = true;
        }

        // Get all completed sales for dropdown (only in normal mode)
        $sales = $this->saleReturnService->getReturnableSales();

        // For locked mode, pre-select the specific sale
        if ($lockedSale && $selectedSale) {
            $sales = collect([$selectedSale]);
        }

        // Get sale items for the selected sale (for shortcut mode)
        $saleItems = $selectedSale ? $selectedSale->items->map(function ($item) {
            $item->returned_quantity = $item->returned_quantity ?? 0;
            return $item;
        }) : collect();

        return view('sale_returns.create', compact(
            'sales', 
            'selectedSale', 
            'lockedSale', 
            'saleItems'
        ));
    }

    /**
     * Store a newly created sale return.
     */
    public function store(StoreSaleReturnRequest $request)
    {

        $this->authorize('create', SaleReturn::class);

        try {
            $saleReturn = $this->saleReturnService->store($request->validated());

            return redirect()->route('sale-returns.show', $saleReturn)
                ->with('success', 'Sale return created successfully.');

        } catch (ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()
                ->with(
                    'error',
                    'Unable to create the sale return. Please try again.'
                );
        }
    }

    /**
     * Display the specified sale return.
     */
    public function show(SaleReturn $saleReturn)
    {
        $this->authorize('view', $saleReturn);

        $saleReturn = $this->saleReturnService->getSaleReturn(
            $saleReturn
        );

        return view(
            'sale_returns.show',
            compact('saleReturn')
        );
    }

    /**
     * Show the form for editing the specified sale return.
     */
    // public function edit(SaleReturn $saleReturn)
    // {
    //     $this->authorize('update', $saleReturn);

    //     $saleReturn = $this->saleReturnService->getSaleReturn(
    //         $saleReturn
    //     );

    //     /*
    //     * The reusable form expects a $sales collection.
    //     *
    //     * For editing, we only need the original sale
    //     * associated with this sale return.
    //     */
    //     $sales = collect([
    //         $saleReturn->sale,
    //     ]);

    //     $saleItems = collect([
    //         $saleReturn->sale->items,
    //     ]);

    //     return view(
    //         'sale_returns.edit',
    //         compact('saleReturn', 'sales', 'saleItems')
    //     );
    // }
    /**
     * Show the form for editing the specified sale return.
     */
    // public function edit(SaleReturn $saleReturn)
    // {
    //     $this->authorize('update', $saleReturn);

    //     $saleReturn = $this->saleReturnService->getSaleReturn($saleReturn);

    //     // Get the sale with its items properly
    //     $sale = $saleReturn->sale->load(['items.medicine', 'customer']);
        
    //     // Get sale items directly (not nested)
    //     $saleItems = $sale->items;

    //     // Get sales collection for dropdown (only the current sale)
    //     $sales = collect([$sale]);

    //     return view(
    //         'sale_returns.edit',
    //         compact('saleReturn', 'sales', 'saleItems', 'sale')
    //     );
    // }
    /**
     * Show the form for editing the specified sale return.
     */
    public function edit(SaleReturn $saleReturn)
    {
        $this->authorize('update', $saleReturn);

        $saleReturn = $this->saleReturnService->getSaleReturn($saleReturn);

        $sale = $saleReturn->sale;

        $saleItems = $this->saleReturnService->getSaleItemsForReturn($sale, $saleReturn);

        /*
        * Only the original sale is required in edit mode.
        */
        $sales = collect([$sale]);

        return view(
            'sale_returns.edit',
            compact(
                'saleReturn',
                'sales',
                'sale',
                'saleItems'
            )
        );
    }

    /**
     * Show the form for editing the specified sale return.
     */
    // public function edit(SaleReturn $saleReturn)
    // {
    //     $this->authorize('update', $saleReturn);

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Load complete sale return
    //     |--------------------------------------------------------------------------
    //     */

    //     $saleReturn = $this->saleReturnService->getSaleReturn(
    //         $saleReturn
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Load the original sale
    //     |--------------------------------------------------------------------------
    //     */

    //     $sale = $saleReturn->sale;


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Prepare sale items for editing
    //     |--------------------------------------------------------------------------
    //     */

    //     $items = $this->saleReturnService->prepareReturnItems(
    //         $sale,
    //         $saleReturn
    //     );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Only the original sale is relevant in edit mode.
    //     |--------------------------------------------------------------------------
    //     */

    //     $sales = collect([
    //         $sale
    //     ]);


    //     return view(
    //         'sale_returns.edit',
    //         compact(
    //             'saleReturn',
    //             'sales',
    //             'items'
    //         )
    //     );
    // }
    /**
     * Update the specified sale return.
     */
    /**
     * Update the specified sale return.
     */
    public function update(UpdateSaleReturnRequest $request, SaleReturn $saleReturn)
    {
        $this->authorize('update', $saleReturn);

        try {

            $saleReturn = $this->saleReturnService->update(
                $saleReturn,
                $request->validated()
            );

            return redirect()
                ->route('sale-returns.show', $saleReturn)
                ->with('success', 'Sale return updated successfully.');

        } catch (ValidationException $e) {

            return back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update the sale return. Please try again.'
                );
        }
    }


    /**
     * Complete a draft sale return.
     */
    public function complete(SaleReturn $saleReturn): RedirectResponse {

        $this->authorize(
            'complete',
            $saleReturn
        );

        try {

            $saleReturn = $this->saleReturnService
                ->complete($saleReturn);

            return redirect()
                ->route(
                    'sale-returns.show',
                    $saleReturn
                )
                ->with(
                    'success',
                    'Sale return completed successfully.'
                );

        } catch (ValidationException $e) {

            return back()
                ->withInput()
                ->withErrors(
                    $e->errors()
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to complete the sale return. Please try again.'
                );
        }
    }


    /**
     * Cancel a sale return.
     */
    public function cancel(SaleReturn $saleReturn): RedirectResponse {

        $this->authorize(
            'cancel',
            $saleReturn
        );

        try {

            $saleReturn = $this->saleReturnService
                ->cancel($saleReturn);

            return redirect()
                ->route(
                    'sale-returns.show',
                    $saleReturn
                )
                ->with(
                    'success',
                    'Sale return cancelled successfully.'
                );

        } catch (ValidationException $e) {

            return back()
                ->withInput()
                ->withErrors(
                    $e->errors()
                );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to cancel the sale return. Please try again.'
                );
        }
    }

    /**
     * Delete the specified sale return.
     */
    public function destroy(SaleReturn $saleReturn)
    {
        $this->authorize('delete', $saleReturn);

        try {

            $this->saleReturnService->destroy($saleReturn);

            return redirect()
                ->route('sale-returns.index')
                ->with(
                    'success',
                    'Sale return deleted successfully.'
                );

        } catch (ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete the sale return.'
                );
        }
    }

    /**
     * Restore a deleted sale return.
     */
    // public function restore(SaleReturn $saleReturn)
    // {
    //     $this->authorize('restore', $saleReturn);

    //     try {

    //         $saleReturn = $this->saleReturnService->restore($saleReturn);

    //         return redirect()->route('sale-returns.index')
    //                ->with('success', 'Sale return restored successfully.');

    //     } catch (ValidationException $e) {

    //         throw $e;

    //     } catch (\Throwable $e) {
    //         report($e);
    //         return back()->with(
    //                 'error',
    //                 'Unable to restore the sale return.'
    //             );
    //     }
    // }
    /**
     * Restore a deleted sale return.
     */
    public function restore(SaleReturn $saleReturn)
    {
        $this->authorize('restore', $saleReturn);

        try {

            $saleReturn = $this->saleReturnService->restore($saleReturn);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sale return restored successfully.',
                    'data' => [
                        'id' => $saleReturn->id,
                        'return_number' => $saleReturn->return_number,
                        'trashed' => $saleReturn->trashed(),
                    ],
                ]);
            }

            return redirect()
                ->route('sale-returns.index')
                ->with(
                    'success',
                    'Sale return restored successfully.'
                );

        } catch (ValidationException $e) {

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;

        } catch (\Throwable $e) {

            report($e);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to restore the sale return.' . $e->getMessage(),
                ], 500);
            }

            return back()->with(
                'error',
                'Unable to restore the sale return.' . $e->getMessage()
            );
        }
    }

    /**
     * Permanently delete the specified sale return.
     */
    public function forceDelete(SaleReturn $saleReturn)
    {
        $this->authorize('forceDelete', $saleReturn);

        try {

            $this->saleReturnService->forceDelete(
                $saleReturn
            );

            return redirect()
                ->route('sale-returns.index')
                ->with(
                    'success',
                    'Sale return permanently deleted.'
                );

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to permanently delete the sale return.'
                );
        }
    }

    /**
     * Get sale items for creating a sale return.
     */
    public function saleItems(Sale $sale)
    {
        $this->authorize('create', SaleReturn::class);

        $items = $this->saleReturnService->getSaleItemsForReturn($sale);

        return view(
            'sale_returns.partials.return-items',
            [
                'items' => $items,
                'saleReturn' => null,
            ]
        );
    }
}