{{-- <div class="row mb-4">


    <div class="col-xl-3 col-md-6">


        <div class="card border-0 shadow-sm">


            <div class="card-body">


                <div class="d-flex justify-content-between">


                    <div>


                        <h6 class="text-muted">
                            Total Permissions
                        </h6>


                        <h3 class="mb-0">

                            {{ \Spatie\Permission\Models\Permission::count() }}

                        </h3>


                    </div>


                    <div class="text-primary">

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


                        <h6 class="text-muted">
                            Assigned Permissions
                        </h6>


                        <h3 class="mb-0">

                            {{ \Spatie\Permission\Models\Permission::has('roles')->count() }}

                        </h3>


                    </div>



                    <div class="text-success">

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


                        <h6 class="text-muted">
                            Unassigned
                        </h6>


                        <h3 class="mb-0">

                            {{ \Spatie\Permission\Models\Permission::doesntHave('roles')->count() }}

                        </h3>


                    </div>



                    <div class="text-danger">

                        <i class="fas fa-user-slash fa-2x"></i>

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


                        <h6 class="text-muted">
                            Guards
                        </h6>


                        <h3 class="mb-0">

                            {{ \Spatie\Permission\Models\Permission::distinct('guard_name')->count('guard_name') }}

                        </h3>


                    </div>



                    <div class="text-warning">

                        <i class="fas fa-shield-alt fa-2x"></i>


                    </div>



                </div>


            </div>


        </div>


    </div>



</div> --}}


<div class="row mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Total Permissions
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $totalPermissions }}
                        </h3>

                    </div>

                    <div class="fs-2 text-primary">

                        <i class="fas fa-key"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Modules
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $totalModules }}
                        </h3>

                    </div>

                    <div class="fs-2 text-success">

                        <i class="fas fa-layer-group"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Assigned
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $assignedPermissions }}
                        </h3>

                    </div>

                    <div class="fs-2 text-warning">

                        <i class="fas fa-user-shield"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Unassigned
                        </small>

                        <h3 class="fw-bold mb-0">
                            {{ $unassignedPermissions }}
                        </h3>

                    </div>

                    <div class="fs-2 text-danger">

                        <i class="fas fa-user-slash"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>