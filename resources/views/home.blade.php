<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/image/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
    <title>@yield('title', 'Sistem Inventaris TKJ')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/tabler-icons/tabler-icons.css') }}">

    <style>
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Tab Styling */
        .nav-tabs-modern {
            border-bottom: 2px solid #e2e8f0;
            gap: 4px;
        }

        .nav-tabs-modern .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 16px;
            padding: 12px 24px;
            border-radius: 0;
            transition: all 0.2s;
        }

        .nav-tabs-modern .nav-link i {
            font-size: 0.9rem;
        }

        .nav-tabs-modern .nav-link:hover {
            color: #3b82f6;
            border-bottom-color: #cbd5e1;
        }

        .nav-tabs-modern .nav-link.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            background: transparent;
        }

        /* Lab Card Hover */
        .lab-card {
            transition: all 0.2s ease;
            cursor: pointer;
            border-radius: 12px;
        }

        .lab-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.12) !important;
        }

        .lab-card:hover .ti-arrow-narrow-right {
            transform: translateX(4px);
            transition: transform 0.2s ease;
        }

        @media (max-width: 767.98px) {
            .table-wrapper {
                overflow-x: visible;
                background: transparent;
                box-shadow: none;
            }

            #tabel-inventaris thead {
                display: none;
            }

            #tabel-inventaris,
            #tabel-inventaris tbody,
            #tabel-inventaris tr,
            #tabel-inventaris td {
                display: block;
                width: 100%;
            }

            #tabel-inventaris tr {
                background: #fff;
                margin-bottom: 1rem;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
                padding: 1rem;
                border: 1px solid #f1f5f9;
            }

            #tabel-inventaris td {
                padding: 8px 0;
                border-bottom: 1px dashed #f1f5f9;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                text-align: right;
            }

            #tabel-inventaris td:last-child {
                border-bottom: none;
            }

            #tabel-inventaris td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                font-size: 0.8rem;
                text-transform: uppercase;
                text-align: left;
                flex-shrink: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center gap-2">
                <img src="{{ asset('assets/image/logo.png') }}" alt="TKJ" width="33" height="36">
                <span class="fw-bold text-dark">Inventaris TKJ</span>
            </a>
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="ti ti-login me-1"></i> Admin Area
            </a>
        </div>
    </nav>

    <!-- Header + Tabs -->
    <section class="bg-light pt-5 pb-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    {{-- Judul Halaman --}}
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold text-dark mb-2">Sistem Inventaris TKJ</h1>
                        <p class="text-muted mb-0">
                            Cari barang atau lihat data inventaris per ruang lab
                        </p>
                    </div>

                    {{-- Tabs Navigation --}}
                    <ul class="nav nav-tabs nav-tabs-modern justify-content-center" id="homeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-cari-btn" data-bs-toggle="tab"
                                data-bs-target="#tab-cari" type="button" role="tab" aria-controls="tab-cari"
                                aria-selected="true">
                                <i class="ti ti-search me-1"></i> Cari Item
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-ruang-btn" data-bs-toggle="tab" data-bs-target="#tab-ruang"
                                type="button" role="tab" aria-controls="tab-ruang" aria-selected="false">
                                <i class="ti ti-building me-1"></i> Lihat Per Ruang
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Tab Content -->
    <section class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="tab-content" id="homeTabsContent">
                        <div class="tab-pane fade show active" id="tab-cari" role="tabpanel"
                            aria-labelledby="tab-cari-btn">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body p-3">
                                    <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari barang, nomor inventaris, atau spesifikasi..."
                                            value="{{ $search ?? '' }}" autofocus>
                                        <button type="submit" class="btn btn-primary px-3">
                                            <i class="ti ti-search"></i> Cari
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Hasil Pencarian --}}
                            @if ($search)
                                <div class="mb-3 text-muted small">
                                    Menampilkan hasil untuk <strong>"{{ $search }}"</strong> —
                                    {{ $inventaris->total() }} data ditemukan
                                </div>

                                @if ($inventaris->total())
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="table-wrapper">
                                                <table class="table table-hover mb-0" id="tabel-inventaris">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="text-center" style="width: 50px;">No</th>
                                                            <th>No Inventaris</th>
                                                            <th>Nama Barang</th>
                                                            <th class="text-center">Kondisi</th>
                                                            <th>Lab</th>
                                                            <th class="text-end">Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($inventaris as $i => $item)
                                                            <tr>
                                                                {{-- Nomor urut global --}}
                                                                <td class="text-center" data-label="No">
                                                                    {{ $inventaris->firstItem() + $i }}
                                                                </td>
                                                                <td data-label="No Inventaris">
                                                                    {{ $item->no_inventaris }}
                                                                </td>
                                                                <td data-label="Nama Barang">{{ $item->nama_barang }}
                                                                </td>
                                                                <td class="text-center" data-label="Kondisi">
                                                                    @if ($item->kondisi === 'Baik')
                                                                        <span
                                                                            class="badge bg-success-subtle text-success">Baik</span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-danger-subtle text-danger">Rusak</span>
                                                                    @endif
                                                                </td>
                                                                <td data-label="Lab">{{ $item->lab->nama_lab ?? '-' }}
                                                                </td>
                                                                <td class="text-end fw-semibold" data-label="Harga">
                                                                    {{ $item->jumlah_total_rupiah }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white border-top py-3">
                                            <div
                                                class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                <small class="text-muted">
                                                    Menampilkan
                                                    <strong>{{ $inventaris->firstItem() ?? 0 }}</strong>
                                                    –
                                                    <strong>{{ $inventaris->lastItem() ?? 0 }}</strong>
                                                    dari
                                                    <strong>{{ $inventaris->total() }}</strong> barang
                                                </small>
                                                <div>
                                                    @if ($inventaris->hasPages())
                                                        {{ $inventaris->links() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body text-center py-5">
                                            <i class="ti ti-search-off fs-1 text-muted mb-3"></i>
                                            <h5 class="fw-semibold">Barang tidak ditemukan</h5>
                                            <p class="text-muted mb-0">Coba kata kunci lain, misal: AC, Printer, Router
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center py-5">
                                        <i class="ti ti-search fs-1 text-muted mb-3"></i>
                                        <h5 class="fw-semibold">Mulai ketik untuk mencari barang</h5>
                                        <p class="text-muted mb-0">Hasil pencarian akan muncul di sini</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="tab-ruang" role="tabpanel" aria-labelledby="tab-ruang-btn">
                            <div class="mb-3 text-muted small">
                                Pilih lab/ruang untuk melihat daftar barang di dalamnya
                            </div>
                            <div class="row g-3">
                                @forelse ($labs as $lab)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <a href="{{ route('home.per-ruang', $lab->id) }}"
                                            class="text-decoration-none">
                                            <div class="card border-0 shadow-sm h-100 lab-card">
                                                <div class="card-body p-3">
                                                    {{-- Icon --}}
                                                    <div class="rounded-3 bg-primary-subtle d-flex align-items-center justify-content-center mb-3"
                                                        style="width: 44px; height: 44px;">
                                                        <i class="ti ti-building fs-5 text-primary"></i>
                                                    </div>

                                                    {{-- Info Lab --}}
                                                    <h6 class="fw-semibold mb-1 text-dark">
                                                        {{ $lab->nama_lab }}
                                                    </h6>
                                                    <p class="text-muted mb-3" style="font-size: 0.75rem;">
                                                        {{ \Illuminate\Support\Str::limit($lab->lokasi ?? 'Lokasi tidak diset', 30) }}
                                                    </p>

                                                    {{-- Footer --}}
                                                    <div
                                                        class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                        <small class="text-muted">
                                                            <i class="ti ti-box" style="font-size: 0.75rem;"></i>
                                                            {{ $lab->inventaris_count }} item
                                                        </small>
                                                        <i class="ti ti-arrow-narrow-right text-primary"
                                                            style="font-size: 0.9rem;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body text-center py-5">
                                                <i class="ti ti-building-off fs-1 text-muted mb-3"></i>
                                                <h6 class="fw-semibold">Belum ada data lab</h6>
                                                <p class="text-muted small mb-0">Data lab akan muncul di sini</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.init.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/iconify-icon.min.js') }}"></script>

    {{-- Auto switch tab berdasarkan query string --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kalau ada ?search=... → aktifin tab Cari
            // Kalau ada ?tab=ruang → aktifin tab Ruang
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');

            if (tab === 'ruang') {
                const ruangTab = document.getElementById('tab-ruang-btn');
                if (ruangTab) {
                    const bsTab = new bootstrap.Tab(ruangTab);
                    bsTab.show();
                }
            }
        });
    </script>
</body>

</html>
