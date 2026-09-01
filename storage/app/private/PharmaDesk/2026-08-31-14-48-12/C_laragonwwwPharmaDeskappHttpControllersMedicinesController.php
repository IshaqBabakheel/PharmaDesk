<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Manufacturer;
use App\Models\MedicineCategory;
use App\Models\MedicineType;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MedicinesController extends Controller
{

    /**
     * --------------------------------------------------------------------------
     * Display Medicines
     * --------------------------------------------------------------------------
     */
    public function index()
    {
        $stats = [

            'total' => Medicine::count(),

            'active' => Medicine::where('status', true)->count(),

            'inactive' => Medicine::where('status', false)->count(),

            'low_stock' => Medicine::whereColumn(
                'current_stock',
                '<=',
                'reorder_level'
            )->count(),

            'out_of_stock' => Medicine::where(
                'current_stock',
                '<=',
                0
            )->count(),

        ];

        return view('medicines.index', [

            'stats' => $stats,

            'categories' => MedicineCategory::active()
                ->orderBy('sort_order')
                ->get(),

            'types' => MedicineType::active()
                ->orderBy('sort_order')
                ->get(),

            'manufacturers' => Manufacturer::active()
                ->orderBy('sort_order')
                ->get(),

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Datatable
     * --------------------------------------------------------------------------
     */
    public function datatable(Request $request)
    {

        $filter = $request->get('filter', 'all');
        $query = Medicine::with(['category', 'type', 'manufacturer', 'unit','creator',]);

        if ($request->filled('search_text')) {

            $search = $request->search_text;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('medicine_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {

            $query->where(
                'medicine_category_id',
                $request->category
            );
        }

        if ($request->filled('type')) {

            $query->where(
                'medicine_type_id',
                $request->type
            );
        }

        if ($request->filled('manufacturer')) {

            $query->where(
                'manufacturer_id',
                $request->manufacturer
            );
        }

        if ($request->status !== null && $request->status !== '') {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->stock === 'low') {

            $query->whereColumn(
                'current_stock',
                '<=',
                'reorder_level'
            );
        }

        if ($request->stock === 'out') {

            $query->where(
                'current_stock',
                '<=',
                0
            );
        }

        if ($request->stock === 'available') {

            $query->where(
                'current_stock',
                '>',
                0
            );
        }

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active'){
            $query->whereNull('deleted_at');
        }
        
        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('image', function ($row) {

                if ($row->image) {

                    return '<img src="' . asset('storage/' . $row->image) . '" 
                        width="50" 
                        height="50"
                        class="rounded"
                        style="object-fit:cover;">';
                }

                return '<img src="' . asset('images/no-image.png') . '" 
                    width="50" 
                    height="50"
                    class="rounded">';
            })

            ->addColumn('category', fn($row) => $row->category?->name)

            ->addColumn('type', fn($row) => $row->type?->name)

            ->addColumn('manufacturer', fn($row) => $row->manufacturer?->name)

            ->addColumn('unit', fn($row) => $row->unit?->name)

            ->addColumn('created_by', function ($row){
                if ($row->trashed()) {
                    return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })

            ->editColumn('status', function ($row) {

                return $row->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('action', function ($row) {
                // Determine if supplier is trashed
                $isTrashed = $row->trashed();
                return view('components.action-dropdown', [

                    'row' => $row,

                    'module' => 'medicines',

                    'show' => true,

                    'edit' => !$isTrashed,

                    'delete' => !$isTrashed,

                    'duplicate' => !$isTrashed,

                    'barcode' => !$isTrashed,

                    'stockHistory' => !$isTrashed,

                    'purchaseHistory' => !$isTrashed,

                    'salesHistory' => !$isTrashed,

                    'restore' => $isTrashed,

                    'forceDelete' => $isTrashed,

                ]);
            })

            ->rawColumns([

                'status',

                'action',

                'image',

            ])

            ->make(true);
            
    }

    /**
     * Display medicine details.
     */
    public function show(Medicine $medicine)
    {
        $medicine->load([
            'category',
            'type',
            'manufacturer',
            'unit',
            'creator',
            'updater',
        ]);

        return view(
            'medicines.show',
            compact('medicine')
        );
    }

    /**
     * Display stock history.
     */
    public function stockHistory(Medicine $medicine)
    {
        return redirect()
            ->route('medicines.show', $medicine)
            ->with(
                'info',
                'Stock history module will be available after the Inventory module is completed.'
            );
    }

    /**
     * Display purchase history.
     */
    public function purchaseHistory(Medicine $medicine)
    {
        return redirect()
            ->route('medicines.show', $medicine)
            ->with(
                'info',
                'Purchase history will be available after the Purchase module is completed.'
            );
    }

    /**
     * Display sales history.
     */
    public function salesHistory(Medicine $medicine)
    {
        return redirect()
            ->route('medicines.show', $medicine)
            ->with(
                'info',
                'Sales history will be available after the POS module is completed.'
            );
    }

    /**
     * Display printable barcode.
     */
    public function barcode(Medicine $medicine)
    {
        return view(
            'medicines.barcode',
            compact('medicine')
        );
    }

    /**
     * Duplicate a medicine.
     */
    public function duplicate(Medicine $medicine)
    {
        $newMedicine = $medicine->replicate();

        $newMedicine->name .= ' (Copy)';

        $newMedicine->created_by = auth()->id();

        $newMedicine->updated_by = null;

        $newMedicine->save();

        return redirect()
            ->route('medicines.edit', $newMedicine)
            ->with(
                'success',
                'Medicine duplicated successfully. Please review and save the new record.'
            );
    }

    /**
     * --------------------------------------------------------------------------
     * Create Form
     * --------------------------------------------------------------------------
     */
    public function create()
    {

        return view('medicines.create', $this->formData());
    }

    /**
     * --------------------------------------------------------------------------
     * Store Medicine
     * --------------------------------------------------------------------------
     */
    public function store(StoreMedicineRequest $request)
    {

        DB::transaction(function () use ($request) {

            $data = $request->validated();

            if ($request->hasFile('image')) {

                $data['image'] = $request
                    ->file('image')
                    ->store('medicines', 'public');
            }

            $data['created_by'] = auth()->id();

            Medicine::create($data);
        });

        return redirect()

            ->route('medicines.index')

            ->with(
                'success',
                'Medicine created successfully.'
            );
    }

    /**
     * --------------------------------------------------------------------------
     * Edit Form
     * --------------------------------------------------------------------------
     */
    public function edit(Medicine $medicine)
    {

        return view(
            'medicines.edit',
            array_merge(
                [
                    'medicine' => $medicine
                ],
                $this->formData()
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Update Medicine
     * --------------------------------------------------------------------------
     */
    public function update(
        UpdateMedicineRequest $request,
        Medicine $medicine
    ) {

        DB::transaction(function () use ($request, $medicine) {

            $data = $request->validated();

            if ($request->hasFile('image')) {

                if (
                    $medicine->image &&
                    Storage::disk('public')->exists($medicine->image)
                ) {

                    Storage::disk('public')
                        ->delete($medicine->image);
                }

                $data['image'] = $request
                    ->file('image')
                    ->store('medicines', 'public');
            }

            $data['updated_by'] = auth()->id();

            $medicine->update($data);
        });

        return redirect()

            ->route('medicines.index')

            ->with(
                'success',
                'Medicine updated successfully.'
            );
    }

    /**
     * --------------------------------------------------------------------------
     * Delete Medicine
     * --------------------------------------------------------------------------
     */
    public function destroy(Medicine $medicine)
    {

        $medicine->update([

            'deleted_by' => auth()->id(),

        ]);

        $medicine->delete();

        return redirect()

            ->route('medicines.index')

            ->with(
                'success',
                'Medicine deleted successfully.'
            );
    }

    /**
     * --------------------------------------------------------------------------
     * Restore
     * --------------------------------------------------------------------------
     */
    public function restore($id)
    {

        Medicine::onlyTrashed()

            ->findOrFail($id)

            ->restore();

        return back()->with(
            'success',
            'Medicine restored successfully.'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Permanent Delete
     * --------------------------------------------------------------------------
     */
    public function forceDelete($id)
    {

        $medicine = Medicine::onlyTrashed()

            ->findOrFail($id);

        if (
            $medicine->image &&
            Storage::disk('public')->exists($medicine->image)
        ) {

            Storage::disk('public')
                ->delete($medicine->image);
        }

        $medicine->forceDelete();

        return back()->with(
            'success',
            'Medicine permanently deleted.'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Shared Form Data
     * --------------------------------------------------------------------------
     */
    private function formData(): array
    {
        return [

            'categories' => MedicineCategory::active()
                ->orderBy('sort_order')
                ->get(),

            'types' => MedicineType::active()
                ->orderBy('sort_order')
                ->get(),

            'manufacturers' => Manufacturer::active()
                ->orderBy('sort_order')
                ->get(),

            'units' => Unit::active()
                ->orderBy('sort_order')
                ->get(),

        ];
    }
}
