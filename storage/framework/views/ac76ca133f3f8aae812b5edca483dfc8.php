<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="header-wrap">
                <button class="sidebar-toggle js-sidebar-toggle" type="button" aria-label="Toggle navigation"
                    aria-expanded="false" aria-controls="main-sidebar">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
                <form class="form-header" role="search" onsubmit="return false">
                    <i class="fa-solid fa-magnifying-glass form-header__icon" aria-hidden="true"></i>
                    <input class="au-input au-input--xl" type="search" name="search" placeholder="Search anything…"
                        aria-label="Search">
                    <kbd class="form-header__hint" aria-hidden="true">⌘K</kbd>
                </form>
                <div class="header-button">
                    <div class="noti-wrap">
                        <div class="noti__item js-item-menu" role="button" tabindex="0" aria-haspopup="true"
                            aria-label="Messages">
                            <i class="fa-solid fa-comment-dots"></i>
                            <span class="quantity">1</span>
                            <div class="mess-dropdown js-dropdown">
                                <div class="mess__title">
                                    <p>Messages</p>
                                </div>
                                <div class="mess__item">
                                    <div class="image img-cir img-40"><img src="images/icon/avatar-06.jpg"
                                            alt=""></div>
                                    <div class="content">
                                        <h6>Michelle Moreno</h6>
                                        <p>Have sent a photo</p>
                                        <span class="time">3 min ago</span>
                                    </div>
                                </div>
                                <div class="mess__item">
                                    <div class="image img-cir img-40"><img src="images/icon/avatar-04.jpg"
                                            alt=""></div>
                                    <div class="content">
                                        <h6>Diane Myers</h6>
                                        <p>You are now connected</p>
                                        <span class="time">Yesterday</span>
                                    </div>
                                </div>
                                <div class="mess__footer"><a href="#">View all messages</a></div>
                            </div>
                        </div>
                        <div class="noti__item js-item-menu" role="button" tabindex="0" aria-haspopup="true"
                            aria-label="Emails">
                            <i class="fa-solid fa-envelope"></i>
                            <span class="quantity">1</span>
                            <div class="email-dropdown js-dropdown">
                                <div class="email__title">
                                    <p>Emails</p>
                                </div>
                                <div class="email__item">
                                    <div class="image img-cir img-40"><img src="images/icon/avatar-06.jpg"
                                            alt=""></div>
                                    <div class="content">
                                        <p>Meeting about new dashboard…</p>
                                        <span>Cynthia Harvey, 3 min ago</span>
                                    </div>
                                </div>
                                <div class="email__item">
                                    <div class="image img-cir img-40"><img src="images/icon/avatar-05.jpg"
                                            alt=""></div>
                                    <div class="content">
                                        <p>Quarterly report draft</p>
                                        <span>John Doe, Yesterday</span>
                                    </div>
                                </div>
                                <div class="email__footer"><a href="#">See all emails</a></div>
                            </div>
                        </div>
                        <div class="noti__item js-item-menu" role="button" tabindex="0" aria-haspopup="true"
                            aria-label="Notifications">
                            <i class="fa-solid fa-bell"></i>
                            <span class="quantity">3</span>
                            <div class="notifi-dropdown js-dropdown">
                                <div class="notifi__title">
                                    <p>Notifications</p>
                                </div>
                                <div class="notifi__item">
                                    <div class="bg-c1 img-cir img-40"><i class="fa-solid fa-envelope-open"></i></div>
                                    <div class="content">
                                        <p>You have a new email</p>
                                        <span class="date">Today, 14:30</span>
                                    </div>
                                </div>
                                <div class="notifi__item">
                                    <div class="bg-c2 img-cir img-40"><i class="fa-solid fa-id-card"></i></div>
                                    <div class="content">
                                        <p>Your account was updated</p>
                                        <span class="date">Yesterday</span>
                                    </div>
                                </div>
                                <div class="notifi__item">
                                    <div class="bg-c3 img-cir img-40"><i class="fa-solid fa-file-lines"></i></div>
                                    <div class="content">
                                        <p>Report uploaded successfully</p>
                                        <span class="date">2 days ago</span>
                                    </div>
                                </div>
                                <div class="notifi__footer"><a href="#">All notifications</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="account-wrap">
                        <div class="account-item clearfix js-item-menu" role="button" tabindex="0"
                            aria-haspopup="true" aria-label="Account menu">
                            <div class="image"><img src="images/icon/avatar-01.jpg" alt="John Doe"></div>
                            <div class="content"><a class="js-acc-btn" href="#"><?php echo e(auth()->user()->name); ?></a></div>
                            <div class="account-dropdown js-dropdown">
                                <div class="info clearfix">
                                    <div class="image"><a href="#"><img src="images/icon/avatar-01.jpg"
                                                alt=""></a></div>
                                    <div class="content">
                                        <h5 class="name"><a href="#"><?php echo e(auth()->user()->name); ?></a></h5>
                                        <span class="email"><?php echo e(auth()->user()->email); ?></span>
                                    </div>
                                </div>
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="#">
                                            <i class="fa-solid fa-user"></i>Account
                                        </a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="<?php echo e(route('settings.index')); ?>">
                                            <i class="fa-solid fa-gear"></i>Settings
                                        </a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="#">
                                            <i class="fa-solid fa-sack-dollar"></i>Billing
                                        </a>
                                    </div>
                                </div>
                                <div class="account-dropdown__footer">
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="btn btn-link text-danger ms-3 text-decoration-none p-0 border-0 align-baseline">
                                            <i class="fa-solid fa-power-off"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\laragon\www\PharmaDesk\resources\views/layouts/header.blade.php ENDPATH**/ ?>