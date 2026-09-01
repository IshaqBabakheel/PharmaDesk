<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePosCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medicine_id' => [
                'required',
                'integer',
                'exists:medicines,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'free_quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}