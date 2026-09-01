<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PharmaDesk') }}</title>
    <meta name="theme-color" content="#4272d7">

    <link href="{{ asset('css/font-face.css') }}" rel="stylesheet" media="all">
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="{{ asset('vendor/fontawesome-7.2.0/css/all.min.css') }}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{ asset('vendor/bootstrap-5.3.8.min.css') }}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('css/theme-2026.css') }}" rel="stylesheet" media="all">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
    <style>
        /* Active state for filter buttons */
        .btn-group .filter-btn.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .btn-group .filter-btn.active.btn-outline-success {
            background-color: #198754;
            color: #fff;
            border-color: #198754;
        }

        .btn-group .filter-btn.active.btn-outline-warning {
            background-color: #ffc107;
            color: #000;
            border-color: #ffc107;
        }

        /* Add to your stylesheet */
        .navbar__list li.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.2) 0%, transparent 100%);
            border-radius: 8px;
            position: relative;
        }

        .navbar__list li.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #4F46E5;
            border-radius: 0 4px 4px 0;
        }

        .navbar__list li.active a {
            color: #ffffff;
            font-weight: 600;
        }

        .navbar__list li.active i {
            color: #818CF8;
        }
    </style>
    @stack('css')
</head>

<body class="theme-2026">
    <div id="app">
        <a class="visually-hidden-focusable skip-link" href="#main-content">Skip to main content</a>

        <div class="page-wrapper">
            <!-- MENU SIDEBAR-->
            @include('layouts.sidebar')
            <!-- END MENU SIDEBAR-->
            <div class="page-container">
                <!-- HEADER DESKTOP-->
                @include('layouts.header')
                <!-- END HEADER -->

                <main id="main-content" class="main-content">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">
                            @yield('content')
                            @include('layouts.footer')
                        </div>
                    </div>
                </main>
            </div>
            <!-- END PAGE CONTAINER-->
        </div>
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    {{-- <script src="{{ asset('js/vanilla-utils.js') }}"></script> --}}
    <script src="{{ asset('vendor/bootstrap-5.3.8.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('vendor/chartjs/chart.umd.js-4.5.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap5-init.js') }}"></script>
    <script src="{{ asset('js/main-vanilla.js') }}"></script>
    <script src="{{ asset('js/modern-plugins.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
    <script>
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        // Display Laravel flash messages as toast notifications
        @if (session('success'))
            toastr.success('{{ session('success') }}', 'Success');
        @endif

        @if (session('error'))
            toastr.error('{{ session('error') }}', 'Error');
        @endif

        @if (session('warning'))
            toastr.warning('{{ session('warning') }}', 'Warning');
        @endif

        @if (session('info'))
            toastr.info('{{ session('info') }}', 'Info');
        @endif
    </script>
</body>

</html>
