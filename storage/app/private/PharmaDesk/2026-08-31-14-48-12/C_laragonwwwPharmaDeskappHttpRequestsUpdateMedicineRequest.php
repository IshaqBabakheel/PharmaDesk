<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => [

                'required',

                'string',

                'max:255',

                Rule::unique('medicines')
                    ->ignore($this->medicine),

            ],

            'generic_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'medicine_category_id' => [
                'required',
                'exists:medicine_categories,id',
            ],

            'medicine_type_id' => [
                'required',
                'exists:medicine_types,id',
            ],

            'manufacturer_id' => [
                'required',
                'exists:manufacturers,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'gte:purchase_price',
            ],

            'wholesale_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'opening_stock' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_stock' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'reorder_level' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Expiry
            |--------------------------------------------------------------------------
            */

            'has_expiry' => [
                'required',
                'boolean',
            ],

            'shelf_life_months' => [
                'nullable',
                'integer',
                'min:1',
                'max:120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Tax
            |--------------------------------------------------------------------------
            */

            'tax_percentage' => [
                'nullable',
                'numeric',
                'between:0,100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            /*
            |--------------------------------------------------------------------------
            | Other
            |--------------------------------------------------------------------------
            */

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ];
    }

    /**
     * Custom Validation Messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Medicine name is required.',

            'name.unique' => 'This medicine already exists.',

            'medicine_category_id.required' => 'Please select a medicine category.',

            'medicine_category_id.exists' => 'The selected medicine category is invalid.',

            'medicine_type_id.required' => 'Please select a medicine type.',

            'medicine_type_id.exists' => 'The selected medicine type is invalid.',

            'manufacturer_id.required' => 'Please select a manufacturer.',

            'manufacturer_id.exists' => 'The selected manufacturer is invalid.',

            'unit_id.required' => 'Please select a unit.',

            'unit_id.exists' => 'The selected unit is invalid.',

            'purchase_price.required' => 'Purchase price is required.',

            'purchase_price.numeric' => 'Purchase price must be numeric.',

            'selling_price.required' => 'Selling price is required.',

            'selling_price.gte' => 'Selling price cannot be less than purchase price.',

            'image.image' => 'Please upload a valid image.',

            'image.max' => 'Image size may not exceed 2 MB.',

        ];
    }

    /**
     * Custom Attribute Names
     */
    public function attributes(): array
    {
        return [

            'medicine_category_id' => 'medicine category',

            'medicine_type_id' => 'medicine type',

            'manufacturer_id' => 'manufacturer',

            'unit_id' => 'unit',

        ];
    }
}
