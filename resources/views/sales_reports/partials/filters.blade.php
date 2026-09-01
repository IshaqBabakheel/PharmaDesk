<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <label for="dateFrom" class="form-label">
                    From
                </label>

                <input type="date" id="dateFrom" class="form-control">
            </div>

            <div class="col-md-3">
                <label for="dateTo" class="form-label">
                    To
                </label>

                <input type="date" id="dateTo" class="form-control">
            </div>

            <div class="col-md-3">
                <label for="customerFilter" class="form-label">
                    Customer
                </label>

                <select id="customerFilter" class="form-select">
                    <option value="">
                        All Customers
                    </option>

                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="paymentStatusFilter" class="form-label">
                    Payment Status
                </label>

                <select id="paymentStatusFilter" class="form-select">
                    <option value="">
                        All
                    </option>

                    <option value="paid">
                        Paid
                    </option>

                    <option value="partial">
                        Partially Paid
                    </option>

                    <option value="due">
                        Due
                    </option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="saleStatusFilter" class="form-label">
                    Sale Status
                </label>

                <select id="saleStatusFilter" class="form-select">
                    <option value="">
                        All
                    </option>

                    <option value="completed">
                        Completed
                    </option>

                    <option value="draft">
                        Draft
                    </option>

                    <option value="cancelled">
                        Cancelled
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-secondary" id="resetSalesReport">
                    <i class="fas fa-rotate-left me-1"></i>
                    Reset
                </button>
            </div>

        </div>

    </div>

</div>
