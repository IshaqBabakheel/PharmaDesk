<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-2 col-md-4">

                        <label
                            for="medicineFilter"
                            class="form-label"
                        >
                            Medicine
                        </label>

                        <select
                            id="medicineFilter"
                            class="form-select"
                        >

                            <option value="">
                                All Medicines
                            </option>

                            @foreach ($medicines as $medicine)

                                <option value="{{ $medicine->id }}">
                                    {{ $medicine->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-4">

                        <label
                            for="batchFilter"
                            class="form-label"
                        >
                            Batch
                        </label>

                        <input
                            type="text"
                            id="batchFilter"
                            class="form-control"
                            placeholder="Batch number"
                        >

                    </div>


                    <div class="col-lg-2 col-md-6">

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


                    <div class="col-lg-2 col-md-6">

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


                    <div class="col-lg-2 col-md-6">

                        <label
                            for="transactionFilter"
                            class="form-label"
                        >
                            Transaction
                        </label>

                        <select
                            id="transactionFilter"
                            class="form-select"
                        >

                            <option value="all">
                                All Transactions
                            </option>

                            <option value="Purchase">
                                Purchase
                            </option>

                            <option value="Purchase Return">
                                Purchase Return
                            </option>

                            <option value="Sale">
                                Sale
                            </option>

                            <option value="Sale Return">
                                Sale Return
                            </option>

                            <option value="Stock Adjustment">
                                Stock Adjustment
                            </option>

                            <option value="Opening Stock">
                                Opening Stock
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-1 col-md-6">

                        <div class="d-flex gap-1">

                            <button
                                type="button"
                                id="applyLedgerFilters"
                                class="btn btn-primary"
                                title="Apply"
                            >
                                <i class="fas fa-filter"></i>
                            </button>

                            <button
                                type="button"
                                id="resetLedgerFilters"
                                class="btn btn-secondary"
                                title="Reset"
                            >
                                <i class="fas fa-rotate-left"></i>
                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>