<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Http\Requests\UpdateStockAdjustmentRequest;
use App\Models\Medicine;
use App\Models\StockAdjustment;
use App\Services\StockAdjustmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentController extends Controller
{
    public function __construct(
        protected StockAdjustmentService $stockAdjustmentService
    ) {}


    /**
     * Display stock adjustments.
     */
    public function index()
    {
        $this->authorize('viewAny', StockAdjustment::class);

        return view('stock_adjustments.index');
    }


    /**
     * Return stock adjustments for DataTables.
     */
    public function datatable(Request $request)
    {
        $this->authorize('viewAny', StockAdjustment::class);

        $filter = $request->get('filter', 'all');
        $query = StockAdjustment::with(['creator'])->select('stock_adjustments.*');

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()

            /*
            |--------------------------------------------------------------------------
            | Adjustment Number
            |--------------------------------------------------------------------------
            */
            ->addColumn('adjustment_number', function (StockAdjustment $row) {
                return '<strong>' .
                    e($row->adjustment_number) .
                    '</strong>';
            })


            /*
            |--------------------------------------------------------------------------
            | Type
            |--------------------------------------------------------------------------
            */
            ->addColumn('type', function (StockAdjustment $row) {
                if ($row->type === 'increase') {

                    return '<span class="badge bg-success">
                                    Increase
                                </span>';
                }

                return '<span class="badge bg-danger">
                                Decrease
                            </span>';
            })


            /*
            |--------------------------------------------------------------------------
            | Date
            |--------------------------------------------------------------------------
            */

            ->editColumn('adjustment_date', function (StockAdjustment $row) {
                return $row->adjustment_date?->format(
                    'd M Y'
                ) ?? '-';
            })


            /*
            |--------------------------------------------------------------------------
            | Reason
            |--------------------------------------------------------------------------
            */
            ->addColumn('reason', function (StockAdjustment $row) {
                return e($row->reason);
            })


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            ->addColumn('status', function (StockAdjustment $row) {
                return match ($row->status) {

                    'completed' =>
                    '<span class="badge bg-success">
                                Completed
                            </span>',

                    'cancelled' =>
                    '<span class="badge bg-danger">
                                Cancelled
                            </span>',

                    default =>
                    '<span class="badge bg-warning text-dark">
                                Draft
                            </span>',
                };
            })


            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */
            ->addColumn('created_by', function (StockAdjustment $row) {
                if ($row->trashed()) {

                    return $row->deleter?->name
                        ?? 'System';
                }

                return $row->creator?->name
                    ?? '-';
            })


            /*
            |--------------------------------------------------------------------------
            | Action
            |--------------------------------------------------------------------------
            */

            ->addColumn('action', function (StockAdjustment $row) {
                $isTrashed = $row->trashed();
                $isDraft = $row->isDraft();
                $isCancelled = $row->isCancelled();

                return view(
                    'components.action-dropdown',
                    [
                        'row' => $row,
                        'module' => 'stock-adjustments',
                        'show' => true,
                        'edit' => !$isTrashed && $isDraft && !$isCancelled,
                        'complete' => !$isTrashed && $isDraft && !$isCancelled,
                        'cancel' => !$isTrashed && !$isCancelled,
                        'delete' => !$isTrashed,
                        'restore' => $isTrashed,
                        'forceDelete' => $isTrashed,
                    ]
                );
            })


            ->rawColumns([
                'adjustment_number',
                'type',
                'status',
                'action',
            ])

            ->make(true);
    }


    /**
     * Show the form for creating a stock adjustment.
     */
    public function create()
    {
        $this->authorize('create', StockAdjustment::class);

        $medicines = Medicine::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'purchase_price',
                'selling_price',
            ]);

        return view('stock_adjustments.create', compact('medicines'));
    }


    /**
     * Store a new stock adjustment.
     */
    public function store(StoreStockAdjustmentRequest $request)
    {
        $this->authorize('create', StockAdjustment::class);

        try {

            $adjustment = $this->stockAdjustmentService->store($request->validated());

            return redirect()
                ->route('stock-adjustments.show', $adjustment)
                ->with(
                    'success',
                    'Stock adjustment created successfully.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create stock adjustment.' . $e->getMessage()
                );
        }
    }


    /**
     * Display the specified stock adjustment.
     */
    public function show(StockAdjustment $stockAdjustment)
    {
        $this->authorize('view', $stockAdjustment);


        $stockAdjustment = $stockAdjustment->load([
            'creator',
            'updater',
            'deleter',
            'items.medicine',
            'items.purchaseItem',
        ]);


        return view('stock_adjustments.show', compact('stockAdjustment'));
    }


    /**
     * Show the form for editing the specified stock adjustment.
     */
    public function edit(StockAdjustment $stockAdjustment)
    {
        $this->authorize('update', $stockAdjustment);

        if (!$stockAdjustment->isDraft()) {

            return redirect()
                ->route('stock-adjustments.show', $stockAdjustment)
                ->with(
                    'error',
                    'Only draft stock adjustments can be edited.'
                );
        }


        $stockAdjustment->load([
            'items.medicine',
            'items.purchaseItem',
        ]);


        $medicines = Medicine::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'purchase_price',
                'selling_price',
            ]);


        return view('stock_adjustments.edit', compact('stockAdjustment', 'medicines'));
    }


    /**
     * Update the specified stock adjustment.
     */
    public function update(UpdateStockAdjustmentRequest $request, StockAdjustment $stockAdjustment)
    {
        $this->authorize('update', $stockAdjustment);

        try {

            $stockAdjustment = $this->stockAdjustmentService->update(
                $stockAdjustment,
                $request->validated()
            );


            return redirect()
                ->route('stock-adjustments.show', $stockAdjustment)
                ->with(
                    'success',
                    'Stock adjustment updated successfully.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update stock adjustment.'
                );
        }
    }


    /**
     * Complete a draft stock adjustment.
     */
    public function complete(StockAdjustment $stockAdjustment)
    {
        $this->authorize('complete', $stockAdjustment);

        if ($stockAdjustment->isCancelled()) {
            return back()->with(
                'error',
                'Cancelled stock adjustments cannot be completed.'
            );
        }


        if ($stockAdjustment->isCompleted()) {
            return back()->with(
                'error',
                'This stock adjustment is already completed.'
            );
        }


        try {
            // dd($stockAdjustment);

            $stockAdjustment = $this->stockAdjustmentService->complete(
                $stockAdjustment
            );

            return redirect()
                ->route('stock-adjustments.show', $stockAdjustment)
                ->with(
                    'success',
                    'Stock adjustment completed successfully.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to complete stock adjustment.'
            );
        }
    }


    /**
     * Cancel a stock adjustment.
     */
    public function cancel(StockAdjustment $stockAdjustment)
    {
        $this->authorize('cancel', $stockAdjustment);

        if ($stockAdjustment->isCancelled()) {

            return back()->with(
                'error',
                'This stock adjustment is already cancelled.'
            );
        }


        try {

            $stockAdjustment = $this->stockAdjustmentService->cancel(
                $stockAdjustment
            );


            return redirect()
                ->route(
                    'stock-adjustments.show',
                    $stockAdjustment
                )
                ->with(
                    'success',
                    'Stock adjustment cancelled successfully.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to cancel stock adjustment.'
            );
        }
    }


    /**
     * Delete the specified stock adjustment.
     */
    public function destroy(StockAdjustment $stockAdjustment)
    {
        $this->authorize('delete', $stockAdjustment);


        try {

            if ($stockAdjustment->isStockApplied()) {
                $this->stockAdjustmentService->restoreStock($stockAdjustment);
            }


            $stockAdjustment->update([
                'deleted_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);


            $stockAdjustment->delete();


            return redirect()
                ->route('stock-adjustments.index')
                ->with(
                    'success',
                    'Stock adjustment deleted successfully.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to delete stock adjustment.'
            );
        }
    }


    /**
     * Restore a deleted stock adjustment.
     */
    public function restore(int $id)
    {
        $stockAdjustment = StockAdjustment::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $stockAdjustment);

        try {
            $stockAdjustment->restore();

            $stockAdjustment->update([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Re-apply Stock For Completed Adjustments
            |--------------------------------------------------------------------------
            */

            if ($stockAdjustment->isCompleted() && !$stockAdjustment->isStockApplied()) {
                $this->stockAdjustmentService->applyStock($stockAdjustment);
            }


            return redirect()->route('stock-adjustments.index')
                ->with(
                    'success',
                    'Stock adjustment restored successfully.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to restore stock adjustment.'
            );
        }
    }


    /**
     * Permanently delete a stock adjustment.
     */
    public function forceDelete(int $id)
    {
        $stockAdjustment = StockAdjustment::onlyTrashed()
            ->with('items')
            ->findOrFail($id);


        $this->authorize('forceDelete', $stockAdjustment);

        try {

            /*
            |--------------------------------------------------------------------------
            | Safety Check
            |--------------------------------------------------------------------------
            */

            if ($stockAdjustment->isStockApplied()) {

                $this->stockAdjustmentService->restoreStock($stockAdjustment);
            }


            $stockAdjustment->items()->delete();

            $stockAdjustment->forceDelete();


            return redirect()
                ->route(
                    'stock-adjustments.index'
                )
                ->with(
                    'success',
                    'Stock adjustment permanently deleted.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to permanently delete stock adjustment.'
            );
        }
    }


    /**
     * Get available batches for a medicine.
     */
    public function medicineBatches(Medicine $medicine)
    {
        $this->authorize('create', StockAdjustment::class);

        $batches = $this->stockAdjustmentService->getAvailableBatches($medicine->id);

        return response()->json([
            'success' => true,

            'batches' => $batches->map(
                function ($batch) {
                    return [
                        'id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'expiry_date' => $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null,
                        'purchase_price' => (float) $batch->purchase_price,
                        'selling_price' => (float) $batch->selling_price,
                        'available_quantity' => (int) ($batch->available_quantity ?? 0),
                    ];
                }
            )->values(),
        ]);
    }
}
