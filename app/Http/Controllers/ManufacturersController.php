<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ManufacturersController extends Controller
{
    /**
     * Display all manufacturers
     */
    public function index()
    {
        $manufacturers = Manufacturer::all();
        return view('manufacturers.index', compact('manufacturers'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('manufacturers.create');
    }

    /**
     * Show edit form
     */
    public function edit(Manufacturer $manufacturer)
    {
        return view('manufacturers.edit', compact('manufacturer'));
    }

    /**
     * Store manufacturer
     */
    public function store(StoreManufacturerRequest $request)
    {
        Manufacturer::create([
            'name' => $request->name,

            'contact_person' => $request->contact_person,

            'phone' => $request->phone,

            'email' => $request->email,

            'website' => $request->website,

            'address' => $request->address,

            'city' => $request->city,

            'country' => $request->country,

            'notes' => $request->notes,

            'status' => $request->status,

            'sort_order' => $request->sort_order,

            'created_by' => auth()->id(),
        ]);

        return redirect()->route('manufacturers.index')->with('success', 'Manufacturer created successfully.');
    }

    /**
     * Update manufacturer
     */
    public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturers)
    {
        $manufacturers->update([
            'name' => $request->name,

            'contact_person' => $request->contact_person,

            'phone' => $request->phone,

            'email' => $request->email,

            'website' => $request->website,

            'address' => $request->address,

            'city' => $request->city,

            'country' => $request->country,

            'notes' => $request->notes,

            'status' => $request->status,

            'sort_order' => $request->sort_order,

            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('manufacturers.index')->with('success', 'Manufacturer updated successfully.');
    }

    /**
     * Delete manufacturer
     */
    public function destroy(Manufacturer $manufacturer)
    {

        $manufacturer->update([

            'deleted_by' => auth()->id(),

        ]);


        $manufacturer->delete();


        return redirect()
            ->route('manufacturers.index')
            ->with(
                'success',
                'Manufacturer deleted successfully.'
            );
    }


    /**
     * Restore deleted manufacturer.
     */
    public function restore($id)
    {

        $manufacturer = Manufacturer::onlyTrashed()
            ->findOrFail($id);


        $manufacturer->restore();


        return redirect()
            ->route('manufacturers.index')
            ->with(
                'success',
                'Manufacturer restored successfully.'
            );
    }


    /**
     * Permanently delete manufacturer.
     *
     * This action cannot be reversed.
     */
    public function forceDelete($id)
    {

        $manufacturer = Manufacturer::onlyTrashed()
            ->findOrFail($id);


        $manufacturer->forceDelete();


        return redirect()
            ->route('manufacturers.index')
            ->with(
                'success',
                'Manufacturer permanently deleted.'
            );
    }
}
