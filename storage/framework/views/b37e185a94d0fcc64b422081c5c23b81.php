<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'PharmaDesk')); ?></title>
    <meta name="theme-color" content="#4272d7">

    <link href="<?php echo e(asset('css/font-face.css')); ?>" rel="stylesheet" media="all">
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="<?php echo e(asset('vendor/fontawesome-7.2.0/css/all.min.css')); ?>" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?php echo e(asset('vendor/bootstrap-5.3.8.min.css')); ?>" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?php echo e(asset('vendor/css-hamburgers/hamburgers.min.css')); ?>" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo e(asset('css/theme.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('css/theme-2026.css')); ?>" rel="stylesheet" media="all">
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
    <?php echo $__env->yieldPushContent('css'); ?>
</head>

<body class="theme-2026">
    <div id="app">
        <a class="visually-hidden-focusable skip-link" href="#main-content">Skip to main content</a>

        <div class="page-wrapper">
            <!-- MENU SIDEBAR-->
            <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- END MENU SIDEBAR-->
            <div class="page-container">
                <!-- HEADER DESKTOP-->
                <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <!-- END HEADER -->

                <main id="main-content" class="main-content">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">
                            <?php echo $__env->yieldContent('content'); ?>
                            <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
    
    <script src="<?php echo e(asset('vendor/bootstrap-5.3.8.bundle.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('vendor/chartjs/chart.umd.js-4.5.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap5-init.js')); ?>"></script>
    <script src="<?php echo e(asset('js/main-vanilla.js')); ?>"></script>
    <script src="<?php echo e(asset('js/modern-plugins.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
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
        <?php if(session('success')): ?>
            toastr.success('<?php echo e(session('success')); ?>', 'Success');
        <?php endif; ?>

        <?php if(session('error')): ?>
            toastr.error('<?php echo e(session('error')); ?>', 'Error');
        <?php endif; ?>

        <?php if(session('warning')): ?>
            toastr.warning('<?php echo e(session('warning')); ?>', 'Warning');
        <?php endif; ?>

        <?php if(session('info')): ?>
            toastr.info('<?php echo e(session('info')); ?>', 'Info');
        <?php endif; ?>
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\PharmaDesk\resources\views/layouts/app.blade.php ENDPATH**/ ?>