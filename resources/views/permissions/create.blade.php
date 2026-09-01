{{-- @extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            <i class="fas fa-user-shield text-primary me-2"></i>

            Create Permission

        </h2>

        <p class="text-muted mb-0">

            Create a new system permission and assign to role.

        </p>

    </div>

    <div>

        <nav>

            <ol class="breadcrumb justify-content-end mb-0">

                <li class="breadcrumb-item">

                    <a href="{{ route('home') }}">

                        Dashboard

                    </a>

                </li>

                <li class="breadcrumb-item">

                    User Management

                </li>

                <li class="breadcrumb-item">

                    <a href="{{ route('permissions.index') }}">

                        Permissions

                    </a>

                </li>

                <li class="breadcrumb-item active">

                    Create

                </li>

            </ol>

        </nav>

    </div>

</div>

<form
    action="{{ route('permissions.store') }}"
    method="POST"
>

    @include('permissions.partials.form')

</form>

@endsection --}}


@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}

    <div class="page-header d-flex justify-content-between align-items-lg-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-key text-primary me-2"></i>

                Create Permission

            </h2>

            <p class="text-muted mb-0">

                Create a new system permission.

            </p>

        </div>

        <div>

            <nav>

                <ol class="breadcrumb justify-content-end mb-0">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home') }}">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        Administration

                    </li>

                    <li class="breadcrumb-item">

                        <a href="{{ route('permissions.index') }}">

                            Permissions

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Create

                    </li>

                </ol>

            </nav>

        </div>

    </div>

    <div class="card">

        <div class="card-header">

            <strong>

                Permission Information

            </strong>

        </div>

        <div class="card-body">

            <form
                action="{{ route('permissions.store') }}"
                method="POST"
            >

                @include('permissions.partials.form')

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

    $(document).ready(function () {

        const $module = $('#permissionModule');
        const $action = $('#permissionAction');

        const actionsUrl = @json(route('permissions.actions'));

        const selectedModule = @json(old('module', $module ?? ''));
        const selectedAction = @json(old('action', $action ?? ''));


        /*
        |--------------------------------------------------------------------------
        | Format Action
        |--------------------------------------------------------------------------
        */

        function formatAction(action) {

            return action
                .replace(/-/g, ' ')
                .replace(/\b\w/g, function (letter) {
                    return letter.toUpperCase();
                });

        }


        /*
        |--------------------------------------------------------------------------
        | Reset Action Dropdown
        |--------------------------------------------------------------------------
        */

        function resetActions(message = 'Select Module First') {

            $action
                .empty()
                .append(
                    $('<option>', {
                        value: '',
                        text: message
                    })
                )
                .prop('disabled', true);

        }


        /*
        |--------------------------------------------------------------------------
        | Load Actions
        |--------------------------------------------------------------------------
        */

        function loadActions(module, selectedAction = '') {

            if (!module) {

                resetActions();

                return;
            }


            $action
                .empty()
                .append(
                    $('<option>', {
                        value: '',
                        text: 'Loading actions...'
                    })
                )
                .prop('disabled', true);


            $.ajax({

                url: actionsUrl,

                type: 'GET',

                data: {
                    module: module
                },


                success: function (response) {

                    $action.empty();


                    if (
                        !response.success ||
                        !response.actions ||
                        response.actions.length === 0
                    ) {

                        $action
                            .append(
                                $('<option>', {
                                    value: '',
                                    text: 'No actions available'
                                })
                            )
                            .prop('disabled', true);

                        return;
                    }


                    $action.append(
                        $('<option>', {
                            value: '',
                            text: 'Select Action'
                        })
                    );


                    response.actions.forEach(function (action) {

                        const option = $('<option>', {
                            value: action,
                            text: formatAction(action)
                        });


                        if (action === selectedAction) {
                            option.prop('selected', true);
                        }


                        $action.append(option);

                    });


                    $action.prop('disabled', false);

                },


                error: function (xhr) {

                    console.error(
                        'Unable to load permission actions.',
                        xhr
                    );


                    resetActions(
                        'Unable to load actions'
                    );

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Module Changed
        |--------------------------------------------------------------------------
        */

        $module.on('change', function () {

            loadActions(
                $(this).val()
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Load Existing Value
        |--------------------------------------------------------------------------
        */

        if (selectedModule) {

            loadActions(
                selectedModule,
                selectedAction
            );

        }

    });

</script>

@endpush