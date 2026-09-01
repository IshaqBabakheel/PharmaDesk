<div class="modal fade" id="updatePaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updatePaymentForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-money-bill-wave text-primary me-2"></i>
                        Update Payment
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Invoice</label>
                        <input type="text" id="paymentInvoice" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Grand Total</label>
                        <input type="text" id="paymentGrandTotal" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Paid Amount</label>
                        <input type="number" id="paymentAmount" name="paid_amount" class="form-control" min="0" step="0.01" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Update Payment
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>