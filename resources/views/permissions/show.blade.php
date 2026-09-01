{{-- @extends('layouts.app')

@section('content')


    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-key text-primary me-2"></i>

                Permission Details

            </h2>


            <p class="text-muted mb-0">

                View permission information and assigned roles.

            </p>


        </div>



        <div>


            @can('permissions.edit')
                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-warning">


                    <i class="fas fa-edit me-1"></i>

                    Edit


                </a>
            @endcan



            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">


                <i class="fas fa-arrow-left me-1"></i>

                Back


            </a>



        </div>



    </div>





    <div class="row mb-4">


        <div class="col-md-3">


            <div class="card border-0 shadow-sm">


                <div class="card-body">


                    <h6 class="text-muted">

                        Permission Name

                    </h6>


                    <h5 class="mb-0">


                        {{ $permission->name }}


                    </h5>


                </div>


            </div>


        </div>





        <div class="col-md-3">


            <div class="card border-0 shadow-sm">


                <div class="card-body">


                    <h6 class="text-muted">

                        Module

                    </h6>


                    <h5 class="mb-0">


                        {{ Str::headline(explode('.', $permission->name)[0]) }}


                    </h5>


                </div>


            </div>


        </div>






        <div class="col-md-3">


            <div class="card border-0 shadow-sm">


                <div class="card-body">


                    <h6 class="text-muted">

                        Action

                    </h6>


                    <h5 class="mb-0">


                        {{ Str::headline(explode('.', $permission->name)[1] ?? '-') }}


                    </h5>


                </div>


            </div>


        </div>







        <div class="col-md-3">


            <div class="card border-0 shadow-sm">


                <div class="card-body">


                    <h6 class="text-muted">

                        Guard

                    </h6>


                    <span class="badge bg-info fs-6">


                        {{ strtoupper($permission->guard_name) }}


                    </span>


                </div>


            </div>


        </div>



    </div>






    <div class="card shadow-sm">


        <div class="card-header d-flex justify-content-between align-items-center">


            <h5 class="mb-0">


                Assigned Roles


            </h5>



            <span class="badge bg-primary">


                {{ $permission->roles->count() }}


            </span>



        </div>




        <div class="card-body">


            @if ($permission->roles->count())
                <div class="row">


                    @foreach ($permission->roles as $role)
                        <div class="col-lg-3 col-md-4 mb-3">


                            <div class="card border">


                                <div class="card-body text-center">


                                    <i class="fas fa-user-shield text-primary fa-2x mb-2"></i>



                                    <h6 class="mb-0">


                                        {{ $role->name }}


                                    </h6>



                                </div>


                            </div>


                        </div>
                    @endforeach


                </div>
            @else
                <div class="alert alert-warning mb-0">


                    <i class="fas fa-info-circle me-1"></i>


                    This permission is not assigned to any role yet.


                </div>
            @endif


        </div>



    </div>




@endsection --}}


@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="fas fa-key text-primary me-2"></i>

                Permission Details

            </h2>

        </div>

    </div>

    <div class="card">

        <div class="card-header">

            Permission Information

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>

                    <th width="220">

                        Module

                    </th>

                    <td>

                        {{ \Illuminate\Support\Str::headline(explode('.', $permission->name)[0]) }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Permission

                    </th>

                    <td>

                        {{ \Illuminate\Support\Str::headline(explode('.', $permission->name)[1]) }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Guard

                    </th>

                    <td>

                        {{ strtoupper($permission->guard_name) }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Assigned Roles

                    </th>

                    <td>

                        @forelse($permission->roles as $role)

                            <span class="badge bg-primary">

                                {{ $role->name }}

                            </span>

                        @empty

                            <span class="text-muted">

                                No roles assigned.

                            </span>

                        @endforelse

                    </td>

                </tr>

            </table>

        </div>

    </div>

</div>

@endsection