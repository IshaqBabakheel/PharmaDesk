<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Http\Requests\StorePurchaseReturnRequest;
use App\Http\Requests\UpdatePurchaseReturnRequest;
use App\Models\PurchaseItem;
use App\Services\PurchaseReturnService;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnController extends Controller
{
    protected PurchaseReturnService $purchaseReturnService;

    public function __construct(PurchaseReturnService $purchaseReturnService)
    {
        $this->purchaseReturnService = $purchaseReturnService;
    }

    /**
     * Display Listing
     */
    public function index()
    {
        $this->authorize('viewAny', PurchaseReturn::class);

        $totalReturns = PurchaseReturn::count();
        $completedReturns = PurchaseReturn::where('status', 'Completed')->count();
        $draftReturns = PurchaseReturn::where('status', 'Draft')->count();
        $totalAmount = PurchaseReturn::sum('grand_total');

        return view('purchase_returns.index', compact('totalReturns', 'completedReturns', 'draftReturns', 'totalAmount'));
    }

    /**
     * Datatable
     */
    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = PurchaseReturn::with(['supplier', 'purchase', 'creator',]);

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        
        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->editColumn('return_date', function ($row) {

                return $row->return_date->format('d M Y');
            })

            ->addColumn('purchase_number', function ($row) {

                return $row->purchase->purchase_number;
            })

            ->addColumn('supplier', function ($row) {

                return $row->supplier->name;
            })

            ->editColumn('grand_total', function ($row) {

                return number_format(

                    $row->grand_total,

                    2

                );
            })

            ->editColumn('status', function ($row) {

                $class = match ($row->status) {

                    'Completed' => 'success',

                    'Draft' => 'warning',

                    default => 'danger',
                };

                return '<span class="badge bg-' . $class . '">' . $row->status . '</span>';
            })

            ->addColumn('created_by', function ($row) {
                if($row->trashed()){
                   return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })

            ->addColumn('action', function ($row) {
                $isTrashed = $row->trashed();
                return view('components.action-dropdown',
                    [
                        'row' => $row,
                        'module' => 'purchase-returns',
                        'show' => true,
                        'edit' => !$isTrashed,
                        'delete' => !$isTrashed,
                        'restore' => $isTrashed,
                        'forceDelete' => $isTrashed,
                    ]

                );
            })

            ->rawColumns([
                'status',
                'action',
            ])
            ->make(true);
    }

    /**
     * Get purchase items for return.
     */
    // public function purchaseItems(Purchase $purchase)
    // {
    //     $this->authorize('create', PurchaseReturn::class);

    //     $items = $this->purchaseReturnService->purchaseItems($purchase);

    //     return response()->json($items);
    // }
    

    /**
     * Create
     */
    // public function create()
    // {
    //     $this->authorize('create', PurchaseReturn::class);

    //     $purchases = Purchase::where('status', 'Completed')->latest()->get();
    //     $suppliers = Supplier::where('status', 1)->orderBy('name')->get();

    //     $selectedPurchase = null;

    //     if (request()->filled('purchase')) {

    //         $selectedPurchase = Purchase::query()
    //             ->where('status', 'Completed')
    //             ->find(request('purchase'));

    //         if ($selectedPurchase) {
    //             $selectedPurchase->load('supplier');
    //         }
    //     }

    //     return view('purchase_returns.create', compact('purchases', 'suppliers', 'selectedPurchase'));
    // }
    public function create()
    {
        $this->authorize('create', PurchaseReturn::class);

        $purchases = Purchase::where('status', 'completed')
            ->latest()
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->orderBy('name')
            ->get();

        $selectedPurchase = null;

        $lockedPurchase = false;

        if (request()->filled('purchase')) {

            $selectedPurchase = Purchase::query()
                ->where('status', 'completed')
                ->findOrFail(request('purchase'));

            $selectedPurchase->load('supplier');

            $lockedPurchase = true;
        }

        return view(
            'purchase_returns.create',
            compact(
                'purchases',
                'suppliers',
                'selectedPurchase',
                'lockedPurchase'
            )
        );
    }

    /**
     * Store
     */
    // public function store(StorePurchaseReturnRequest $request)
    // {
    //     $this->authorize('create', PurchaseReturn::class);

    //     $purchase = Purchase::findOrFail($request->purchase_id);
    //     $this->validateReturnQuantities($purchase, $request->supplier_id ,$request->items);
        
    //     DB::transaction(function () use ($request) {

    //         /*
    //         ---------------------------------------
    //         Create Return
    //         ---------------------------------------
    //         */

    //         $purchaseReturn = PurchaseReturn::create([

    //             'purchase_id' => $request->purchase_id,

    //             'supplier_id' => $request->supplier_id,

    //             'return_date' => $request->return_date,

    //             'subtotal' => $request->subtotal,

    //             'discount_type' => $request->discount_type,

    //             'discount' => $request->discount,

    //             'tax_type' => $request->tax_type,

    //             'tax' => $request->tax,

    //             'grand_total' => $request->grand_total,

    //             'status' => $request->status,

    //             'reason' => $request->reason,

    //             'notes' => $request->notes,

    //             'created_by' => auth()->id(),

    //         ]);

    //         /*
    //         ---------------------------------------
    //         Save Returned Medicines
    //         ---------------------------------------
    //         */

    //         foreach ($request->items as $item) {

    //             PurchaseReturnItem::create([

    //                 'purchase_return_id' => $purchaseReturn->id,

    //                 'purchase_item_id' => $item['purchase_item_id'],

    //                 'medicine_id' => $item['medicine_id'],

    //                 'batch_number' => $item['batch_number'],

    //                 'expiry_date' => $item['expiry_date'],

    //                 'quantity' => $item['quantity'],

    //                 'purchase_price' => $item['purchase_price'],

    //                 'total' => $item['total'],

    //             ]);

    //             /*
    //             ---------------------------------------
    //             Reduce Medicine Stock
    //             ---------------------------------------
    //             */

    //             Medicine::find($item['medicine_id'])->decrement('current_stock', $item['quantity']);
    //         }
    //     });

    //     return redirect()->route('purchase-returns.index')->with('success', 'Purchase Return created successfully.');
    // }
    public function store(StorePurchaseReturnRequest $request)
    {
        $this->authorize('create', PurchaseReturn::class);

        try {

            $this->purchaseReturnService->store(
                $request->validated()
            );

            return redirect()
                ->route('purchase-returns.index')
                ->with('success', 'Purchase Return created successfully.');

        } catch (ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to create purchase return.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(PurchaseReturn $purchaseReturn)
    {
        $this->authorize('view', $purchaseReturn);

        $purchaseReturn->load([

            'purchase',

            'supplier',

            'items.medicine',

            'creator',

            'updater',

            'deleter',

        ]);

        return view('purchase_returns.show', compact('purchaseReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseReturn $purchaseReturn)
    {
        $this->authorize('update', $purchaseReturn);

        $purchaseReturn->load(['purchase', 'items',]);
        $purchases = Purchase::where('status', 'Completed')->latest()->get();
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();

        return view('purchase_returns.edit', compact('purchaseReturn', 'purchases', 'suppliers'));
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdatePurchaseReturnRequest $request, PurchaseReturn $purchaseReturn)
    {
        $this->authorize('update', $purchaseReturn);

        $purchase = Purchase::findOrFail($request->purchase_id);
        $this->purchaseReturnService->validateReturnQuantities($purchase, $request->supplier_id, $request->status, $request->items, $purchaseReturn);

        DB::transaction(function () use ($request, $purchaseReturn) {

            /*
        |--------------------------------------------------------------------------
        | Restore Previous Stock
        |--------------------------------------------------------------------------
        */

            foreach ($purchaseReturn->items as $item) {

                Medicine::find($item->medicine_id)->increment('current_stock', $item->quantity);
            }

            /*
        |--------------------------------------------------------------------------
        | Remove Previous Items
        |--------------------------------------------------------------------------
        */

            $purchaseReturn->items()->delete();

            /*
        |--------------------------------------------------------------------------
        | Update Header
        |--------------------------------------------------------------------------
        */

            $purchaseReturn->update([

                'purchase_id' => $request->purchase_id,

                'supplier_id' => $request->supplier_id,

                'return_date' => $request->return_date,

                'subtotal' => $request->subtotal,

                'discount_type' => $request->discount_type,

                'discount' => $request->discount,

                'tax_type' => $request->tax_type,

                'tax' => $request->tax,

                'grand_total' => $request->grand_total,

                'status' => $request->status,

                'reason' => $request->reason,

                'notes' => $request->notes,

                'updated_by' => auth()->id(),

            ]);

            /*
        |--------------------------------------------------------------------------
        | Save New Items
        |--------------------------------------------------------------------------
        */

            foreach ($request->items as $item) {

                PurchaseReturnItem::create([

                    'purchase_return_id' => $purchaseReturn->id,

                    'purchase_item_id' => $item['purchase_item_id'],

                    'medicine_id' => $item['medicine_id'],

                    'batch_number' => $item['batch_number'],

                    'expiry_date' => $item['expiry_date'],

                    'quantity' => $item['quantity'],

                    'purchase_price' => $item['purchase_price'],

                    'total' => $item['total'],

                ]);

                /*
            |--------------------------------------------------------------------------
            | Reduce Stock Again
            |--------------------------------------------------------------------------
            */

                Medicine::find($item['medicine_id'])->decrement('current_stock', $item['quantity']);
            }
        });

        return redirect()->route('purchase-returns.index')->with('success', 'Purchase Return updated successfully.');
    }

    /**
     * Soft Delete
     */
    public function destroy(PurchaseReturn $purchaseReturn): void
    {
        DB::transaction(function () use ($purchaseReturn) {

            if ($purchaseReturn->isStockApplied()) {
                $this->purchaseReturnService->restoreStock($purchaseReturn);
            }

            $purchaseReturn->update([
                'deleted_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $purchaseReturn->delete();
        });
    }

    /**
     * Restore
     */
    public function restore(PurchaseReturn $purchaseReturn): PurchaseReturn
    {
        return DB::transaction(function () use ($purchaseReturn) {

            $purchaseReturn->restore();

            if ($purchaseReturn->status === 'Completed') {
                $this->purchaseReturnService->applyStock($purchaseReturn);
            }

            $purchaseReturn->update([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            return $purchaseReturn->fresh();
        });
    }

    /**
     * Force Delete
     */
    public function forceDelete($id)
    {
        $purchaseReturn = PurchaseReturn::onlyTrashed()->with('items')->findOrFail($id);

        $this->authorize('forceDelete', $purchaseReturn);

        DB::transaction(function () use ($purchaseReturn) {

            /*
        |--------------------------------------------------------------------------
        | Future Safeguards
        |--------------------------------------------------------------------------
        |
        | Supplier Credit Note Exists?
        | Financial Voucher Posted?
        | Purchase Return Invoice Locked?
        | Audit Closed?
        |
        */

            $purchaseReturn->items()->delete();

            $purchaseReturn->forceDelete();
        });

        return back()->with(
            'success',
            'Purchase Return permanently deleted.'

        );
    }

    /**
     * Get suppliers that have completed purchases.
     */
    public function suppliers()
    {
        $this->authorize('create', PurchaseReturn::class);

        $suppliers = Supplier::query()
            ->where('status', 1)
            ->whereHas('purchases', function ($query) {
                $query->where('status', 'completed');
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return response()->json([
            'success' => true,
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Get completed purchases for a supplier.
     */
    public function supplierPurchases(Supplier $supplier)
    {
        $this->authorize('create', PurchaseReturn::class);

        $purchases = $supplier->purchases()
            ->where('status', 'completed')
            ->latest('id')
            ->get([
                'id',
                'purchase_number',
                'purchase_date',
                'supplier_id',
            ]);

        return response()->json([
            'success' => true,
            'purchases' => $purchases,
            // 'id' => $purchase->id,
            // 'purchase_number' => $purchase->purchase_number,
            // 'purchase_date' => $purchase->purchase_date?->format('d M Y'),
            // 'supplier_id' => $purchase->supplier_id,
        ]);
        // ->map(function ($purchase) {
        //     return [
        //         'id' => $purchase->id,
        //         'purchase_number' => $purchase->purchase_number,
        //         'purchase_date' => $purchase->purchase_date?->format('d M Y'),
        //         'supplier_id' => $purchase->supplier_id,
        //     ];
        // });
    }

    /**
     * Get returnable items for a purchase.
     */
    public function purchaseItems(Purchase $purchase)
    {
        $this->authorize('create', PurchaseReturn::class);

        if ($purchase->status !== 'Completed') {
            return response()->json([
                'success' => false,
                'message' => 'Only completed purchases can have returns.',
                'items' => [],
            ], 422);
        }

        $items = $this->purchaseReturnService
            ->purchaseItems($purchase);

        return response()->json([
            'success' => true,

            'purchase' => [
                'id' => $purchase->id,
                'purchase_number' => $purchase->purchase_number,
                'supplier_id' => $purchase->supplier_id,
            ],

            'items' => $items,
        ]);
    }
}
