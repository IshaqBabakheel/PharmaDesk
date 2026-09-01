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
                    for="customerFilter"
                    class="form-label"
                >
                    Customer
                </label>

                <select
                    id="customerFilter"
                    class="form-select"
                >

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

                <div class="d-flex gap-2">

                    <button
                        type="button"
                        class="btn btn-primary flex-grow-1"
                        id="applyProfitLossFilters"
                    >
                        <i class="fas fa-filter me-1"></i>
                        Apply
                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="resetProfitLoss"
                        title="Reset Filters"
                    >
                        <i class="fas fa-rotate-left"></i>
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>