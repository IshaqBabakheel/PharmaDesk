<div
    class="modal fade"
    id="posPaymentModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-credit-card me-2"></i>
                    Complete Payment
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="text-center mb-4">

                    <div class="text-muted">
                        Total Amount
                    </div>

                    <div
                        class="display-5 fw-bold text-success"
                        id="paymentTotal"
                    >
                        Rs. 0.00
                    </div>

                </div>


                <div class="mb-3">

                    <label
                        for="paymentCustomer"
                        class="form-label"
                    >
                        Customer
                    </label>

                    <select
                        id="paymentCustomer"
                        class="form-select"
                    >

                        <option value="">
                            Walk-in Customer
                        </option>

                        @foreach ($customers as $customer)

                            <option value="{{ $customer->id }}">
                                {{ $customer->name }}
                                @if ($customer->phone)
                                    - {{ $customer->phone }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-3">

                    <label
                        for="paymentReceived"
                        class="form-label"
                    >
                        Amount Received
                    </label>

                    <input
                        type="number"
                        id="paymentReceived"
                        class="form-control form-control-lg"
                        step="0.01"
                        min="0"
                        value="0"
                    >

                </div>


                <div class="d-flex justify-content-between">

                    <span>
                        Due
                    </span>

                    <strong
                        class="text-danger"
                        id="paymentDue"
                    >
                        Rs. 0.00
                    </strong>

                </div>


                <div class="d-flex justify-content-between mt-2">

                    <span>
                        Change
                    </span>

                    <strong
                        class="text-success"
                        id="paymentChange"
                    >
                        Rs. 0.00
                    </strong>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>


                <button
                    type="button"
                    class="btn btn-success"
                    id="confirmPayment"
                >

                    <i class="bi bi-check-circle me-1"></i>

                    Complete Sale

                </button>

            </div>

        </div>

    </div>
</div>