<div class="row mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">

                            Total Suppliers

                        </small>

                        <h3 class="fw-bold mt-2">

                            {{ $totalSuppliers }}

                        </h3>

                    </div>

                    <div class="text-primary">

                        <i class="fas fa-truck fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">

                            Active

                        </small>

                        <h3 class="fw-bold text-success mt-2">

                            {{ $activeSuppliers }}

                        </h3>

                    </div>

                    <div class="text-success">

                        <i class="fas fa-circle-check fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">

                            Inactive

                        </small>

                        <h3 class="fw-bold text-danger mt-2">

                            {{ $inactiveSuppliers }}

                        </h3>

                    </div>

                    <div class="text-danger">

                        <i class="fas fa-circle-xmark fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">

                            Payable Accounts

                        </small>

                        <h3 class="fw-bold text-warning mt-2">

                            {{ $payableSuppliers }}

                        </h3>

                    </div>

                    <div class="text-warning">

                        <i class="fas fa-money-check-dollar fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>