<!--  Header Start -->
<header class="topbar">
    <div class="with-vertical">
        <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
                <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                    <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>

            {{-- ============================================
                 INFO DI HEADER — Sesuai Role
                 ============================================ --}}
            <ul class="navbar-nav quick-links d-none d-lg-flex align-items-center">
                @php
                    $user = auth()->user();
                @endphp

                @if ($user->isSuperAdmin())
                    Selamat Datang&nbsp;<span class="fw-semibold">Super&nbsp;Admin</span>&nbsp;SMK Syafi'i Akrom
                @elseif ($user->isPimpinan())
                    Selamat Datang&nbsp;<span
                        class="fw-semibold">{{ $user->isKepalaSekolah() ? 'Kepala Sekolah' : 'Waka Bidang Sarana dan Prasarana' }}</span>
                    &nbsp;SMK Syafi'i Akrom
                @else
                    Selamat Datang Admin&nbsp;<span class="fw-semibold">{{ $user->jurusan?->singkatan ?? '-' }}</span>&nbsp;SMK
                    Syafi'i Akrom
                @endif
            </ul>

            <div class="d-block d-lg-none py-4">
                <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img">
                    <img src="{{ asset('assets/image/sa.png') }}" class="dark-logo" alt="Logo-Dark" width="45"
                        height="45" />
                </a>
            </div>

            <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0" href="javascript:void(0)"
                data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="ti ti-dots fs-7"></i>
            </a>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between">
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                        <!-- start profile Dropdown -->
                        <li class="nav-item dropdown">
                            @php
                                // Foto profil: pimpinan & super admin pakai user-1, sisanya user-7
                                $fotoProfil =
                                    $user->isSuperAdmin() || $user->isPimpinan() ? 'user-1.jpg' : 'user-7.jpg';

                                // Label role
                                $labelRole = match ($user->role) {
                                    'super_admin' => 'Super Admin',
                                    'kepala_sekolah' => 'Kepala Sekolah',
                                    'waka' => 'Wakil Kepala Sekolah',
                                    'admin' => 'Admin ' . ($user->jurusan?->singkatan ?? ''),
                                    'user' => 'User ' . ($user->jurusan?->singkatan ?? ''),
                                    default => '-',
                                };

                                // Nama yang ditampilin (pakai kepala_jurusan kalau ada, fallback ke name)
                                $namaTampil = $user->jurusan?->kepala_jurusan ?? $user->name;
                            @endphp

                            <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="user-profile-img">
                                        <img src="{{ asset('assets/image/' . $fotoProfil) }}" class="rounded-circle"
                                            width="35" height="35" alt="modernize-img" />
                                    </div>
                                </div>
                            </a>

                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop1">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5 class="mb-0 fs-5 fw-semibold">Profile</h5>
                                    </div>

                                    {{-- Info User --}}
                                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <img src="{{ asset('assets/image/' . $fotoProfil) }}" class="rounded-circle"
                                            width="80" height="80" alt="modernize-img" />
                                        <div class="ms-3">
                                            <h5 class="mb-1 fs-3">{{ $namaTampil }}</h5>
                                            <span class="mb-1 d-block text-muted small">{{ $labelRole }}</span>
                                            <p class="mb-0 d-flex align-items-center gap-2">
                                                <i class="ti ti-mail fs-4"></i> {{ $user->email }}
                                            </p>

                                            @if ($user->jurusan && !$user->isPimpinan())
                                                <p class="mb-0 d-flex align-items-center gap-2 mt-1">
                                                    <i class="ti ti-building fs-4"></i>
                                                    <span class="small">{{ $user->jurusan->nama }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Tombol Logout --}}
                                    <div class="d-grid py-4 px-7 pt-8">
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button class="btn btn-danger w-100">
                                                <i class="ti ti-logout me-1"></i> Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- end profile Dropdown -->

                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!--  Header End -->
