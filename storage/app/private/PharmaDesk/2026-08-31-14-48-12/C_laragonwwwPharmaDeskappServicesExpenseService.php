<?php

namespace App\Services;

use App\Models\Expense;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExpenseService
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function store(array $data): Expense
    {
        return DB::transaction(function () use ($data) {

            $this->validateAmount(
                $data['amount'],
                $data['paid_amount'] ?? 0
            );

            $paidAmount =
                $data['status'] === 'Completed'
                    ? (float) ($data['paid_amount'] ?? 0)
                    : 0;

            $expense = Expense::create([
                'expense_number' => null,
                'expense_category_id' => $data['expense_category_id'],
                'expense_date' => $data['expense_date'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'amount' => $data['amount'],
                'paid_amount' => 0,
                'due_amount' => (float) $data['amount'],
                'payment_status' => 'Unpaid',
                'status' => $data['status'],
                'payment_method' => $data['payment_method'],
                'reference_number' => null,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            $expense->update([
                'reference_number' =>
                    $this->generateReferenceNumber($expense),
            ]);

            if (
                $expense->isCompleted()
                && $paidAmount > 0
            ) {
                $this->paymentService->store([
                    'type' => 'payment',
                    'sale_id' => null,
                    'purchase_id' => null,
                    'expense_id' => $expense->id,
                    'customer_id' => null,
                    'supplier_id' => null,
                    'amount' => $paidAmount,
                    'method' => $data['payment_method'],
                    'payment_date' => $data['expense_date'],
                    'reference_number' => $expense->reference_number,
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            return $expense->fresh([
                'category',
                'creator',
                'payments',
            ]);
        });
    }

    public function update(Expense $expense, array $data): Expense 
    {
        return DB::transaction(function () use ($expense, $data) 
        {
            if ($expense->isCancelled()) {
                throw ValidationException::withMessages([
                    'status' => [
                        'Cancelled expenses cannot be edited.'
                    ],
                ]);
            }

            $this->validateAmount(
                $data['amount'],
                $data['paid_amount'] ?? 0
            );

            $paidAmount =
                $data['status'] === 'Completed'
                    ? (float) ($data['paid_amount'] ?? 0)
                    : 0;

            $expense->update([
                'expense_category_id' =>
                    $data['expense_category_id'],

                'expense_date' =>
                    $data['expense_date'],

                'title' =>
                    $data['title'],

                'description' =>
                    $data['description'] ?? null,

                'amount' =>
                    $data['amount'],

                'paid_amount' =>
                    $paidAmount,

                'due_amount' =>
                    max(
                        (float) $data['amount'] -
                        $paidAmount,
                        0
                    ),

                'payment_status' =>
                    $this->paymentStatus(
                        (float) $data['amount'],
                        $paidAmount
                    ),

                'status' =>
                    $data['status'],

                'payment_method' =>
                    $data['payment_method'],

                'reference_number' =>
                    $data['reference_number'] ?? null,

                'notes' =>
                    $data['notes'] ?? null,

                'updated_by' =>
                    Auth::id(),
            ]);

            return $expense->fresh([
                'category',
                'updater',
                'payments',
            ]);
        });
    }

    public function complete(Expense $expense): Expense
    {
        return DB::transaction(function () use ($expense) {
            if (!$expense->isDraft()) {
                throw ValidationException::withMessages([
                    'status' => [
                        'Only draft expenses can be completed.'
                    ],
                ]);
            }

            $expense->update([
                'status' => 'Completed',
                'updated_by' => Auth::id(),
            ]);

            return $expense->fresh([
                'category',
                'payments',
            ]);
        });
    }

    public function cancel(Expense $expense): Expense
    {
        return DB::transaction(function () use ($expense) {
            if ($expense->isCancelled()) {
                throw ValidationException::withMessages([
                    'status' => [
                        'This expense is already cancelled.'
                    ],
                ]);
            }

            $expense->update([
                'status' => 'Cancelled',
                'updated_by' => Auth::id(),
            ]);

            return $expense->fresh([
                'category',
            ]);
        });
    }

    public function delete(Expense $expense): void
    {
        DB::transaction(function () use ($expense) {
            $expense->update([
                'deleted_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $expense->delete();
        });
    }

    public function restore(Expense $expense): Expense
    {
        return DB::transaction(function () use ($expense) {
            $expense->restore();

            $expense->update([
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            return $expense->fresh([
                'category',
            ]);
        });
    }

    public function forceDelete(Expense $expense): void
    {
        DB::transaction(function () use ($expense) {
            $expense->forceDelete();
        });
    }

    protected function validateAmount(float|int|string $amount, float|int|string $paidAmount): void 
    {
        if ((float) $paidAmount > (float) $amount) {
            throw ValidationException::withMessages([
                'paid_amount' => [
                    'Paid amount cannot be greater than the expense amount.'
                ],
            ]);
        }
    }

    protected function paymentStatus(float $amount, float $paidAmount): string 
    {
        if ($paidAmount <= 0) {
            return 'Unpaid';
        }

        if ($paidAmount >= $amount) {
            return 'Paid';
        }

        return 'Partially Paid';
    }

    protected function generateReferenceNumber(Expense $expense): string 
    {
        return 'EXREF-' . str_pad($expense->id, 6, '0', STR_PAD_LEFT);
    }

    public function getStatistics(array $filters = []): array
    {
        $filter = $filters['filter'] ?? 'active';

        $query = match ($filter) {
            'trashed' => Expense::onlyTrashed(),
            'all' => Expense::withTrashed(),
            default => Expense::query(),
        };

        if (!empty($filters['status'])) {
            $query->where(
                'status',
                $filters['status']
            );
        }

        if (!empty($filters['expense_category_id'])) {
            $query->where(
                'expense_category_id',
                $filters['expense_category_id']
            );
        }

        return [
            'count' => $query->count(),
            'amount' => (float) $query->sum('amount'),
            'paid_amount' => (float) $query->sum('paid_amount'),
            'due_amount' => (float) $query->sum('due_amount'),
        ];
    }
}