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
        /* Sidebar Styles */
        .menu-sidebar {
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3);
        }

        .navbar__list {
            padding: 5px 0 20px 0;
            margin: 0;
        }

        /* Section Labels */
        .navbar__list .section-label {
            padding: 10px 16px 4px 16px;
            margin-top: 6px;
            cursor: default;
            pointer-events: none;
            background: transparent !important;
            border: none !important;
        }

        .navbar__list .section-label span {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.25);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding-bottom: 5px;
        }

        /* Menu Items */
        .navbar__list li:not(.section-label) {
            list-style: none;
            margin: 1px 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .navbar__list li:not(.section-label) a {
            display: flex;
            align-items: center;
            padding: 7px 12px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 12.5px;
            font-weight: 400;
            border-radius: 6px;
            transition: all 0.2s ease;
            gap: 10px;
            letter-spacing: 0.3px;
        }

        .navbar__list li:not(.section-label) a i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.35);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .navbar__list li:not(.section-label) a span {
            flex: 1;
            font-size: 12.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Active State */
        .navbar__list li:not(.section-label).active > a {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.2) 0%, rgba(79, 70, 229, 0.05) 100%);
            border-left: 3px solid #4F46E5;
            padding-left: 9px;
        }

        .navbar__list li:not(.section-label).active > a i {
            color: #818CF8;
        }

        /* Hover State */
        .navbar__list li:not(.section-label) a:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .navbar__list li:not(.section-label) a:hover i {
            color: #818CF8;
        }

        /* Scrollbar Styling */
        .menu-sidebar__content {
            height: calc(100vh - 110px);
            overflow-y: auto;
            padding-bottom: 30px;
            scroll-behavior: smooth;
        }

        .menu-sidebar__content::-webkit-scrollbar {
            width: 3px;
        }

        .menu-sidebar__content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .menu-sidebar__content::-webkit-scrollbar-thumb {
            background: rgba(79, 70, 229, 0.4);
            border-radius: 10px;
        }

        .menu-sidebar__content::-webkit-scrollbar-thumb:hover {
            background: rgba(79, 70, 229, 0.6);
        }

        /* Logo */
        .logo {
            background: transparent;
            padding: 15px 15px 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo-link {
            display: block;
        }

        .logo-link div {
            background: #ffffff;
            padding: 8px 12px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }

        .logo-link div:hover {
            transform: scale(1.02);
        }

        /* Sidebar Close Button */
        .sidebar-close {
            display: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-sidebar {
                width: 240px;
            }
            
            .sidebar-close {
                display: block;
                position: absolute;
                top: 15px;
                right: 15px;
                background: none;
                border: none;
                color: #fff;
                font-size: 20px;
                cursor: pointer;
            }
            
            .navbar__list li:not(.section-label) a {
                padding: 6px 10px;
                font-size: 12px;
            }
            
            .navbar__list .section-label {
                padding: 8px 12px 3px 12px;
            }
            
            .navbar__list .section-label span {
                font-size: 9px;
            }
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


        //notification script
        // $(function () {
        //     loadHeaderNotifications();

        //     $('#markAllNotificationsRead').on('click', function (e) {
        //         e.preventDefault();
        //         e.stopPropagation();

        //         $.ajax({
        //             url: <?php echo json_encode(route('notifications.read-all'), 15, 512) ?>,
        //             type: 'PATCH',
        //             data: {
        //                 _token: <?php echo json_encode(csrf_token(), 15, 512) ?>
        //             },
        //             success: function () {
        //                 loadHeaderNotifications();
        //             }
        //         });
        //     });

        //     $(document).on('click', '.header-notification-link', function () {
        //         const id = $(this).data('id');

        //         $.ajax({
        //             url: <?php echo json_encode(url('/notifications'), 15, 512) ?> + '/' + id + '/read',
        //             type: 'PATCH',
        //             data: {
        //                 _token: <?php echo json_encode(csrf_token(), 15, 512) ?>
        //             }
        //         });
        //     });

        //     function loadHeaderNotifications() {
        //         $.ajax({
        //             url: <?php echo json_encode(route('notifications.unread'), 15, 512) ?>,
        //             type: 'GET',
        //             success: function (response) {
        //                 updateNotificationBadge(response.count);
        //                 renderNotifications(response.notifications);
        //             },
        //             error: function () {
        //                 $('#headerNotificationList').html(`
        //                     <div class="notifi__item">
        //                         <div class="bg-c3 img-cir img-40">
        //                             <i class="fa-solid fa-triangle-exclamation"></i>
        //                         </div>
        //                         <div class="content">
        //                             <p>Unable to load notifications</p>
        //                             <span class="date"></span>
        //                         </div>
        //                     </div>
        //                 `);
        //             }
        //         });
        //     }

        //     function updateNotificationBadge(count) {
        //         const badge = $('#notificationBadge');

        //         if (count > 0) {
        //             badge
        //                 .text(count > 99 ? '99+' : count)
        //                 .removeClass('d-none');
        //         } else {
        //             badge
        //                 .text('0')
        //                 .addClass('d-none');
        //         }
        //     }

        //     function renderNotifications(notifications) {
        //         const container = $('#headerNotificationList');

        //         container.empty();

        //         if (!notifications.length) {
        //             container.html(`
        //                 <div class="notifi__item">
        //                     <div class="bg-c1 img-cir img-40">
        //                         <i class="fa-regular fa-bell-slash"></i>
        //                     </div>
        //                     <div class="content">
        //                         <p>No new notifications</p>
        //                         <span class="date">You're all caught up</span>
        //                     </div>
        //                 </div>
        //             `);

        //             return;
        //         }

        //         notifications.forEach(function (notification) {
        //             const item = `
        //                 <div class="notifi__item">
        //                     <div class="${notificationColor(notification.type)} img-cir img-40">
        //                         <i class="${notificationIcon(notification.type)}"></i>
        //                     </div>

        //                     <div class="content">
        //                         <p>
        //                             <a
        //                                 href="${escapeAttribute(notification.url)}"
        //                                 class="header-notification-link"
        //                                 data-id="${escapeAttribute(notification.id)}"
        //                                 style="color: inherit; text-decoration: none;"
        //                             >
        //                                 ${escapeHtml(notification.title)}
        //                             </a>
        //                         </p>

        //                         <span class="date">
        //                             ${escapeHtml(notification.time || '')}
        //                         </span>
        //                     </div>
        //                 </div>
        //             `;

        //             container.append(item);
        //         });
        //     }

        //     function notificationIcon(type) {
        //         switch (type) {
        //             case 'low_stock':
        //                 return 'fa-solid fa-box-open';

        //             case 'expiry':
        //                 return 'fa-solid fa-calendar-days';

        //             case 'expired':
        //                 return 'fa-solid fa-triangle-exclamation';

        //             case 'customer_due':
        //                 return 'fa-solid fa-user-clock';

        //             case 'supplier_due':
        //                 return 'fa-solid fa-truck-clock';

        //             default:
        //                 return 'fa-solid fa-bell';
        //         }
        //     }

        //     function notificationColor(type) {
        //         switch (type) {
        //             case 'low_stock':
        //                 return 'bg-c1';

        //             case 'expiry':
        //                 return 'bg-c2';

        //             case 'expired':
        //                 return 'bg-c3';

        //             case 'customer_due':
        //                 return 'bg-c2';

        //             case 'supplier_due':
        //                 return 'bg-c1';

        //             default:
        //                 return 'bg-c1';
        //         }
        //     }

        //     function escapeHtml(value) {
        //         return $('<div>').text(value ?? '').html();
        //     }

        //     function escapeAttribute(value) {
        //         return String(value ?? '')
        //             .replace(/&/g, '&amp;')
        //             .replace(/"/g, '&quot;')
        //             .replace(/</g, '&lt;')
        //             .replace(/>/g, '&gt;');
        //     }
        // });
        $(function () {

            loadNotifications();

            $('#notificationDropdown').on('click', function () {
                loadNotifications();
            });

            function loadNotifications() {
                $.get(
                    "<?php echo e(route('notifications.unread')); ?>",
                    function (response) {

                        updateBadge(response.count);

                        const container = $('#notificationList');

                        container.empty();

                        if (!response.notifications.length) {
                            container.html(`
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                                    No new notifications.
                                </div>
                            `);

                            return;
                        }

                        response.notifications.forEach(function (notification) {

                            container.append(`
                                <a
                                    href="${escapeAttribute(notification.url)}"
                                    class="dropdown-item text-wrap py-3 border-bottom notification-link"
                                    data-id="${escapeAttribute(notification.id)}"
                                >
                                    <div class="fw-semibold">
                                        ${escapeHtml(notification.title)}
                                    </div>

                                    <div class="small text-muted mt-1">
                                        ${escapeHtml(notification.message)}
                                    </div>

                                    <div class="small text-secondary mt-1">
                                        ${escapeHtml(notification.time)}
                                    </div>
                                </a>
                            `);

                        });

                        $('.notification-link').on('click', function () {
                            markRead($(this).data('id'));
                        });
                    }
                );
            }

            function markRead(id) {
                $.ajax({
                    url: "<?php echo e(url('/notifications')); ?>/" + id + "/read",
                    method: 'PATCH',
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>"
                    }
                });
            }

            function updateBadge(count) {
                const badge = $('#notificationBadge');

                if (count > 0) {
                    badge
                        .text(count > 99 ? '99+' : count)
                        .removeClass('d-none');
                } else {
                    badge
                        .text('')
                        .addClass('d-none');
                }
            }

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            function escapeAttribute(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
            }

        });

        $(document).ready(function() {
    
            // ============================================
            // 1. AUTO-SCROLL TO ACTIVE ITEM
            // ============================================
            function scrollToActiveItem() {
                var sidebarContent = $('.menu-sidebar__content');
                var activeItem = $('.navbar__list li.active');
                
                if (activeItem.length) {
                    // Get the position of the active item within the sidebar
                    var itemTop = activeItem[0].offsetTop;
                    var itemHeight = activeItem.outerHeight();
                    var containerHeight = sidebarContent.height();
                    
                    // Calculate scroll position (center the item if possible)
                    var scrollPosition = itemTop - (containerHeight / 2) + (itemHeight / 2);
                    
                    // Ensure we don't scroll past the bottom
                    var maxScroll = sidebarContent[0].scrollHeight - containerHeight;
                    scrollPosition = Math.min(scrollPosition, maxScroll);
                    scrollPosition = Math.max(scrollPosition, 0);
                    
                    // Check if the item is already visible
                    var currentScroll = sidebarContent.scrollTop();
                    var itemVisible = (itemTop >= currentScroll && itemTop <= currentScroll + containerHeight - itemHeight);
                    
                    if (!itemVisible) {
                        // Smooth scroll to the active item
                        sidebarContent.animate({
                            scrollTop: scrollPosition
                        }, 400, 'swing');
                    }
                }
            }
            
            // ============================================
            // 2. INITIALIZE ON PAGE LOAD
            // ============================================
            // Wait for everything to render
            setTimeout(function() {
                scrollToActiveItem();
            }, 300);
            
            // Also scroll after fonts and images load
            $(window).on('load', function() {
                setTimeout(function() {
                    scrollToActiveItem();
                }, 200);
            });
            
            // ============================================
            // 3. HANDLE WINDOW RESIZE
            // ============================================
            var resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    scrollToActiveItem();
                }, 250);
            });
            
            // ============================================
            // 4. HANDLE DYNAMIC CONTENT CHANGES
            // ============================================
            // Listen for changes in the sidebar (for dynamic content)
            var observer = new MutationObserver(function() {
                scrollToActiveItem();
            });
            
            var sidebarContent = document.querySelector('.menu-sidebar__content');
            if (sidebarContent) {
                observer.observe(sidebarContent, {
                    childList: true,
                    subtree: true,
                    attributes: false
                });
            }
            
            // ============================================
            // 5. ACTIVE STATE MANAGEMENT
            // ============================================
            // When clicking a menu item, ensure proper active state
            $('.navbar__list li:not(.section-label) a').on('click', function() {
                var parentLi = $(this).closest('li');
                var parentUl = parentLi.closest('ul');
                
                // Remove active from siblings
                parentUl.find('> li').not(parentLi).removeClass('active');
                
                // Add active to current
                parentLi.addClass('active');
            });
            
            // ============================================
            // 6. KEYBOARD NAVIGATION (Accessibility)
            // ============================================
            $('.navbar__list li:not(.section-label) a').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    window.location.href = $(this).attr('href');
                }
            });
            
            // ============================================
            // 7. TOGGLE SIDEBAR ON MOBILE
            // ============================================
            $('.js-sidebar-toggle').on('click', function() {
                $('.menu-sidebar').toggleClass('sidebar-open');
            });
        });

        // ============================================
        // 8. UTILITY FUNCTION (Globally Available)
        // ============================================
        function scrollSidebarToActive() {
            setTimeout(function() {
                var sidebarContent = $('.menu-sidebar__content');
                var activeItem = $('.navbar__list li.active');
                
                if (activeItem.length) {
                    var itemTop = activeItem[0].offsetTop;
                    var containerHeight = sidebarContent.height();
                    var itemHeight = activeItem.outerHeight();
                    var scrollPosition = itemTop - (containerHeight / 2) + (itemHeight / 2);
                    var maxScroll = sidebarContent[0].scrollHeight - containerHeight;
                    scrollPosition = Math.min(scrollPosition, maxScroll);
                    scrollPosition = Math.max(scrollPosition, 0);
                    
                    sidebarContent.animate({
                        scrollTop: scrollPosition
                    }, 400);
                }
            }, 100);
        }

        window.scrollSidebarToActive = scrollSidebarToActive;
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\PharmaDesk\resources\views/layouts/app.blade.php ENDPATH**/ ?>