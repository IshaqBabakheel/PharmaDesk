<div class="row g-3 mb-4">
    {{-- Total Purchases --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Total Purchases
                        </h6>
                        <h3 class="fw-bold mb-0">
                            {{ number_format($totalPurchases) }}
                        </h3>
                    </div>
                    <div class="icon bg-primary text-white rounded-circle">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Completed Purchases --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Completed
                        </h6>
                        <h3 class="fw-bold text-success mb-0">
                            {{ number_format($completedPurchases) }}
                        </h3>
                    </div>
                    <div class="icon bg-success text-white rounded-circle">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Draft Purchases --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Draft
                        </h6>
                        <h3 class="fw-bold text-warning mb-0">
                            {{ number_format($draftPurchases) }}
                        </h3>
                    </div>
                    <div class="icon bg-warning text-white rounded-circle">
                        <i class="fas fa-pen fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Purchase Amount --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">
                            Purchase Amount
                        </h6>
                        <h3 class="fw-bold text-primary mb-0">
                            {{ number_format($totalAmount, 2) }}
                        </h3>
                    </div>
                    <div class="icon bg-primary text-white rounded-circle">
                        <i class="fas fa-money-bill-wave fa-lg"></i>
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