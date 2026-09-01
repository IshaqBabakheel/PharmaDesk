<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name'      => 'required|string|max:255',
            'owner_name'      => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'currency'        => 'nullable|string|max:20',
            'timezone'        => 'nullable|string|max:255',
            'tax_percentage'  => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => 'string'
                ]
            );
        }

        return back()->with('success', 'settings updated successfully.');
    }
}
