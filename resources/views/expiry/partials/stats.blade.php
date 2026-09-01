<div
    class="row g-3 mb-4"
    id="expiryStats"
>

    {{-- Expired --}}
    <div class="col-xl-2 col-md-4 col-sm-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="text-muted small">
                            Expired Batches
                        </div>

                        <h3
                            class="fw-bold mb-0 text-danger"
                            id="expiredBatches"
                        >
                            0
                        </h3>

                    </div>

                    <div class="text-danger">

                        <i class="fas fa-calendar-xmark fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Critical --}}
    <div class="col-xl-2 col-md-4 col-sm-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="text-muted small">
                            Critical
                        </div>

                        <h3
                            class="fw-bold mb-0 text-warning"
                            id="criticalBatches"
                        >
                            0
                        </h3>

                    </div>

                    <div class="text-warning">

                        <i class="fas fa-triangle-exclamation fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- 30 Days --}}
    <div class="col-xl-2 col-md-4 col-sm-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="text-muted small">
                            Within 30 Days
                        </div>

                        <h3
                            class="fw-bold mb-0 text-warning"
                            id="warningBatches"
                        >
                            0
                        </h3>

                    </div>

                    <div class="text-warning">

                        <i class="fas fa-clock fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- 90 Days --}}
    <div class="col-xl-2 col-md-4 col-sm-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="text-muted small">
                            Within 90 Days
                        </div>

                        <h3
                            class="fw-bold mb-0 text-info"
                            id="upcomingBatches"
                        >
                            0
                        </h3>

                    </div>

                    <div class="text-info">

                        <i class="fas fa-calendar-days fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Total Batches --}}
    <div class="col-xl-2 col-md-4 col-sm-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="text-muted small">
                            Active Expiry Batches
                        </div>

                        <h3
                            class="fw-bold mb-0 text-primary"
                            id="totalExpiryBatches"
                        >
                            0
                        </h3>

                    </div>

                    <div class="text-primary">

                        <i class="fas fa-boxes-stacked fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Total Quantity --}}
    <div class="col-xl-2 col-md-4 col-sm-6">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="text-muted small">
                            Units at Risk
                        </div>

                        <h3
                            class="fw-bold mb-0 text-secondary"
                            id="totalExpiryQuantity"
                        >
                            0
                        </h3>

                    </div>

                    <div class="text-secondary">

                        <i class="fas fa-pills fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>