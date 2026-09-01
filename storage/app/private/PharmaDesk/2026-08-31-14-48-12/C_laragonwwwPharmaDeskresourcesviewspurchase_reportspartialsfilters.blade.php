<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="row g-3 align-items-end">

            <div class="col-md-3">

                <label
                    for="dateFrom"
                    class="form-label"
                >
                    From
                </label>

                <input
                    type="date"
                    id="dateFrom"
                    class="form-control"
                >

            </div>


            <div class="col-md-3">

                <label
                    for="dateTo"
                    class="form-label"
                >
                    To
                </label>

                <input
                    type="date"
                    id="dateTo"
                    class="form-control"
                >

            </div>


            <div class="col-md-3">

                <label
                    for="supplierFilter"
                    class="form-label"
                >
                    Supplier
                </label>

                <select
                    id="supplierFilter"
                    class="form-select"
                >

                    <option value="">
                        All Suppliers
                    </option>

                    @foreach ($suppliers as $supplier)

                        <option value="{{ $supplier->id }}">
                            {{ $supplier->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-3">

                <label
                    for="paymentStatusFilter"
                    class="form-label"
                >
                    Payment Status
                </label>

                <select
                    id="paymentStatusFilter"
                    class="form-select"
                >

                    <option value="">
                        All
                    </option>

                    <option value="Paid">
                        Paid
                    </option>

                    <option value="Partially Paid">
                        Partially Paid
                    </option>

                    <option value="Unpaid">
                        Unpaid
                    </option>

                </select>

            </div>


            <div class="col-md-3">

                <label
                    for="purchaseStatusFilter"
                    class="form-label"
                >
                    Purchase Status
                </label>

                <select
                    id="purchaseStatusFilter"
                    class="form-select"
                >

                    <option value="">
                        All
                    </option>

                    <option value="Completed">
                        Completed
                    </option>

                    <option value="Draft">
                        Draft
                    </option>

                    <option value="Cancelled">
                        Cancelled
                    </option>

                </select>

            </div>


            <div class="col-md-2">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="resetPurchaseReport"
                >
                    <i class="fas fa-rotate-left me-1"></i>
                    Reset
                </button>

            </div>

        </div>

    </div>

</div>