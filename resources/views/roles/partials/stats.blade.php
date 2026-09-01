<div class="row mb-4">

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted mb-2">
                            Total Roles
                        </h6>

                        <h3 class="mb-0">
                            {{ \Spatie\Permission\Models\Role::count() }}
                        </h3>

                    </div>

                    <div class="text-primary">

                        <i class="fas fa-user-shield fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted mb-2">
                            Permissions
                        </h6>

                        <h3 class="mb-0">
                            {{ \Spatie\Permission\Models\Permission::count() }}
                        </h3>

                    </div>

                    <div class="text-success">

                        <i class="fas fa-key fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted mb-2">
                            Assigned Roles
                        </h6>

                        <h3 class="mb-0">

                            {{ \App\Models\User::has('roles')->count() }}

                        </h3>

                    </div>

                    <div class="text-warning">

                        <i class="fas fa-users fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted mb-2">
                            Unassigned Roles
                        </h6>

                        <h3 class="mb-0">

                            {{ \Spatie\Permission\Models\Role::doesntHave('users')->count() }}

                        </h3>

                    </div>

                    <div class="text-danger">

                        <i class="fas fa-user-slash fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>