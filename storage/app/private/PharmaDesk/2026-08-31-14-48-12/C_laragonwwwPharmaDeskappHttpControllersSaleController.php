<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
        // $this->authorizeResource(Sale::class, 'sales');
    }

    /**
     * Display a listing of sales.
     */
    public function index()
    {
        $totalSales = Sale::all()->count();
        $completedSales = Sale::where('status', 'completed')->count();
        $pendingDues = Sale::where('payment_status', 'due')->count();
        $cancelledSales = Sale::where('status', 'cancelled')->count();
        return view('sales.index', compact('totalSales', 'completedSales', 'pendingDues','cancelledSales'));
    }

    /**
     * Return sales for DataTables.
     */
    public function datatable(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = Sale::with(['customer', 'creator'])->select('sales.*');

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }
        
        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('customer', function (Sale $row) {
                return $row->customer?->name ?? 'Walk-in Customer';
            })
            ->editColumn('invoice_number', function (Sale $row) {
                return '<strong>' . e($row->invoice_number) . '</strong>';
            })
            ->editColumn('sale_date', function (Sale $row) {
                return $row->sale_date?->format('d M Y') ?? '-';
            })
            ->addColumn('total', function (Sale $row) {
                return number_format((float) $row->grand_total, 2);
            })
            ->addColumn('paid', function (Sale $row) {
                return number_format((float) $row->paid_amount, 2);
            })
            ->addColumn('due', function (Sale $row) {
                return number_format((float) $row->due_amount, 2);
            })
            ->addColumn('payment_status', function (Sale $row) {
                return $row->payment_status_badge;
            })
            ->addColumn('status', function (Sale $row) {
                return $row->status_badge;
            })
            ->addColumn('created_by', function (Sale $row) {
                if ($row->trashed()){
                    return $row->deleter?->name ?? 'System';
                }
                return $row->creator?->name ?? '-';
            })
            ->addColumn('action', function (Sale $row) {
                return view('components.action-dropdown', [
                    'row' => $row,
                    'module' => 'sales',
                    'show' => true,
                    'edit' => $row->status !== 'cancelled',
                    'complete' => $row->status === 'draft',
                    'cancel' => $row->status !== 'cancelled',
                    'updatePayment' => $row->status !== 'cancelled',
                    'saleReturn' => $row->status === 'completed' && !$row->trashed(),
                    'delete' => !$row->trashed(),
                    'restore' => request('filter') === 'trashed',
                    'forceDelete' => request('filter') === 'trashed',
                    'receipt' => !$row->trashed(),
                ]);
            })
            
            ->rawColumns([
                'invoice_number',
                'payment_status',
                'status',
                'action',
            ])
            ->make(true);
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $this->authorize('create', Sale::class);

        $customers = $this->saleService->getCustomers();
        $medicines = $this->saleService->getSaleMedicines();

        return view('sales.create', compact('customers', 'medicines',));
    }

    /**
     * Store a newly created sale.
     */
    public function store(StoreSaleRequest $request)
    {
        $this->authorize('create', Sale::class);
        try {
            $sale = $this->saleService->store($request->validated());

            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Sale created successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to create the sale. Please try again.');
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);
        $sale = $this->saleService->getSale($sale);

        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(Sale $sale)
    {
        $this->authorize('update', $sale);

        $customers = $this->saleService->getCustomers();
        $medicines = $this->saleService->getSaleMedicines();
        $sale = $this->saleService->getSale($sale);

        return view('sales.edit', compact('sale', 'customers', 'medicines'));
    }

    /**
     * Update the specified sale.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $this->authorize('update', $sale);
        try {
            $sale = $this->saleService->update($sale, $request->validated());

            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Sale updated successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to update the sale. Please try again.');
        }
    }

    /**
     * Cancel the specified sale.
     */
    public function cancel(Sale $sale)
    {
        $this->authorize('cancel', $sale);

        if ($sale->isCancelled()) {
            return back()->with('error', 'This sale is already cancelled.');
        }
        try {

            $this->saleService->cancel($sale);

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale cancelled successfully and stock restored.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to cancel the sale.');
        }
    }

    /**
     * Complete a draft sale.
     */
    public function complete(Sale $sale)
    {
        $this->authorize('complete', $sale);

        if ($sale->isCancelled()) {
            return back()->with('error', 'Cancelled sales cannot be completed.');
        }

        if ($sale->isCompleted()) {
            return back()->with('error', 'This sale is already completed.');
        }
        try {

            $sale = $this->saleService->complete($sale);

            return redirect()->route('sales.show', $sale)->with('success', 'Sale completed successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to complete the sale.');
        }
    }

    /**
     * Update sale payment.
     */
    // public function updatePayment(Request $request, Sale $sale)
    // {
    //     $this->authorize('updatePayment', $sale);

    //     $validated = $request->validate([
    //         'paid_amount' => ['required', 'numeric', 'min:0'],
    //     ]);

    //     try {
    //         $this->saleService->updatePayment(
    //             $sale,
    //             (float) $validated['paid_amount']
    //         );

    //         return back()->with('success', 'Payment updated successfully.');
    //     } catch (ValidationException $e) {
    //         throw $e;
    //     } catch (\Throwable $e) {
    //         report($e);

    //         return back()->with('error', 'Unable to update payment.');
    //     }
    // }

    public function updatePayment(Request $request, Sale $sale)
    {
        $this->authorize('updatePayment', $sale);

        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $this->saleService->updatePayment(
                $sale,
                (float) $validated['paid_amount']
            );

            // Check if the request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment updated successfully.',
                    'data' => [
                        'paid_amount' => $sale->paid_amount,
                        'due_amount' => $sale->due_amount,
                        'payment_status' => $sale->payment_status,
                        'grand_total' => $sale->grand_total
                    ]
                ]);
            }

            return back()->with('success', 'Payment updated successfully.');
            
        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
            
        } catch (\Throwable $e) {
            report($e);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to update payment: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Unable to update payment.');
        }
    }

    /**
     * Delete the specified sale.
     */
    public function destroy(Sale $sale)
    {
        $this->authorize('delete', $sale);

        try {
            $this->saleService->destroy($sale);

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale deleted successfully and stock restored.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to delete the sale.');
        }
    }

    /**
     * Restore the specified sale.
     */
    public function restore(int $id)
    {
        $sale = Sale::withTrashed()->findOrFail($id);

        $this->authorize('restore', $sale);

        try {
            $this->saleService->restore($sale);

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale restored successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to restore the sale.');
        }
    }

    /**
     * Permanently delete the specified sale.
     */
    public function forceDelete(int $id)
    {
        $sale = Sale::withTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $sale);

        try {
            $this->saleService->forceDelete($sale);

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale permanently deleted.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to permanently delete the sale.');
        }
    }

    /**
     * Get available stock information for a medicine.
     */
    public function medicineStock(int $medicineId)
    {
        try {
            return response()->json(
                $this->saleService->getMedicineStock($medicineId)
            );
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Unable to retrieve medicine stock.',
            ], 500);
        }
    }

    /**
     * Calculate sale totals.
     */
    public function calculateTotals(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'discount_type' => ['required', 'in:fixed,percentage'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax_type' => ['required', 'in:fixed,percentage'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'shipping' => ['nullable', 'numeric', 'min:0'],
            'other_charges' => ['nullable', 'numeric', 'min:0'],
        ]);

        return response()->json(
            $this->saleService->calculateTotals(
                $validated['items'],
                $validated['discount_type'],
                (float) ($validated['discount'] ?? 0),
                $validated['tax_type'],
                (float) ($validated['tax'] ?? 0),
                (float) ($validated['shipping'] ?? 0),
                (float) ($validated['other_charges'] ?? 0)
            )
        );
    }
}
