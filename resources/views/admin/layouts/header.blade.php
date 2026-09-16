<!--  Header Start -->
<header class="topbar">
    <div class="with-vertical"><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Header -->
        <!-- ---------------------------------- -->
        <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
                <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                    <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav quick-links d-none d-lg-flex align-items-center">

            </ul>

            <div class="d-block d-lg-none py-4">
                <a href="../main/index.html" class="text-nowrap logo-img">
                    <img src="{{ asset('assets/image/dark-logo.svg') }}" class="dark-logo" alt="Logo-Dark" />
                    <img src="{{ asset('assets/image/dark-logo.svg') }}" class="light-logo" alt="Logo-light" />
                </a>
            </div>
            <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0" href="javascript:void(0)"
                data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="ti ti-dots fs-7"></i>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between">

                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                        <!-- ------------------------------- -->
                        <!-- start language Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item nav-icon-hover-bg rounded-circle">
                            <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                <i class="ti ti-moon moon"></i>
                            </a>
                            <a class="nav-link sun light-layout" href="javascript:void(0)">
                                <i class="ti ti-sun sun"></i>
                            </a>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- end language Dropdown -->
                        <!-- ------------------------------- -->

                        <!-- ------------------------------- -->
                        <!-- start profile Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item dropdown">
                            <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="user-profile-img">
                                        <img src="{{ asset('assets/image/user-1.jpg') }}" class="rounded-circle"
                                            width="35" height="35" alt="modernize-img" />
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop1">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                    </div>
                                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <img src="{{ asset('assets/image/user-1.jpg') }}" class="rounded-circle"
                                            width="80" height="80" alt="modernize-img" />
                                        <div class="ms-3">
                                            <h5 class="mb-1 fs-3">Mathew Anderson</h5>
                                            <span class="mb-1 d-block">Designer</span>
                                            <p class="mb-0 d-flex align-items-center gap-2">
                                                <i class="ti ti-mail fs-4"></i> <a href="/cdn-cgi/l/email-protection"
                                                    class="__cf_email__"
                                                    data-cfemail="a5cccbc3cae5c8cac1c0d7cbccdfc08bc6cac8">[email&#160;protected]</a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <a href="{{ route('logout') }}"
                                            class="btn btn-outline-primary">Log Out</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- end profile Dropdown -->
                        <!-- ------------------------------- -->
                    </ul>
                </div>
            </div>
        </nav>
        <!-- ---------------------------------- -->
        <!-- End Vertical Layout Header -->
        <!-- ---------------------------------- -->
    </div>
</header>
<!--  Header End -->
