<div class="row g-3 mb-4">

    {{-- Total Returns --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted mb-2">
                            Total Returns
                        </h6>

                        <h3 class="fw-bold mb-0">
                            {{ number_format($totalReturns) }}
                        </h3>

                    </div>

                    <div class="icon bg-primary text-white rounded-circle">

                        <i class="fas fa-rotate-left fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Completed --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted mb-2">
                            Completed
                        </h6>

                        <h3 class="fw-bold text-success mb-0">
                            {{ number_format($completedReturns) }}
                        </h3>

                    </div>

                    <div class="icon bg-success text-white rounded-circle">

                        <i class="fas fa-circle-check fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Draft --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted mb-2">
                            Draft Returns
                        </h6>

                        <h3 class="fw-bold text-warning mb-0">
                            {{ number_format($draftReturns) }}
                        </h3>

                    </div>

                    <div class="icon bg-warning text-white rounded-circle">

                        <i class="fas fa-file-pen fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Total Amount --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-muted mb-2">
                            Return Amount
                        </h6>

                        <h3 class="fw-bold text-danger mb-0">

                            {{ number_format($totalAmount,2) }}

                        </h3>

                    </div>

                    <div class="icon bg-danger text-white rounded-circle">

                        <i class="fas fa-money-bill-wave fa-lg"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

.icon{

    width:60px;

    height:60px;

    display:flex;

    justify-content:center;

    align-items:center;

}

</style>