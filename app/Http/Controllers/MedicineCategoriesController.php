<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicineCategoryRequest;
use App\Http\Requests\UpdateMedicineCategoryRequest;
use App\Models\MedicineCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MedicineCategoriesController extends Controller
{
    public function index()
    {
        $medicine_categories = MedicineCategory::all();
        return view('medicine_categories.index', compact('medicine_categories'));
    }

    public function create()
    {
        return view('medicine_categories.create');
    }
    public function edit(MedicineCategory $medicineCategory)
    {
        return view('medicine_categories.edit', compact('medicineCategory'));
    }

    public function store(StoreMedicineCategoryRequest $request)
    {
        MedicineCategory::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('medicine-categories.index')->with('success', 'Medicine category created successfully.');
    }

    public function update(UpdateMedicineCategoryRequest $request, MedicineCategory $medicineCategory)
    {
        $medicineCategory->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('medicine-categories.index')->with('success', 'Medicine category updated successfully.');
    }

    public function destroy(MedicineCategory $medicineCategory)
    {
        $medicineCategory->update([
            'deleted_by' => auth()->id(),
        ]);

        $medicineCategory->delete();

        return redirect()->route('medicine-categories.index')->with('success', 'Medicine category deleted successfully.');
    }
}
