<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\ReceiptService;

class ReceiptController extends Controller
{
    public function __construct(
        protected ReceiptService $receiptService
    ) {}

    public function a4(Sale $sale)
    {
        $this->authorize('view', $sale);

        $data = $this->receiptService->getSaleData($sale);

        return view('receipts.a4', $data);
    }

    public function thermal(Sale $sale)
    {
        $this->authorize('view', $sale);

        $data = $this->receiptService->getSaleData($sale);

        return view('receipts.thermal', $data);
    }
}