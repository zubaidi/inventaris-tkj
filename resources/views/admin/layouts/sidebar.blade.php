<!-- Sidebar Start -->
<aside class="left-sidebar with-vertical">
    <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="../main/index.html" class="text-nowrap logo-img">
                <img src="{{ asset('assets/image/dark-logo.svg') }}" class="dark-logo" alt="Logo-Dark" />
                <img src="{{ asset('assets/image/dark-logo.svg') }}" class="light-logo" alt="Logo-light" />
            </a>
            <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="" id="get-url" aria-expanded="false">
                        <span>
                            <i class="ti ti-aperture"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- ---------------------------------- -->
                <!-- Inventaris -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Master</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.labs.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-home"></i>
                        </span>
                        <span class="hide-menu">Atur Labs</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.sumber-dana.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-building-bank"></i>
                        </span>
                        <span class="hide-menu">Atus Sumber Dana</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="../main/app-chat.html" aria-expanded="false">
                        <span>
                            <i class="ti ti-box-seam"></i>
                        </span>
                        <span class="hide-menu">Atur Inventaris</span>
                    </a>
                </li>
                <!-- ---------------------------------- -->
                <!-- Pengaturan -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Pengaturan</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="../main/page-pricing.html" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Pengguna</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="../main/page-faq.html" aria-expanded="false">
                        <span>
                            <i class="ti ti-database-export"></i>
                        </span>
                        <span class="hide-menu">Backup Data</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="../main/page-account-settings.html" aria-expanded="false">
                        <span>
                            <i class="ti ti-file-spreadsheet"></i>
                        </span>
                        <span class="hide-menu">Eksport Data CSV</span>
                    </a>
                </li>
        </nav>

        <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
            <div class="hstack gap-3">
                <div class="john-img">
                    <img src="{{ asset('assets/image/user-1.jpg') }}" class="rounded-circle" width="40"
                        height="40" alt="modernize-img" />
                </div>
                <div class="john-title">
                    <h6 class="mb-0 fs-4 fw-semibold">Mathew</h6>
                    <span class="fs-2">Administrator</span>
                </div>
                <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button"
                    aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                    <i class="ti ti-power fs-6"></i>
                </button>
            </div>
        </div>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
    </div>
</aside>
<!--  Sidebar End -->
