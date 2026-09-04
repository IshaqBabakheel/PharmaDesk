<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <meta name="theme-color" content="#4272d7" />
    <link href="css/font-face.css" rel="stylesheet" media="all" />
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <link href="<?php echo e(asset('vendor/fontawesome-7.2.0/css/all.min.css')); ?>" rel="stylesheet" media="all" />
    <link href="<?php echo e(asset('vendor/bootstrap-5.3.8.min.css')); ?>" rel="stylesheet" media="all" />
    <link href="<?php echo e(asset('vendor/css-hamburgers/hamburgers.min.css')); ?>" rel="stylesheet" media="all" />
    <link href="<?php echo e(asset('css/theme.css')); ?>" rel="stylesheet" media="all" />
    <link href="<?php echo e(asset('css/theme-2026.css')); ?>" rel="stylesheet" media="all" />
</head>
<body class="theme-2026 auth-page">
    <div id="app">
            <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="<?php echo e(asset('js/vanilla-utils.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/bootstrap-5.3.8.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap5-init.js')); ?>"></script>
    <script src="<?php echo e(asset('js/main-vanilla.js')); ?>"></script>
    <script src="<?php echo e(asset('js/modern-plugins.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\PharmaDesk\resources\views/auth/layouts/app.blade.php ENDPATH**/ ?>