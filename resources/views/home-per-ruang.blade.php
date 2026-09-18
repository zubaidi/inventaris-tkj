<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/image/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
    <title>{{ $lab->nama_lab }} — Inventaris TKJ</title>
    <link rel="stylesheet" href="{{ asset('assets/tabler-icons/tabler-icons.css') }}">

    <style>
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 767.98px) {
            .table-wrapper {
                overflow-x: visible;
            }

            #tabel-detail thead {
                display: none;
            }

            #tabel-detail,
            #tabel-detail tbody,
            #tabel-detail tr,
            #tabel-detail td {
                display: block;
                width: 100%;
            }

            #tabel-detail tr {
                background: #fff;
                margin-bottom: 1rem;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
                padding: 1rem;
                border: 1px solid #f1f5f9;
            }

            #tabel-detail td {
                padding: 8px 0;
                border-bottom: 1px dashed #f1f5f9;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                text-align: right;
            }

            #tabel-detail td:last-child {
                border-bottom: none;
            }

            #tabel-detail td::before {
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
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center gap-2">
                <img src="{{ asset('assets/image/logo.png') }}" alt="TKJ" width="34" height="36">
                <span class="fw-bold text-dark">Inventaris TKJ</span>
            </a>
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="ti ti-login me-1"></i> Admin Area
            </a>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class="ti ti-home"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}?tab=ruang" class="text-decoration-none">
                            Per Ruang
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $lab->nama_lab }}</li>
                </ol>
            </nav>

            {{-- Info Lab Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center g-3">
                        {{-- Kiri: Icon + Info Lab --}}
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center shrink-0"
                                    style="width: 56px; height: 56px;">
                                    <i class="ti ti-building fs-5 text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $lab->nama_lab }}</h5>
                                    <p class="mb-0 text-muted small">
                                        <i class="ti ti-map-pin me-1"></i>
                                        {{ $lab->lokasi ?? 'Lokasi tidak diset' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Total Aset --}}
                        <div class="col-md-4 text-md-end">
                            <small class="text-muted d-block mb-1">Total Nilai Asset</small>
                            <h4 class="fw-bold mb-0 text-primary">
                                Rp {{ number_format((float) $grandTotal, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Barang --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-wrapper">
                        <table class="table table-hover mb-0" id="tabel-detail">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th>No Inventaris</th>
                                    <th>Nama Barang</th>
                                    <th>Spesifikasi</th>
                                    <th class="text-center">Volume</th>
                                    <th class="text-center">Kondisi</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventaris as $i => $item)
                                    <tr>
                                        <td class="text-center" data-label="No">{{ $i + 1 }}</td>
                                        <td data-label="No Inventaris">{{ $item->no_inventaris }}</td>
                                        <td class="fw-semibold" data-label="Nama Barang">{{ $item->nama_barang }}</td>
                                        <td data-label="Spesifikasi">{{ $item->spesifikasi ?? '-' }}</td>
                                        <td class="text-center" data-label="Volume">
                                            <span class="badge bg-primary-subtle text-primary">
                                                {{ $item->volume }} {{ $item->satuan }}
                                            </span>
                                        </td>
                                        <td class="text-center" data-label="Kondisi">
                                            @if ($item->kondisi === 'Baik')
                                                <span class="badge bg-success-subtle text-success">Baik</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Rusak</span>
                                            @endif
                                        </td>
                                        <td class="text-end" data-label="Harga Satuan">
                                            Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end fw-semibold" data-label="Jumlah">
                                            Rp
                                            {{ number_format((float) ($item->volume * $item->harga_satuan), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="ti ti-box-off fs-1 text-muted d-block mb-2"></i>
                                            <span class="text-muted">Belum ada barang di {{ $lab->nama_lab }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <small class="text-muted">
                            Menampilkan
                            <strong>{{ $inventaris->firstItem() ?? 0 }}</strong>
                            –
                            <strong>{{ $inventaris->lastItem() ?? 0 }}</strong>
                            dari
                            <strong>{{ $inventaris->total() }}</strong> barang
                        </small>
                        <div>
                            {{-- Pagination cuma muncul kalau > 1 halaman --}}
                            @if ($inventaris->hasPages())
                                {{ $inventaris->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.init.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/iconify-icon.min.js') }}"></script>
</body>

</html>
