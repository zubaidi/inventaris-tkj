<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/image/logo.png') }}" />

    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />

    <title>@yield('title', 'Sistem Inventaris TKJ')</title>
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/tabler-icons/tabler-icons.css') }}">

    {{-- Custom CSS untuk tabel responsif --}}
    <style>
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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
                <img src="{{ asset('assets/image/logo.png') }}" alt="TKJ" width="36" height="36">
                <span class="fw-bold text-dark">Inventaris TKJ</span>
            </a>
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="ti ti-login me-1"></i> Admin Area
            </a>
        </div>
    </nav>

    <!-- Search Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="h4 fw-bold mb-2">Cari Barang</h2>
                            <p class="text-muted small mb-4">
                                Ketik nama barang atau kode barang untuk melihat data inventaris
                            </p>
                            <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control form-control-lg"
                                    placeholder="Contoh: AC, Lab Komputer, INV-001..."
                                    value="{{ $search ?? '' }}" autofocus>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="ti ti-search"></i> Cari
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if ($search)
                        <div class="mb-3 text-muted small">
                            Menampilkan hasil pencarian untuk <strong>"{{ $search }}"</strong> — {{ $inventaris->count() }} data ditemukan
                        </div>

                        @if ($inventaris->count())
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
                                                @foreach ($inventaris as $item)
                                                    <tr>
                                                        <td class="text-center" data-label="No">{{ $loop->iteration }}</td>
                                                        <td data-label="No Inventaris">{{ $item->no_inventaris }}</td>
                                                        <td data-label="Nama Barang">{{ $item->nama_barang }}</td>
                                                        <td class="text-center" data-label="Kondisi">
                                                            @if ($item->kondisi === 'Baik')
                                                                <span class="badge bg-success-subtle text-success">Baik</span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger">Rusak</span>
                                                            @endif
                                                        </td>
                                                        <td data-label="Lab">{{ $item->lab->nama_lab ?? '-' }}</td>
                                                        <td class="text-end fw-semibold" data-label="Harga">
                                                            {{ $item->jumlah_total_rupiah }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center py-5">
                                    <i class="ti ti-search fs-1 text-muted mb-3"></i>
                                    <h5 class="fw-semibold">Barang tidak ditemukan</h5>
                                    <p class="text-muted mb-0">Coba kata kunci lain, misal: AC, Printer, Lab</p>
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
</body>

</html>
