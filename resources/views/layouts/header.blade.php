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
                    {{-- <div class="noti-wrap">
                        <div
                            class="noti__item js-item-menu"
                            role="button"
                            tabindex="0"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-label="Notifications"
                        >
                            <i class="fa-solid fa-bell"></i>

                            <span
                                class="quantity d-none"
                                id="notificationBadge"
                            >0</span>

                            <div class="notifi-dropdown js-dropdown">

                                <div class="notifi__title">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Notifications</p>

                                        <button
                                            type="button"
                                            id="markAllNotificationsRead"
                                            class="btn btn-sm btn-link p-0"
                                        >
                                            Mark all read
                                        </button>
                                    </div>
                                </div>

                                <div id="headerNotificationList">
                                    <div class="notifi__item">
                                        <div class="bg-c1 img-cir img-40">
                                            <i class="fa-solid fa-spinner fa-spin"></i>
                                        </div>

                                        <div class="content">
                                            <p>Loading notifications...</p>
                                            <span class="date"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="notifi__footer">
                                    <a href="{{ route('notifications.index') }}">
                                        All notifications
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div> --}}
                    <div class="dropdown">

                        <button
                            class="btn btn-link position-relative text-decoration-none"
                            type="button"
                            data-bs-toggle="dropdown"
                            id="notificationDropdown"
                            aria-expanded="false"
                        >
                            <i class="fas fa-bell fs-5"></i>

                            <span
                                id="notificationBadge"
                                class="position-absolute top-0 start-90 translate-middle badge rounded-pill bg-danger d-none"
                            ></span>
                        </button>

                        <div
                            class="dropdown-menu dropdown-menu-end shadow-sm"
                            style="width: 360px; max-height: 450px; overflow-y: auto;"
                            id="notificationMenu"
                        >
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <strong>Notifications</strong>

                                <a
                                    href="{{ route('notifications.index') }}"
                                    class="small"
                                >
                                    View All
                                </a>
                            </div>

                            <div id="notificationList">
                                <div class="text-center text-muted py-4">
                                    Loading...
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="account-wrap">
                        <div class="account-item clearfix js-item-menu" role="button" tabindex="0"
                            aria-haspopup="true" aria-label="Account menu">
                            <div class="image"><img src="images/icon/avatar-01.jpg" alt="John Doe"></div>
                            <div class="content"><a class="js-acc-btn" href="#">{{ auth()->user()->name }}</a></div>
                            <div class="account-dropdown js-dropdown">
                                <div class="info clearfix">
                                    <div class="image"><a href="#"><img src="images/icon/avatar-01.jpg"
                                                alt=""></a></div>
                                    <div class="content">
                                        <h5 class="name"><a href="#">{{ auth()->user()->name }}</a></h5>
                                        <span class="email">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="#">
                                            <i class="fa-solid fa-user"></i>Account
                                        </a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="{{ route('settings.index') }}">
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
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
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
