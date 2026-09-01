<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Supplier::class);
        return view('suppliers.index', [

            'totalSuppliers' => Supplier::count(),

            'activeSuppliers' => Supplier::where('status', 1)->count(),

            'inactiveSuppliers' => Supplier::where('status', 0)->count(),

            'payableSuppliers' => Supplier::where('balance_type', 'Payable')->count(),

        ]);
    }

    /**
     * Datatable 
     */
    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Supplier::with('creator')->select('suppliers.*');

        // Apply filter
        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        
        // 'all' shows both active and trashed    
        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                if ($row->trashed()) {
                    return '<span class="badge bg-warning text-dark">Trashed</span>';
                }
                return $row->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('opening_balance', function ($row) {
                return number_format($row->opening_balance, 2);
            })
            ->addColumn('created_by', function ($row) {
                // If trashed, show who deleted it
                if ($row->trashed()) {
                    return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                // Determine if supplier is trashed
                $isTrashed = $row->trashed();

                return view('components.action-dropdown', [
                    'row' => $row,
                    'module' => 'suppliers',
                    'show' => true,
                    'edit' => !$isTrashed, // Only show edit for non-trashed
                    'delete' => !$isTrashed, // Only show delete for non-trashed
                    'restore' => $isTrashed, // Show restore for trashed
                    'forceDelete' => $isTrashed, // Show force delete for trashed
                ]);
            })
            ->rawColumns([
                'status',
                'action',
            ])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Supplier::class);
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $this->authorize('create', Supplier::class);
        Supplier::create([

            ...$request->validated(),

            'created_by' => auth()->id(),

        ]);

        return redirect()

            ->route('suppliers.index')

            ->with(

                'success',

                'Supplier created successfully.'

            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $this->authorize('view', $supplier);
        $supplier->load([

            'creator',

            'updater',

            'deleter',

        ]);

        return view(

            'suppliers.show',

            compact('supplier')

        );
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Supplier $supplier)
    {
        $this->authorize('update', $supplier);
        return view(
            'suppliers.edit',
            compact('supplier')
        );
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $supplier->update([

            ...$request->validated(),

            'updated_by' => auth()->id(),

        ]);

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);
        /*
    |--------------------------------------------------------------------------
    | Future Safety Checks
    |--------------------------------------------------------------------------
    |
    | Before allowing deletion, verify that the supplier is not used in:
    |
    | Purchases
    | Purchase Returns
    | Payments
    | Ledger
    |
    */

        /*
    if ($supplier->purchases()->exists()) {

        return back()->with(
            'error',
            'Supplier cannot be deleted because purchase records exist.'
        );

    }
    */

        $supplier->update([

            'deleted_by' => auth()->id(),

        ]);

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                'Supplier moved to trash successfully.'
            );
    }


    /**
     * Restore supplier.
     */
    public function restore($id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $supplier);

        // Restore the supplier
        $supplier->restore();

        // Clear the deleted_by column since the record is no longer deleted
        $supplier->deleted_by = null;
        $supplier->save();

        // If you want to return JSON for AJAX response
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier restored successfully.'
            ]);
        }

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier restored successfully.');
    }

    /**
     * Permanently delete supplier.
     */
    public function forceDelete($id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $supplier);

        $supplier->forceDelete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier permanently deleted.'
            ]);
        }

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier permanently deleted.');
    }
}
