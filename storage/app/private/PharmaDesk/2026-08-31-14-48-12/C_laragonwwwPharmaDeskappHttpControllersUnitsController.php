<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Support\Str;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;

class UnitsController extends Controller
{
    /**
     * Display all units.
     */
    public function index()
    {
        $units = Unit::with('creator')
            ->latest()
            ->get();

        return view('units.index', compact('units'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store unit.
     */
    public function store(StoreUnitRequest $request)
    {
        Unit::create([

            'name' => $request->name,

            'short_name' => $request->short_name,

            'description' => $request->description,

            'status' => $request->status,

            'sort_order' => $request->sort_order ?? 0,

            'created_by' => auth()->id(),

        ]);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    /**
     * Update unit.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $unit->update([

            'name' => $request->name,

            'short_name' => $request->short_name,

            'description' => $request->description,

            'status' => $request->status,

            'sort_order' => $request->sort_order ?? 0,

            'updated_by' => auth()->id(),

        ]);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Delete unit.
     */
    public function destroy(Unit $unit)
    {
        $unit->update([
            'deleted_by' => auth()->id(),
        ]);

        $unit->delete();

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }

    /**
     * Restore Unit
     */
    public function restore($id)
    {
        $unit = Unit::onlyTrashed()->findOrFail($id);

        $unit->restore();

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit restored successfully.');
    }

    /**
     * Force Delete
     */
    public function forceDelete($id)
    {
        $unit = Unit::onlyTrashed()->findOrFail($id);

        $unit->forceDelete();

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit permanently deleted.');
    }
}