<div class="row g-3 mb-4">
    {{-- Total Sales --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Total Sales
                        </h6>
                        <h3 class="fw-bold mb-0" id="totalSales">
                            {{ number_format($totalSales) }}
                        </h3>
                    </div>
                    <div class="icon bg-primary text-white rounded-circle">
                        <i class="fas fa-cart-shopping fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Completed Sales --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Completed
                        </h6>
                        <h3 class="fw-bold text-success mb-0" id="completedSales">
                            {{ number_format($completedSales) }}
                        </h3>
                    </div>
                    <div class="icon bg-success text-white rounded-circle">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Due --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Pending Due
                        </h6>
                        <h3 class="fw-bold text-warning mb-0" id="pendingDue">
                            {{ number_format($pendingDues, 2) }}
                        </h3>
                    </div>
                    <div class="icon bg-warning text-white rounded-circle">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancelled Sales --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Cancelled
                        </h6>
                        <h3 class="fw-bold text-danger mb-0" id="cancelledSales">
                            {{ number_format($cancelledSales) }}
                        </h3>
                    </div>
                    <div class="icon bg-danger text-white rounded-circle">
                        <i class="fas fa-ban fa-lg"></i>
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