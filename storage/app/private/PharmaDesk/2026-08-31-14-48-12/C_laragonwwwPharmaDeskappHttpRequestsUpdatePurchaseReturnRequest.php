<?php

namespace App\Http\Requests;

class UpdatePurchaseReturnRequest extends StorePurchaseReturnRequest
{
    /**
     * Authorize the request.
     */
    public function authorize(): bool
    {
        return true;
    }
}