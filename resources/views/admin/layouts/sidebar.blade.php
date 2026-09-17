@push('style')
    <style>
        html[data-bs-theme="light"] .dark-logo { display: block !important; }
        html[data-bs-theme="light"] .light-logo { display: none !important; }
        html[data-bs-theme="dark"] .dark-logo { display: none !important; }
        html[data-bs-theme="dark"] .light-logo { display: block !important; }
    </style>
@endpush
<!-- Sidebar Start -->
<aside class="left-sidebar with-vertical">
    <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="../main/index.html" class="text-nowrap logo-img">
                <img src="{{ asset('assets/image/tkj2.png') }}" class="dark-logo" alt="Logo-Dark" width="105px"
                    height="32px" />
                <img src="{{ asset('assets/image/tkj1.png') }}" class="light-logo" alt="Logo-light" width="105px"
                    height="32px" />
            </a>
            <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                @php
                    $isAdmin = auth()->user()->isAdmin();
                @endphp

                @if ($isAdmin)
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Home</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-aperture"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                @endif

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Master</span>
                </li>

                @if ($isAdmin)
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('admin.labs.*') ? 'active' : '' }}"
                            href="{{ route('admin.labs.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-home"></i>
                            </span>
                            <span class="hide-menu">Atur Labs</span>
                        </a>
                    </li>
                @endif

                @if ($isAdmin)
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('admin.sumber-dana.*') ? 'active' : '' }}"
                            href="{{ route('admin.sumber-dana.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-building-bank"></i>
                            </span>
                            <span class="hide-menu">Atur Sumber Dana</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.inventaris.index') ? 'active' : '' }}"
                        href="{{ route('admin.inventaris.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-box-seam"></i>
                        </span>
                        <span class="hide-menu">Atur Inventaris</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.inventaris.rekap') ? 'active' : '' }}"
                        href="{{ route('admin.inventaris.rekap') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-clipboard-list"></i>
                        </span>
                        <span class="hide-menu">Rekap Inventaris</span>
                    </a>
                </li>
                @if ($isAdmin)
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Pengaturan</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}"
                            href="{{ route('admin.user.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="hide-menu">Pengguna</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('admin.backup.database') ? 'active' : '' }}"
                            href="{{ route('admin.backup.database') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-database-export"></i>
                            </span>
                            <span class="hide-menu">Backup Data</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('admin.backup.csv') ? 'active' : '' }}"
                            href="{{ route('admin.backup.csv') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-file-spreadsheet"></i>
                            </span>
                            <span class="hide-menu">Eksport Data CSV</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
            <div class="hstack gap-3">
                <div class="john-img">
                    @if (auth()->user()->isAdmin())
                        <img src="{{ asset('assets/image/user-1.jpg') }}" class="rounded-circle" width="40"
                            height="40" alt="modernize-img" />
                    @else
                        <img src="{{ asset('assets/image/user-7.jpg') }}" class="rounded-circle" width="40"
                            height="40" alt="modernize-img" />
                    @endif
                </div>
                <div class="john-title">
                    <h6 class="mb-0 fs-4 fw-semibold">{{ auth()->user()->name }}</h6>
                    <span class="fs-2">
                        {{ auth()->user()->isAdmin() ? 'Administrator' : 'User' }}
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="submit"
                        aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                        <i class="ti ti-power fs-6"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
    </div>
</aside>
<!--  Sidebar End -->
