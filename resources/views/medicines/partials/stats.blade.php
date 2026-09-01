{{-- ========================================================= --}}
{{-- Statistics Cards --}}
{{-- ========================================================= --}}

<div class="row mb-4">

    {{-- Total Medicines --}}
    <div class="col-xl-3 col-md-6 mb-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted d-block">
                            Total Medicines
                        </small>

                        <h3 class="fw-bold mb-0 mt-2">

                            {{ number_format($stats['total']) }}

                        </h3>

                    </div>

                    <div
                        class="bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                        style="width:60px;height:60px;"
                    >

                        <i
                            class="fas fa-capsules
                            text-primary
                            fa-lg"
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Active Medicines --}}
    <div class="col-xl-3 col-md-6 mb-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted d-block">

                            Active Medicines

                        </small>

                        <h3 class="fw-bold text-success mb-0 mt-2">

                            {{ number_format($stats['active']) }}

                        </h3>

                    </div>

                    <div
                        class="bg-success bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                        style="width:60px;height:60px;"
                    >

                        <i
                            class="fas fa-circle-check
                            text-success
                            fa-lg"
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Low Stock --}}
    <div class="col-xl-3 col-md-6 mb-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted d-block">

                            Low Stock

                        </small>

                        <h3 class="fw-bold text-warning mb-0 mt-2">

                            {{ number_format($stats['low_stock']) }}

                        </h3>

                    </div>

                    <div
                        class="bg-warning bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                        style="width:60px;height:60px;"
                    >

                        <i
                            class="fas fa-triangle-exclamation
                            text-warning
                            fa-lg"
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- Out of Stock --}}
    <div class="col-xl-3 col-md-6 mb-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted d-block">

                            Out of Stock

                        </small>

                        <h3 class="fw-bold text-danger mb-0 mt-2">

                            {{ number_format($stats['out_of_stock']) }}

                        </h3>

                    </div>

                    <div
                        class="bg-danger bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center"
                        style="width:60px;height:60px;"
                    >

                        <i
                            class="fas fa-box-open
                            text-danger
                            fa-lg"
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>