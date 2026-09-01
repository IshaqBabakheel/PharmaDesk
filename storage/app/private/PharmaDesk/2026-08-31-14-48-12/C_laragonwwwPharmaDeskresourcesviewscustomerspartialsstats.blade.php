<div class="row g-3 mb-4">
    {{-- Total Customers --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Total Customers
                        </h6>
                        <h3 class="fw-bold mb-0">
                            {{ number_format($totalCustomers) }}
                        </h3>
                    </div>
                    <div class="icon bg-primary text-white rounded-circle">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Regular Customers --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Regular Customers
                        </h6>
                        <h3 class="fw-bold text-success mb-0">
                            {{ number_format($regularCustomers) }}
                        </h3>
                    </div>
                    <div class="icon bg-success text-white rounded-circle">
                        <i class="fas fa-user-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Corporate Customers --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Corporate Customers
                        </h6>
                        <h3 class="fw-bold text-warning mb-0">
                            {{ number_format($corporateCustomers) }}
                        </h3>
                    </div>
                    <div class="icon bg-warning text-white rounded-circle">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Credit Customers --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Credit Customers
                        </h6>
                        <h3 class="fw-bold text-danger mb-0">
                            {{ number_format($creditCustomers) }}
                        </h3>
                    </div>
                    <div class="icon bg-danger text-white rounded-circle">
                        <i class="fas fa-hand-holding-usd fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon {
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>