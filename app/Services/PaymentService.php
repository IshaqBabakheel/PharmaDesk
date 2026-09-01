<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sale;
use app\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function store(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $this->validatePaymentData($data);

            $payment = Payment::create([
                'payment_number' => null,
                'type' => $data['type'],
                'sale_id' => $data['sale_id'] ?? null,
                'purchase_id' => $data['purchase_id'] ?? null,
                'expense_id' => $data['expense_id'] ?? null,
                'customer_id' => $data['customer_id'] ?? null,
                'supplier_id' => $data['supplier_id'] ?? null,
                'amount' => $data['amount'],
                'method' => $data['method'],
                'payment_date' => $data['payment_date'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $this->syncReference($payment);

            return $payment->fresh([
                'sale',
                'purchase',
                'customer',
                'supplier',
                'creator',
            ]);
        });
    }
    
    public function update(Payment $payment, array $data): Payment 
    {
        return DB::transaction(function () use ($payment, $data) {
            $oldSaleId = $payment->sale_id;
            $oldPurchaseId = $payment->purchase_id;
            $oldExpenseId = $payment->expense_id;

            $this->validatePaymentData($data, $payment);

            $payment->update([
                'type' => $data['type'],
                'sale_id' => $data['sale_id'] ?? null,
                'purchase_id' => $data['purchase_id'] ?? null,
                'expense_id' => $data['expense_id'] ?? null,
                'customer_id' => $data['customer_id'] ?? null,
                'supplier_id' => $data['supplier_id'] ?? null,
                'amount' => $data['amount'],
                'method' => $data['method'],
                'payment_date' => $data['payment_date'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'updated_by' => Auth::id(),
            ]);

            $this->recalculateReference(
                $oldSaleId,
                $oldPurchaseId,
                $oldExpenseId
            );

            $this->syncReference($payment);

            return $payment->fresh([
                'sale',
                'purchase',
                'expense',
                'customer',
                'supplier',
                'updater',
            ]);
        });
    }

    public function delete(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->update([
                'deleted_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $payment->delete();

            $this->recalculateReference(
                $payment->sale_id,
                $payment->purchase_id,
                $payment->expense_id
            );
        });
    }

    public function restore(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {
            $payment->restore();

            $payment->update([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            $this->syncReference($payment);

            return $payment->fresh([
                'sale',
                'purchase',
                'expense',
                'customer',
                'supplier',
            ]);
        });
    }

    public function forceDelete(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $saleId = $payment->sale_id;
            $purchaseId = $payment->purchase_id;

            $payment->forceDelete();

            $this->recalculateReference(
                $saleId,
                $purchaseId,
                $payment->expense_id
            );
        });
    }

    public function getSalePaidAmount(int $saleId): float
    {
        return (float) Payment::query()
            ->where('sale_id', $saleId)
            ->where('type', 'receipt')
            ->whereNull('deleted_at')
            ->sum('amount');
    }

    public function getPurchasePaidAmount(int $purchaseId): float
    {
        return (float) Payment::query()
            ->where('purchase_id', $purchaseId)
            ->where('type', 'payment')
            ->whereNull('deleted_at')
            ->sum('amount');
    }

    public function getExpensePaidAmount(int $expenseId): float
    {
        return (float) Payment::query()
            ->where('expense_id', $expenseId)
            ->where('type', 'payment')
            ->whereNull('deleted_at')
            ->sum('amount');
    }

    protected function validatePaymentData(array $data, ?Payment $currentPayment = null): void 
    {
        $type = $data['type'];

        if ($type === 'receipt') {
            if (empty($data['sale_id'])) {
                throw ValidationException::withMessages([
                    'sale_id' => [
                        'A sale is required for a receipt.'
                    ],
                ]);
            }

            $sale = Sale::find($data['sale_id']);

            if (!$sale) {
                throw ValidationException::withMessages([
                    'sale_id' => [
                        'The selected sale does not exist.'
                    ],
                ]);
            }

            if ($sale->isCancelled()) {
                throw ValidationException::withMessages([
                    'sale_id' => [
                        'Payment cannot be added to a cancelled sale.'
                    ],
                ]);
            }

            $paid = $this->getSalePaidAmount(
                $sale->id
            );

            if ($currentPayment?->sale_id === $sale->id) {
                $paid -= (float) $currentPayment->amount;
            }

            $remaining =
                max(
                    (float) $sale->grand_total - $paid,
                    0
                );

            if ((float) $data['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => [
                        "Only {$remaining} is outstanding on this sale."
                    ],
                ]);
            }

            if (
                !empty($data['customer_id']) &&
                $sale->customer_id &&
                (int) $data['customer_id'] !== (int) $sale->customer_id
            ) {
                throw ValidationException::withMessages([
                    'customer_id' => [
                        'The selected customer does not belong to this sale.'
                    ],
                ]);
            }

            return;
        }

        if ($type === 'payment' && !empty($data['expense_id'])) {

            $expense = Expense::find($data['expense_id']);

            if (!$expense) {
                throw ValidationException::withMessages([
                    'expense_id' => [
                        'The selected expense does not exist.'
                    ],
                ]);
            }

            if ($expense->isCancelled()) {
                throw ValidationException::withMessages([
                    'expense_id' => [
                        'Payment cannot be added to a cancelled expense.'
                    ],
                ]);
            }

            $paid = $this->getExpensePaidAmount(
                $expense->id
            );

            if ($currentPayment?->expense_id === $expense->id) {
                $paid -= (float) $currentPayment->amount;
            }

            $remaining = max(
                (float) $expense->amount - $paid,
                0
            );

            if ((float) $data['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => [
                        "Only {$remaining} is outstanding on this expense."
                    ],
                ]);
            }

            return;
        }

        if (empty($data['purchase_id'])) {
            throw ValidationException::withMessages([
                'purchase_id' => [
                    'A purchase is required for a supplier payment.'
                ],
            ]);
        }

        $purchase = Purchase::find(
            $data['purchase_id']
        );

        if (!$purchase) {
            throw ValidationException::withMessages([
                'purchase_id' => [
                    'The selected purchase does not exist.'
                ],
            ]);
        }

        if ($purchase->isCancelled()) {
            throw ValidationException::withMessages([
                'purchase_id' => [
                    'Payment cannot be added to a cancelled purchase.'
                ],
            ]);
        }

        $paid = $this->getPurchasePaidAmount(
            $purchase->id
        );

        if ($currentPayment?->purchase_id === $purchase->id) {
            $paid -= (float) $currentPayment->amount;
        }

        $remaining =
            max(
                (float) $purchase->grand_total - $paid,
                0
            );

        if ((float) $data['amount'] > $remaining) {
            throw ValidationException::withMessages([
                'amount' => [
                    "Only {$remaining} is outstanding on this purchase."
                ],
            ]);
        }

        if (
            !empty($data['supplier_id']) &&
            $purchase->supplier_id &&
            (int) $data['supplier_id'] !== (int) $purchase->supplier_id
        ) {
            throw ValidationException::withMessages([
                'supplier_id' => [
                    'The selected supplier does not belong to this purchase.'
                ],
            ]);
        }
    }

    protected function syncReference(Payment $payment): void 
    {
        $this->recalculateReference(
            $payment->sale_id,
            $payment->purchase_id,
            $payment->expense_id
        );
    }

    protected function recalculateReference(?int $saleId, ?int $purchaseId, ?int $expenseId = null): void 
    {
        if ($saleId) {
            $sale = Sale::find($saleId);

            if ($sale) {
                $paid = $this->getSalePaidAmount(
                    $sale->id
                );

                $due =
                    max(
                        (float) $sale->grand_total - $paid,
                        0
                    );

                $sale->update([
                    'paid_amount' => $paid,
                    'due_amount' => $due,
                    'payment_status' =>
                    $this->calculatePaymentStatus(
                        $sale->grand_total,
                        $paid
                    ),
                ]);
            }
        }

        if ($purchaseId) {
            $purchase = Purchase::find($purchaseId);

            if ($purchase) {
                $paid = $this->getPurchasePaidAmount(
                    $purchase->id
                );

                $due =
                    max(
                        (float) $purchase->grand_total - $paid,
                        0
                    );

                $purchase->update([
                    'paid_amount' => $paid,
                    'due_amount' => $due,
                    'payment_status' =>
                    $this->calculatePurchasePaymentStatus(
                        $purchase->grand_total,
                        $paid
                    ),
                ]);
            }
        }

        if ($expenseId) {

            $expense = Expense::find($expenseId);

            if ($expense) {

                $paid = $this->getExpensePaidAmount(
                    $expense->id
                );

                $due = max(
                    (float) $expense->amount - $paid,
                    0
                );

                $expense->update([
                    'paid_amount' => $paid,
                    'due_amount' => $due,
                    'payment_status' =>
                        $this->calculateExpensePaymentStatus(
                            $expense->amount,
                            $paid
                        ),
                    'updated_by' => Auth::id(),
                ]);
            }
        }
    }

    protected function calculatePaymentStatus(float $total, float $paid): string 
    {
        if ($paid <= 0) {
            return 'due';
        }

        if ($paid >= $total) {
            return 'paid';
        }

        return 'partial';
    }

    protected function calculatePurchasePaymentStatus(float $total, float $paid): string 
    {
        if ($paid <= 0) {
            return 'Unpaid';
        }

        if ($paid >= $total) {
            return 'Paid';
        }

        return 'Partially Paid';
    }

    protected function calculateExpensePaymentStatus(float $total, float $paid): string 
    {
        if ($paid <= 0) {
            return 'Unpaid';
        }

        if ($paid >= $total) {
            return 'Paid';
        }

        return 'Partially Paid';
    }
}
