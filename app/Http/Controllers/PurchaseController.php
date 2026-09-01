<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    protected PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Display purchases.
     */
    public function index()
    {
        $this->authorize('viewAny', Purchase::class);

        return view('purchases.index', [

            'totalPurchases' => Purchase::count(),

            'completedPurchases' => Purchase::where('status', 'Completed')->count(),

            'draftPurchases' => Purchase::where('status', 'Draft')->count(),

            'totalAmount' => Purchase::sum('grand_total'),

        ]);
    }


    /**
     * purchase's Datatable.
     */
    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = Purchase::with(['supplier', 'creator'])->select('purchases.*');

        // Apply filter
        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('purchase_date', function ($row) {

                return $row->purchase_date->format('d M Y');
            })

            ->addColumn('supplier', function ($row) {

                return $row->supplier?->name;
            })

            ->editColumn('grand_total', function ($row) {

                return number_format($row->grand_total, 2);
            })

            ->editColumn('payment_status', function ($row) {

                $class = match ($row->payment_status) {

                    'Paid' => 'success',

                    'Partially Paid' => 'warning',

                    default => 'danger'
                };

                return "<span class='badge bg-{$class}'>{$row->payment_status}</span>";
            })

            ->editColumn('status', function ($row) {

                $class = match ($row->status) {

                    'Completed' => 'success',

                    'Draft' => 'secondary',

                    default => 'danger'
                };

                return "<span class='badge bg-{$class}'>{$row->status}</span>";
            })

            ->addColumn('created_by', function ($row) {
                // If trashed, show who deleted it
                if ($row->trashed()) {
                    return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })

            ->addColumn('action', function ($row) {

                $isTrashed = $row->trashed();
                $isDraft = $row->isDraft();
                $isPaid = $row->isPaid();
                $isCancelled = $row->isCancelled();
                $isCompleted = $row->isCompleted();

                return view('components.action-dropdown', [
                    'row' => $row,
                    'module' => 'purchases',
                    // View
                    'show' => true,
                    // Only draft purchases can be edited
                    'edit' => $isDraft && !$isTrashed && !$isCancelled,
                    // Payment can be updated only on active,
                    // non-cancelled purchases
                    'updatePayment' => !$isTrashed && !$isCancelled && !$isPaid,
                    // Only draft purchases can be completed
                    'complete' => $isDraft && !$isTrashed && !$isCancelled,
                    'purchaseReturn' => !$isTrashed && $isCompleted,
                    // Active purchases can be cancelled
                    'cancel' => !$isTrashed && !$isCancelled && !$isCompleted,
                    // Delete active records
                    'delete' => !$isTrashed,
                    // Restore trashed records
                    'restore' => $isTrashed,
                    // Permanently delete trashed records
                    'forceDelete' => $isTrashed,
                ]);
            })

            ->rawColumns([

                'payment_status',

                'status',

                'action',

            ])

            ->make(true);
    }


    /**
     * Display Purchases.
     */
    public function show(Purchase $purchase)
    {
        $this->authorize('view', $purchase);
        $purchase->load([
            'supplier',
            'items.medicine',
            'creator',
            'updater',
            'deleter',
        ]);

        return view('purchases.show', compact('purchase'));
    }


    /**
     * Create purchase.
     */
    public function create()
    {
        $this->authorize('create', Purchase::class);

        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $medicines = Medicine::where('status', 1)->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'medicines'));
    }


    /**
     * Store purchase.
     */
    public function store(StorePurchaseRequest $request)
    {
        $this->authorize('create', Purchase::class);

        try {

            $this->purchaseService->store(
                $request->validated()
            );

            return redirect()
                ->route('purchases.index')
                ->with('success', 'Purchase created successfully.');

        } catch (ValidationException $e) {
            throw $e;       
        } catch (\Throwable $e) {

            report($e);
            return back()
                ->withInput()
                ->with('error', 'Unable to create the purchase.' . $e->getMessage());
        }
    }

    


    /**
     * Edit purchase.
     */
    public function edit(Purchase $purchase)
    {
        $this->authorize('update', $purchase);

        $purchase->load('items');
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        $medicines = Medicine::where('status', 1)->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'medicines'));
    }


    /**
     * Update the specified purchase.
     */
    // public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    // {
    //     $this->authorize('update', $purchase);

    //     DB::transaction(function () use ($request, $purchase) {

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Reverse Previous Stock
    //     |--------------------------------------------------------------------------
    //     */

    //         foreach ($purchase->items as $item) {

    //             $medicine = Medicine::find($item->medicine_id);

    //             if ($medicine) {

    //                 $medicine->decrement(

    //                     'current_stock',

    //                     $item->quantity + $item->free_quantity

    //                 );
    //             }
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Remove Old Items
    //     |--------------------------------------------------------------------------
    //     */

    //         $purchase->items()->delete();

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Update Purchase
    //     |--------------------------------------------------------------------------
    //     */

    //         $purchase->update([

    //             'supplier_id' => $request->supplier_id,

    //             'invoice_number' => $request->invoice_number,

    //             'reference_number' => $request->reference_number,

    //             'purchase_date' => $request->purchase_date,

    //             'subtotal' => $request->subtotal,

    //             'discount_type' => $request->discount_type,

    //             'discount' => $request->discount,

    //             'tax_type' => $request->tax_type,

    //             'tax' => $request->tax,

    //             'shipping' => $request->shipping,

    //             'other_charges' => $request->other_charges,

    //             'grand_total' => $request->grand_total,

    //             'paid_amount' => $request->paid_amount,

    //             'due_amount' => $request->due_amount,

    //             'payment_status' => $request->payment_status,

    //             'status' => $request->status,

    //             'notes' => $request->notes,

    //             'updated_by' => auth()->id(),

    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Save New Items
    //     |--------------------------------------------------------------------------
    //     */

    //         foreach ($request->items as $item) {

    //             PurchaseItem::create([

    //                 'purchase_id' => $purchase->id,

    //                 'medicine_id' => $item['medicine_id'],

    //                 'batch_number' => $item['batch_number'],

    //                 'expiry_date' => $item['expiry_date'],

    //                 'quantity' => $item['quantity'],

    //                 'free_quantity' => $item['free_quantity'],

    //                 'purchase_price' => $item['purchase_price'],

    //                 'selling_price' => $item['selling_price'],

    //                 'discount' => $item['discount'],

    //                 'tax' => $item['tax'],

    //                 'total' => $item['total'],

    //             ]);

    //             $medicine = Medicine::find($item['medicine_id']);

    //             $medicine->increment(

    //                 'current_stock',

    //                 $item['quantity'] + $item['free_quantity']

    //             );

    //             $medicine->update([

    //                 'purchase_price' => $item['purchase_price'],

    //                 'selling_price' => $item['selling_price'],

    //             ]);
    //         }
    //     });

    //     return redirect()
    //         ->route('purchases.index')
    //         ->with('success', 'Purchase updated successfully.');
    // }
    public function update(UpdatePurchaseRequest $request, Purchase $purchase) 
    {
        $this->authorize('update', $purchase);

        $this->purchaseService->update(
            $purchase,
            $request->validated()
        );

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase updated successfully.');
    }

    /**
     * Complete a draft purchase.
     */
    public function complete(Purchase $purchase)
    {
        $this->authorize('complete', $purchase);

        if ($purchase->isCancelled()) {
            return back()->with(
                'error',
                'Cancelled purchases cannot be completed.'
            );
        }

        if ($purchase->isCompleted()) {
            return back()->with(
                'error',
                'This purchase is already completed.'
            );
        }

        try {

            $purchase = $this->purchaseService->complete($purchase);

            return redirect()
                ->route('purchases.index')
                ->with(
                    'success',
                    'Purchase completed successfully.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to complete the purchase.'
            );
        }
    }

    /**
     * Cancel the specified purchase.
     */
    public function cancel(Purchase $purchase)
    {
        $this->authorize('cancel', $purchase);

        if ($purchase->isCancelled()) {
            return back()->with(
                'error',
                'This purchase is already cancelled.'
            );
        }

        try {

            $this->purchaseService->cancel($purchase);

            return redirect()
                ->route('purchases.index')
                ->with(
                    'success',
                    'Purchase cancelled successfully and stock restored.'
                );
        } catch (ValidationException $e) {

            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Unable to cancel the purchase.'
            );
        }
    }

    public function updatePayment(Request $request, Purchase $purchase)
    {
        $this->authorize('updatePayment', $purchase);

        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $this->purchaseService->updatePayment(
                $purchase,
                (float) $validated['paid_amount']
            );

            // Check if the request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment updated successfully.',
                    'data' => [
                        'paid_amount' => $purchase->paid_amount,
                        'due_amount' => $purchase->due_amount,
                        'payment_status' => $purchase->payment_status,
                        'grand_total' => $purchase->grand_total
                    ]
                ]);
            }

            return back()->with('success', 'Payment updated successfully.');
        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to update payment: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Unable to update payment.');
        }
    }

    /**
     * Reference number for front end.
     */
    public function nextReference()
    {
        return response()->json([
            'reference_number' => $this->purchaseService->generateReferenceNumber(),
        ]);
    }

    /**
     * Remove the specified purchase.
     */
    public function destroy(Purchase $purchase)
    {
        $this->authorize('delete', $purchase);

        DB::transaction(function () use ($purchase) {

            foreach ($purchase->items as $item) {

                $medicine = Medicine::find($item->medicine_id);

                if ($medicine) {

                    $medicine->decrement(

                        'current_stock',

                        $item->quantity + $item->free_quantity

                    );
                }
            }

            $purchase->update([

                'deleted_by' => auth()->id(),

            ]);

            $purchase->delete();
        });

        return back()->with('success', 'Purchase moved to trash successfully.');
    }

    /**
     * Restore the specified purchase.
     */
    public function restore($id)
    {
        $purchase = Purchase::onlyTrashed()->with('items')->findOrFail($id);

        $this->authorize('restore', $purchase);

        DB::transaction(function () use ($purchase) {

            $purchase->restore();

            foreach ($purchase->items as $item) {

                $medicine = Medicine::find($item->medicine_id);

                if ($medicine) {

                    $medicine->increment(

                        'current_stock',

                        $item->quantity + $item->free_quantity

                    );
                }
            }
        });

        return back()->with('success', 'Purchase restored successfully.');
    }

    /**
     * Permanently remove the specified purchase.
     */
    public function forceDelete($id)
    {
        $purchase = Purchase::onlyTrashed()
            ->with('items')
            ->findOrFail($id);

        $this->authorize('forceDelete', $purchase);

        DB::transaction(function () use ($purchase) {

            /*
        |--------------------------------------------------------------------------
        | Future Safeguards
        |--------------------------------------------------------------------------
        |
        | Add checks here before permanent deletion:
        |
        | Purchase Return Exists?
        | Supplier Ledger Exists?
        | Financial Transaction Exists?
        | Audit Lock?
        |
        */

            $purchase->items()->delete();

            $purchase->forceDelete();
        });

        return back()->with('success', 'Purchase permanently deleted.');
    }

}
