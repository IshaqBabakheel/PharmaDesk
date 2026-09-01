<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    /**
     * Create a new purchase.
     */
    public function store(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            /*
            |--------------------------------------------------------------------------
            | Generate Reference Number
            |--------------------------------------------------------------------------
            */
            $referenceNumber = $data['reference_number'] ?? null;

            if (empty($referenceNumber)) {
                $referenceNumber = $this->generateReferenceNumber();
            }

            /*
            |--------------------------------------------------------------------------
            | Create Purchase
            |--------------------------------------------------------------------------
            */
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'invoice_number' => $data['invoice_number'] ?? null,
                'reference_number' => $referenceNumber,
                'purchase_date' => $data['purchase_date'],
                'subtotal' => $data['subtotal'] ?? 0,
                'discount_type' => $data['discount_type'] ?? 'Fixed',
                'discount' => $data['discount'] ?? 0,
                'tax_type' => $data['tax_type'] ?? 'Fixed',
                'tax' => $data['tax'] ?? 0,
                'shipping' => $data['shipping'] ?? 0,
                'other_charges' => $data['other_charges'] ?? 0,
                'grand_total' => $data['grand_total'] ?? 0,
                'paid_amount' => $data['paid_amount'] ?? 0,
                'due_amount' => $data['due_amount'] ?? 0,
                'payment_status' => $data['payment_status'],
                'status' => $data['status'],
                'stock_applied' => false,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Purchase Items
            |--------------------------------------------------------------------------
            */
            foreach ($data['items'] as $item) {
                $this->createPurchaseItem(
                    $purchase,
                    $item
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Apply Stock
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | Stock is changed ONLY when the purchase is created
            | directly as completed.
            |
            */
            if ($purchase->isCompleted()) {
                $this->applyStock($purchase);
            }
            // dd($purchase);
            return $purchase->load([
                'supplier',
                'items.medicine',
            ]);
        });
    }

    public function update(Purchase $purchase, array $data): Purchase 
    {

        return DB::transaction(function () use ($purchase, $data) {

            /*
            |--------------------------------------------------------------------------
            | Restore Previously Applied Stock
            |--------------------------------------------------------------------------
            |
            | Only restore stock if this purchase actually affected inventory.
            |
            */

            if ($purchase->isStockApplied()) {

                $this->restoreStock($purchase);
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Existing Purchase Items
            |--------------------------------------------------------------------------
            */

            $purchase->items()->delete();


            /*
            |--------------------------------------------------------------------------
            | Update Purchase
            |--------------------------------------------------------------------------
            */

            $purchase->update([
                'supplier_id' => $data['supplier_id'],
                'invoice_number' => $data['invoice_number'] ?? null,
                'reference_number' => $data['reference_number'],
                'purchase_date' => $data['purchase_date'],
                'subtotal' => $data['subtotal'],
                'discount_type' => $data['discount_type'],
                'discount' => $data['discount'] ?? 0,
                'tax_type' => $data['tax_type'],
                'tax' => $data['tax'] ?? 0,
                'shipping' => $data['shipping'] ?? 0,
                'other_charges' => $data['other_charges'] ?? 0,
                'grand_total' => $data['grand_total'],
                'paid_amount' => $data['paid_amount'] ?? 0,
                'due_amount' => $data['due_amount'] ?? 0,
                'payment_status' => $data['payment_status'],
                'status' => $data['status'],
                /*
                |--------------------------------------------------------------------------
                | Stock Is Currently Not Applied
                |--------------------------------------------------------------------------
                */
                'stock_applied' => false,
                'notes' => $data['notes'] ?? null,
                'updated_by' => Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create New Purchase Items
            |--------------------------------------------------------------------------
            */
            foreach ($data['items'] as $item) {

                $this->createPurchaseItem(
                    $purchase,
                    $item
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Apply New Stock Only If Purchase Is Completed
            |--------------------------------------------------------------------------
            */

            $purchase->refresh();

            if ($purchase->isCompleted()) {

                $this->applyStock($purchase);
            }


            return $purchase->fresh([
                'items',
                'supplier',
            ]);
        });
    }

    /**
     * Create a purchase item.
     */
    private function createPurchaseItem(Purchase $purchase, array $item): PurchaseItem
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Batch Number
        |--------------------------------------------------------------------------
        */
        $batchNumber = $item['batch_number'] ?? null;
        if (empty($batchNumber)) {
            $batchNumber = $this->generateBatchNumber();
        }

        /*
        |--------------------------------------------------------------------------
        | Create Item
        |--------------------------------------------------------------------------
        */
        return PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'source' => 'purchase',
            'medicine_id' => $item['medicine_id'],
            'batch_number' => $batchNumber,
            'expiry_date' => $item['expiry_date'] ?? null,
            'quantity' => $item['quantity'],
            'free_quantity' => $item['free_quantity'] ?? 0,
            'purchase_price' => $item['purchase_price'],
            'selling_price' => $item['selling_price'],
            'discount' => $item['discount'] ?? 0,
            'tax' => $item['tax'] ?? 0,
            'total' => $item['total'],
        ]);
    }

    /**
     * Apply purchase stock.
     */
    public function applyStock(Purchase $purchase): void
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Stock Application
        |--------------------------------------------------------------------------
        */
        if ($purchase->stock_applied) {
            return;
        }

        if ($purchase->isCancelled()) {

            throw ValidationException::withMessages([
                'purchase' => 'Cancelled purchases cannot affect stock.',
            ]);
        }

        $purchase->loadMissing('items');

        foreach ($purchase->items as $item) {
            $medicine = Medicine::findOrFail(
                $item->medicine_id
            );

            $medicine->increment(
                'current_stock',
                $item->quantity + $item->free_quantity
            );

            /*
            |--------------------------------------------------------------------------
            | Update Latest Medicine Prices
            |--------------------------------------------------------------------------
            */
            $medicine->update([
                'purchase_price' => $item->purchase_price,
                'selling_price' => $item->selling_price,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Mark Stock As Applied
        |--------------------------------------------------------------------------
        */
        $purchase->update([
            'stock_applied' => true,
        ]);
    }

    /**
     * Restore stock previously applied by a purchase.
     */
    public function restoreStock(Purchase $purchase): void
    {
        /*
        |--------------------------------------------------------------------------
        | Nothing To Restore
        |--------------------------------------------------------------------------
        */
        if (!$purchase->stock_applied) {
            return;
        }

        $purchase->loadMissing('items');

        foreach ($purchase->items as $item) {
            $medicine = Medicine::findOrFail(
                $item->medicine_id
            );
            $medicine->decrement(
                'current_stock',
                $item->quantity + $item->free_quantity
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Mark Stock As Not Applied
        |--------------------------------------------------------------------------
        */
        $purchase->update([
            'stock_applied' => false,
        ]);
    }

    /**
     * Change paid amount.
     */
    public function updatePayment(Purchase $purchase, float $paidAmount): Purchase
    {
        return DB::transaction(function () use ($purchase, $paidAmount) {

            $payment = $this->calculatePaymentValues(
                (float) $purchase->grand_total,
                $paidAmount
            );

            $payment['updated_by'] = Auth::id();

            $purchase->update($payment);

            return $purchase->fresh();
        });
    }


    /**
     * Complete a draft purchase.
     */
    public function complete(Purchase $purchase): Purchase
    {
        return DB::transaction(function () use ($purchase) {

            $purchase->load('items');

            if ($purchase->isCancelled()) {
                throw ValidationException::withMessages([
                    'status' => 'Cancelled purchases cannot be completed.',
                ]);
            }

            if ($purchase->isCompleted()) {
                throw ValidationException::withMessages([
                    'status' => 'This purchase is already completed.',
                ]);
            }

            $purchase->update([
                'status' => 'completed',
            ]);

            $this->applyStock($purchase);

            return $purchase->fresh('items');
        });
    }

    /**
     * Cancel a purchase.
     */
    public function cancel(Purchase $purchase): Purchase
    {
        return DB::transaction(function () use ($purchase) {

            $purchase->load('items');

            if ($purchase->isCancelled()) {
                throw ValidationException::withMessages([
                    'status' => 'This purchase is already cancelled.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Restore Stock If It Was Applied
            |--------------------------------------------------------------------------
            */

            if ($purchase->isStockApplied()) {
                $this->restoreStock($purchase);
            }

            $purchase->update([
                'status' => 'cancelled',
            ]);

            return $purchase->fresh('items');
        });
    }


    /**
     * Calculate purchase payment values.
     */
    protected function calculatePaymentValues(float $grandTotal, float $paidAmount): array
    {
        $grandTotal = round(max($grandTotal, 0), 2);
        $paidAmount = round(max($paidAmount, 0), 2);

        if ($paidAmount > $grandTotal) {
            throw ValidationException::withMessages([
                'paid_amount' => ['Paid amount cannot be greater than the grand total.']
            ]);
        }

        return [
            'paid_amount' => $paidAmount,
            'due_amount' => $this->calculateDue($grandTotal, $paidAmount),
            'payment_status' => $this->calculatePaymentStatus($grandTotal, $paidAmount),
        ];
    }

    /**
     * Calculate due amount.
     */
    protected function calculateDue(float $grandTotal, float $paidAmount): float
    {
        return round(max($grandTotal - $paidAmount, 0), 2);
    }

    /**
     * Calculate payment status.
     */
    protected function calculatePaymentStatus(float $grandTotal, float $paidAmount): string
    {
        if ($paidAmount == 0) {
            return 'Un Paid';
        }

        if ($paidAmount < $grandTotal) {
            return 'Partially Paid';
        }

        return 'Paid';
    }

    /**
     * Generate reference number.
     */
    public function generateReferenceNumber(): string
    {
        $prefix = 'REF-' . now()->format('Ymd') . '-';

        $lastPurchase = Purchase::where('reference_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;

        if ($lastPurchase) {
            $last = explode('-', $lastPurchase->reference_number);
            $number = (int) end($last) + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate batch number.
     */
    protected function generateBatchNumber(): string
    {
        do {

            $batchNumber =
                'BT'
                . strtoupper(
                    substr(
                        str_shuffle(
                            'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
                        ),
                        0,
                        6
                    )
                );
        } while (
            PurchaseItem::where(
                'batch_number',
                $batchNumber
            )->exists()
        );

        return $batchNumber;
    }

}
