<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', ExpenseCategory::class);

        return view('expense_categories.index');
    }

    public function datatable(Request $request)
    {
        $this->authorize('viewAny', ExpenseCategory::class);

        $filter = $request->get(
            'filter',
            'active'
        );

        $query = ExpenseCategory::query()
            ->select('expense_categories.*');

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()

            ->editColumn(
                'status',
                function (ExpenseCategory $row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                }
            )


            ->editColumn(
                'description',
                fn (ExpenseCategory $row) =>
                    $row->description ?: '-'
            )

            ->addColumn(
                'action',
                function (ExpenseCategory $row) {
                    $isTrashed = $row->trashed();
                    return view('components.action-dropdown',
                        [
                            'row' => $row,
                            'module' => 'expense-categories',
                            'show' => false,
                            'edit' => !$isTrashed,
                            'delete' => !$isTrashed,
                            'restore' => $isTrashed,
                            'forceDelete' => $isTrashed,
                        ]
                    );
                }
            )

            ->rawColumns([
                'status',
                'action',
            ])

            ->make(true);
    }

    public function create()
    {
        $this->authorize('create', ExpenseCategory::class);

        return view('expense_categories.create');
    }

    public function store(
        StoreExpenseCategoryRequest $request
    ) {
        $this->authorize('create', ExpenseCategory::class);

        ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->boolean('status', true),
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('expense-categories.index')
            ->with(
                'success',
                'Expense category created successfully.'
            );
    }

    public function edit(
        ExpenseCategory $expenseCategory
    ) {
        $this->authorize(
            'update',
            $expenseCategory
        );

        return view(
            'expense_categories.edit',
            compact('expenseCategory')
        );
    }

    public function update(
        UpdateExpenseCategoryRequest $request,
        ExpenseCategory $expenseCategory
    ) {
        $this->authorize(
            'update',
            $expenseCategory
        );

        $expenseCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->boolean('status'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('expense-categories.index')
            ->with(
                'success',
                'Expense category updated successfully.'
            );
    }

    public function destroy(
        ExpenseCategory $expenseCategory
    ) {
        $this->authorize(
            'delete',
            $expenseCategory
        );

        if ($expenseCategory->expenses()->exists()) {
            throw ValidationException::withMessages([
                'category' => [
                    'This expense category cannot be deleted because it is being used by expenses.'
                ],
            ]);
        }

        $expenseCategory->update([
            'deleted_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $expenseCategory->delete();

        return back()->with(
            'success',
            'Expense category deleted successfully.'
        );
    }

    public function restore($id)
    {
        $expenseCategory =
            ExpenseCategory::onlyTrashed()
                ->findOrFail($id);

        $this->authorize(
            'restore',
            $expenseCategory
        );

        $expenseCategory->restore();

        $expenseCategory->update([
            'deleted_by' => null,
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Expense category restored successfully.'
        );
    }

    public function forceDelete($id)
    {
        $expenseCategory =
            ExpenseCategory::onlyTrashed()
                ->findOrFail($id);

        $this->authorize(
            'forceDelete',
            $expenseCategory
        );

        if ($expenseCategory->expenses()->withTrashed()->exists()) {
            throw ValidationException::withMessages([
                'category' => [
                    'This expense category cannot be permanently deleted because it has expense history.'
                ],
            ]);
        }

        $expenseCategory->forceDelete();

        return back()->with(
            'success',
            'Expense category permanently deleted.'
        );
    }

    // public function datatable(Request $request)
    // {
    //     $this->authorize(
    //         'viewAny',
    //         ExpenseCategory::class
    //     );

    //     $filter =
    //         $request->get('filter', 'active');

    //     $query = ExpenseCategory::query()
    //         ->withCount('expenses')
    //         ->select('expense_categories.*');

    //     if ($filter === 'trashed') {
    //         $query->onlyTrashed();
    //     } elseif ($filter === 'all') {
    //         $query->withTrashed();
    //     } else {
    //         $query->whereNull('deleted_at');
    //     }

    //     return DataTables::eloquent($query)
    //         ->addIndexColumn()
    //         ->editColumn(
    //             'status',
    //             function (ExpenseCategory $row) {
    //                 return $row->status
    //                     ? '<span class="badge bg-success">Active</span>'
    //                     : '<span class="badge bg-secondary">Inactive</span>';
    //             }
    //         )
    //         ->addColumn(
    //             'expenses_count',
    //             fn (ExpenseCategory $row) =>
    //                 $row->expenses_count
    //         )
    //         ->addColumn(
    //             'action',
    //             function (ExpenseCategory $row) {

    //                 $isTrashed =
    //                     $row->trashed();

    //                 return view(
    //                     'components.action-dropdown',
    //                     [
    //                         'row' => $row,
    //                         'module' => 'expense-categories',
    //                         'show' => false,
    //                         'edit' => !$isTrashed,
    //                         'delete' => !$isTrashed,
    //                         'restore' => $isTrashed,
    //                         'forceDelete' => $isTrashed,
    //                     ]
    //                 );
    //             }
    //         )
    //         ->rawColumns([
    //             'status',
    //             'action',
    //         ])
    //         ->make(true);
    // }
}