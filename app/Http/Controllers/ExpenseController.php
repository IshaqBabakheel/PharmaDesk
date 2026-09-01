<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function __construct(
        protected ExpenseService $expenseService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Expense::class);

        $categories = ExpenseCategory::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view(
            'expenses.index',
            compact('categories')
        );
    }

    public function create()
    {
        $this->authorize('create', Expense::class);

        $categories = ExpenseCategory::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'expenses.create',
            compact('categories')
        );
    }

    public function store(StoreExpenseRequest $request)
    {
        $this->authorize('create', Expense::class);

        try {
            $expense = $this->expenseService->store(
                $request->validated()
            );

            return redirect()
                ->route('expenses.show', $expense)
                ->with(
                    'success',
                    'Expense created successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create expense.'
                );
        }
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);

        $expense->load([
            'category',
            'payments',
            'creator',
            'updater',
            'deleter',
        ]);

        return view(
            'expenses.show',
            compact('expense')
        );
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $categories = ExpenseCategory::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'expenses.edit',
            compact(
                'expense',
                'categories'
            )
        );
    }

    public function update(UpdateExpenseRequest $request, Expense $expense) 
    {
        $this->authorize('update', $expense);

        try {
            $this->expenseService->update(
                $expense,
                $request->validated()
            );

            return redirect()
                ->route('expenses.show', $expense)
                ->with(
                    'success',
                    'Expense updated successfully.'
                );
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update expense.'
                );
        }
    }

    public function complete(Expense $expense)
    {
        $this->authorize('complete', $expense);

        try {
            $this->expenseService->complete($expense);

            return back()->with(
                'success',
                'Expense completed successfully.'
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to complete expense.'
            );
        }
    }

    public function cancel(Expense $expense)
    {
        $this->authorize('cancel', $expense);

        try {
            $this->expenseService->cancel($expense);

            return back()->with(
                'success',
                'Expense cancelled successfully.'
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to cancel expense.'
            );
        }
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        try {
            $this->expenseService->delete($expense);

            return redirect()
                ->route('expenses.index')
                ->with(
                    'success',
                    'Expense deleted successfully.'
                );
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to delete expense.'
            );
        }
    }

    public function restore(int $id)
    {
        $expense = Expense::onlyTrashed()
            ->findOrFail($id);

        $this->authorize('restore', $expense);

        try {
            $this->expenseService->restore($expense);

            return back()->with(
                'success',
                'Expense restored successfully.'
            );
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to restore expense.'
            );
        }
    }

    public function forceDelete(int $id)
    {
        $expense = Expense::onlyTrashed()
            ->findOrFail($id);

        $this->authorize(
            'forceDelete',
            $expense
        );

        try {
            $this->expenseService->forceDelete($expense);

            return back()->with(
                'success',
                'Expense permanently deleted.'
            );
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to permanently delete expense.'
            );
        }
    }

    public function datatable(Request $request)
    {
        $this->authorize('viewAny', Expense::class);

        $query = Expense::query()
            ->with([
                'category',
                'creator',
                'deleter',
            ])
            ->select('expenses.*');

        $filter =
            $request->get(
                'filter',
                'active'
            );

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('expense_category_id')) {
            $query->where(
                'expense_category_id',
                $request->expense_category_id
            );
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn(
                'category',
                fn (Expense $row) =>
                    e($row->category?->name ?? '-')
            )
            ->addColumn(
                'amount',
                fn (Expense $row) =>
                    number_format(
                        (float) $row->amount,
                        2
                    )
            )
            ->addColumn(
                'paid_amount',
                fn (Expense $row) =>
                    number_format(
                        (float) $row->paid_amount,
                        2
                    )
            )
            ->addColumn(
                'due_amount',
                fn (Expense $row) =>
                    number_format(
                        (float) $row->due_amount,
                        2
                    )
            )
            ->editColumn(
                'expense_date',
                fn (Expense $row) =>
                    $row->expense_date?->format('d M Y')
            )
            ->addColumn(
                'payment_status',
                fn (Expense $row) =>
                    $row->payment_status
            )
            ->addColumn(
                'action',
                function (Expense $row) {

                    $isTrashed = $row->trashed();

                    return view(
                        'components.action-dropdown',
                        [
                            'row' => $row,
                            'module' => 'expenses',
                            'show' => true,
                            'edit' =>
                                !$isTrashed
                                && !$row->isCancelled(),

                            'complete' =>
                                !$isTrashed
                                && $row->isDraft(),

                            'cancel' =>
                                !$isTrashed
                                && !$row->isCancelled(),

                            'delete' =>
                                !$isTrashed,

                            'restore' =>
                                $isTrashed,

                            'forceDelete' =>
                                $isTrashed,
                        ]
                    );
                }
            )
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function statistics(Request $request)
    {
        $this->authorize('viewAny', Expense::class);

        return response()->json([
            'success' => true,
            'statistics' =>
                $this->expenseService->getStatistics(
                    $request->only([
                        'filter',
                        'status',
                        'expense_category_id',
                    ])
                ),
        ]);
    }
}