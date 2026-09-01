<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sale;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display payments.
     */
    public function index()
    {
        $this->authorize('viewAny', Payment::class);

        return view('payments.index');
    }

    /**
     * Return payments for DataTables.
     */
    public function datatable(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $filter = $request->get('filter', 'all');
        $type = $request->get('type', 'all');

        $query = Payment::with([
            'sale',
            'purchase',
            'expense',
            'customer',
            'supplier',
            'creator',
            'deleter',
        ])->select('payments.*');

        if ($filter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($filter === 'active') {
            $query->whereNull('deleted_at');
        }

        if ($filter === 'receipt'){
            $query->whereType('receipt')->get();
        } elseif ($filter === 'payment'){
            $query->whereType('payment')->get();
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('payment_number', function (Payment $row) {
                return '<strong>' . e($row->payment_number) . '</strong>';
            })
            ->addColumn('type', function (Payment $row) {
                return $row->isReceipt()
                    ? '<span class="badge bg-success">Receipt</span>'
                    : '<span class="badge bg-primary">Payment</span>';
            })
            ->addColumn('reference', function (Payment $row) {
                if ($row->sale) {
                    return e($row->sale->invoice_number);
                }

                if ($row->purchase) {
                    return e($row->purchase->purchase_number);
                }

                if ($row->expense) {
                    return e($row->expense->expense_number);
                }

                return '-';
            })

            ->addColumn('party', function (Payment $row) {
                if ($row->customer) {
                    return e($row->customer->name);
                }

                if ($row->supplier) {
                    return e($row->supplier->name);
                }

                return '-';
            })
            ->editColumn('amount', function (Payment $row) {
                return number_format((float) $row->amount, 2);
            })
            ->editColumn('payment_date', function (Payment $row) {
                return $row->payment_date?->format('d M Y') ?? '-';
            })
            ->addColumn('method', function (Payment $row) {
                return ucwords(str_replace('_', ' ', $row->method));
            })
            ->addColumn('created_by', function (Payment $row) {
                if ($row->trashed()) {
                    return $row->deleter?->name ?? 'System';
                }

                return $row->creator?->name ?? '-';
            })
            ->addColumn('action', function (Payment $row) {
                $isTrashed = $row->trashed();

                return view('components.action-dropdown', [
                    'row' => $row,
                    'module' => 'payments',
                    'show' => true,
                    'edit' => !$isTrashed,
                    'delete' => !$isTrashed,
                    'restore' => $isTrashed,
                    'forceDelete' => $isTrashed,
                ]);
            })
            ->rawColumns([
                'payment_number',
                'type',
                'action',
            ])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create', Payment::class);

        $sales = Sale::query()
            ->where('status', '!=', 'cancelled')
            ->orderByDesc('id')
            ->get([
                'id',
                'invoice_number',
                'grand_total',
                'paid_amount',
                'customer_id',
            ]);

        $purchases = Purchase::query()
            ->where('status', '!=', 'Cancelled')
            ->orderByDesc('id')
            ->get([
                'id',
                'purchase_number',
                'grand_total',
                'paid_amount',
                'supplier_id',
            ]);

        return view('payments.create', compact(
            'sales',
            'purchases'
        ));
    }

    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);

        $sales = Sale::query()
            ->orderByDesc('id')
            ->get([
                'id',
                'invoice_number',
                'grand_total',
                'paid_amount',
                'customer_id',
            ]);

        $purchases = Purchase::query()
            ->orderByDesc('id')
            ->get([
                'id',
                'purchase_number',
                'grand_total',
                'paid_amount',
                'supplier_id',
            ]);

        return view('payments.edit', compact(
            'payment',
            'sales',
            'purchases'
        ));
    }

    /**
     * Store a payment.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Payment::class);

        $data = $request->validate([
            'type' => ['required', 'in:receipt,payment'],
            'sale_id' => ['nullable', 'integer', 'exists:sales,id'],
            'purchase_id' => ['nullable', 'integer', 'exists:purchases,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'in:cash,card,bank_transfer,jazzcash,easypaisa,other'],
            'payment_date' => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $payment = $this->paymentService->store($data);

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Payment recorded successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to record payment.');
        }
    }

    /**
     * Update a payment record.
     */
    public function update(Request $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $data = $request->validate([
            'type' => ['required', 'in:receipt,payment'],
            'sale_id' => ['nullable', 'integer', 'exists:sales,id'],
            'purchase_id' => ['nullable', 'integer', 'exists:purchases,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'in:cash,card,bank_transfer,jazzcash,easypaisa,other'],
            'payment_date' => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $payment = $this->paymentService->update(
                $payment,
                $data
            );

            return redirect()
                ->route('payments.show', $payment)
                ->with('success', 'Payment updated successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to update payment.');
        }
    }

    /**
     * Display payment details.
     */
    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load([
            'sale',
            'purchase',
            'expense',
            'customer',
            'supplier',
            'creator',
            'updater',
            'deleter',
        ]);

        return view('payments.show', compact('payment'));
    }

    /**
     * Soft delete a payment.
     */
    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);

        try {
            $this->paymentService->delete($payment);

            return redirect()
                ->route('payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to delete payment.'
            );
        }
    }

    /**
     * Restore a payment.
     */
    public function restore($id)
    {
        $payment = Payment::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $payment);

        try {
            $this->paymentService->restore($payment);

            return redirect()
                ->route('payments.index')
                ->with('success', 'Payment restored successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to restore payment.'
            );
        }
    }

    /**
     * Permanently delete a payment.
     */
    public function forceDelete($id)
    {
        $payment = Payment::onlyTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $payment);

        try {
            $this->paymentService->forceDelete($payment);

            return redirect()
                ->route('payments.index')
                ->with(
                    'success',
                    'Payment permanently deleted.'
                );
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Unable to permanently delete payment.'
            );
        }
    }

    /**
     * Record an additional receipt for a sale.
     *
     * The existing Sales modal sends the desired total paid amount.
     */
    public function salePayment(Request $request, Sale $sale)
    {
        $this->authorize('create', Payment::class);

        $data = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'method' => ['nullable', 'in:cash,card,bank_transfer,jazzcash,easypaisa,other'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $alreadyPaid = $this->paymentService->getSalePaidAmount($sale->id);

            $newPaid = (float) $data['paid_amount'];
            $difference = $newPaid - $alreadyPaid;

            if ($difference < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'The new paid amount cannot be less than the amount already recorded.'
                ], 422);
            }

            if ($difference == 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No additional payment was recorded.',
                    'no_change' => true
                ]);
            }

            $this->paymentService->store([
                'type' => 'receipt',
                'sale_id' => $sale->id,
                'purchase_id' => null,
                'customer_id' => $sale->customer_id,
                'supplier_id' => null,
                'amount' => $difference,
                'method' => $data['method'] ?? 'cash',
                'payment_date' => now()->format('Y-m-d'),
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully.',
                'sale_id' => $sale->id,
                'new_paid_amount' => $newPaid,
                'difference' => $difference
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update sale payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Record an additional supplier payment for a purchase.
     *
     * The existing Purchases modal sends the desired total paid amount.
     */
    public function purchasePayment(Request $request, Purchase $purchase) 
    {
        $this->authorize('create', Payment::class);

        $data = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'method' => ['nullable', 'in:cash,card,bank_transfer,jazzcash,easypaisa,other'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $alreadyPaid = $this->paymentService->getPurchasePaidAmount($purchase->id);

            $newPaid = (float) $data['paid_amount'];
            $difference = $newPaid - $alreadyPaid;

            if ($difference < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'The new paid amount cannot be less than the amount already recorded.'
                ], 422);
            }

            if ($difference == 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No additional payment was recorded.',
                    'no_change' => true
                ]);
            }

            $payment = $this->paymentService->store([
                'type' => 'payment',
                'sale_id' => null,
                'purchase_id' => $purchase->id,
                'customer_id' => null,
                'supplier_id' => $purchase->supplier_id,
                'amount' => $difference,
                'method' => $data['method'] ?? 'cash',
                'payment_date' => now()->format('Y-m-d'),
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully.',
                'purchase_id' => $purchase->id,
                'new_paid_amount' => $newPaid,
                'difference' => $difference
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update purchase payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
