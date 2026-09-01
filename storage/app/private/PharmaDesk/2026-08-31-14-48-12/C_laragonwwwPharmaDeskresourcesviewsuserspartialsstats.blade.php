<div class="row mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">

                            Total Users

                        </small>

                        <h3 class="fw-bold">

                            {{ $totalUsers }}

                        </h3>

                    </div>

                    <div>

                        <i class="fas fa-users fa-2x text-primary"></i>

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

                            Active Users

                        </small>

                        <h3 class="fw-bold text-success">

                            {{ $activeUsers }}

                        </h3>

                    </div>

                    <div>

                        <i class="fas fa-user-check fa-2x text-success"></i>

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

                            Inactive Users

                        </small>

                        <h3 class="fw-bold text-danger">

                            {{ $inactiveUsers }}

                        </h3>

                    </div>

                    <div>

                        <i class="fas fa-user-slash fa-2x text-danger"></i>

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

                            Roles

                        </small>

                        <h3 class="fw-bold text-info">

                            {{ $rolesCount }}

                        </h3>

                    </div>

                    <div>

                        <i class="fas fa-user-shield fa-2x text-info"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>