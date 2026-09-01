<?php

namespace App\Http\Controllers;

use App\Models\MedicineType;
use Illuminate\Support\Str;
use App\Http\Requests\StoreMedicineTypeRequest;
use App\Http\Requests\UpdateMedicineTypeRequest;
use Yajra\DataTables\Facades\DataTables;

class MedicineTypesController extends Controller
{

    /**
     * Display all medicine types
     */
    public function index()
    {
        $medicine_types = MedicineType::with('creator')
            ->latest()
            ->get();

        return view(
            'medicine_types.index',
            compact('medicine_types')
        );
    }

    // public function datatable()
    // {
    //     $query = MedicineType::with('creator')
    //         ->select('medicine_types.*');

    //     return DataTables::eloquent($query)

    //         ->addIndexColumn()

    //         ->editColumn('status', function ($row) {

    //             if ($row->status) {

    //                 return '<span class="badge bg-success">Active</span>';
    //             }

    //             return '<span class="badge bg-danger">Inactive</span>';
    //         })

    //         ->addColumn('created_by', function ($row) {

    //             return $row->creator?->name ?? '-';
    //         })

    //         ->addColumn('action', function ($row) {

    //             return view(
    //                 'medicine_types.actions',
    //                 compact('row')
    //             );
    //         })

    //         ->rawColumns([
    //             'status',
    //             'action',
    //         ])

    //         ->make(true);
    // }


    /**
     * Show create form
     */
    public function create()
    {
        return view('medicine_types.create');
    }


    /**
     * Store medicine type
     */
    public function store(StoreMedicineTypeRequest $request)
    {

        MedicineType::create([

            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'description' => $request->description,

            'status' => $request->status,

            'sort_order' => $request->sort_order ?? 0,

            'created_by' => auth()->id(),

        ]);


        return redirect()
            ->route('medicine-types.index')
            ->with(
                'success',
                'Medicine type created successfully.'
            );
    }


    /**
     * Show edit form
     */
    public function edit(MedicineType $medicineType)
    {

        return view(
            'medicine_types.edit',
            compact('medicineType')
        );
    }


    /**
     * Update medicine type
     */
    public function update(
        UpdateMedicineTypeRequest $request,
        MedicineType $medicineType
    ) {

        $medicineType->update([

            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'description' => $request->description,

            'status' => $request->status,

            'sort_order' => $request->sort_order ?? 0,

            'updated_by' => auth()->id(),

        ]);


        return redirect()
            ->route('medicine-types.index')
            ->with(
                'success',
                'Medicine type updated successfully.'
            );
    }



    /**
     * Delete medicine type
     */
    public function destroy(MedicineType $medicineType)
    {

        $medicineType->update([

            'deleted_by' => auth()->id(),

        ]);


        $medicineType->delete();


        return redirect()
            ->route('medicine-types.index')
            ->with(
                'success',
                'Medicine type deleted successfully.'
            );
    }


    /**
     * Restore deleted medicine type.
     */
    public function restore($id)
    {

        $medicineType = MedicineType::onlyTrashed()
            ->findOrFail($id);


        $medicineType->restore();


        return redirect()
            ->route('medicine-types.index')
            ->with(
                'success',
                'Medicine type restored successfully.'
            );
    }


    /**
     * Permanently delete medicine type.
     *
     * This action cannot be reversed.
     */
    public function forceDelete($id)
    {

        $medicineType = MedicineType::onlyTrashed()
            ->findOrFail($id);


        $medicineType->forceDelete();


        return redirect()
            ->route('medicine-types.index')
            ->with(
                'success',
                'Medicine type permanently deleted.'
            );
    }
}
