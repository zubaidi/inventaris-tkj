@extends('admin.layouts.app-layout')
@section('title', 'Data Inventaris')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Bagian Kiri: Judul dan Breadcrumb -->
                        <div>
                            <h3 class="fw-semibold mb-1">Data Inventaris</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 small">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Inventaris</li>
                                </ol>
                            </nav>
                        </div>

                        <!-- Bagian Kanan: Tombol yang Dikelompokkan agar Berjejer -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#modalImport">
                                <i class="ti ti-file-arrow-right fs-5"></i> Import Data
                            </button>
                            <a href="{{ route('admin.inventaris.create') }}"
                                class="btn btn-primary d-flex align-items-center gap-2">
                                <i class="ti ti-plus fs-5"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <form method="GET" class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                            {{-- Preserve filter yang udah ada (kecuali search, per_page, page) --}}
                            @foreach (request()->except('search', 'per_page', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            {{-- Baris per Halaman — kiri --}}
                            <div class="d-flex align-items-center gap-2">
                                <select name="per_page" class="form-select form-select-sm" style="width: auto;"
                                    onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="small text-muted text-nowrap">baris per halaman</span>
                            </div>

                            {{-- Search Box — kanan --}}
                            <div class="d-flex align-items-center gap-2 ms-md-auto">
                                <div class="input-group input-group-sm" style="width: 280px;">
                                    <span class="input-group-text bg-white">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari barang, no inventaris..." value="{{ request('search') }}"
                                        maxlength="100" autocomplete="off">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Cari
                                </button>
                                @if (request('search'))
                                    <a href="{{ route('admin.inventaris.index', request()->except('search', 'page')) }}"
                                        class="btn btn-outline-secondary btn-sm" title="Clear search">
                                        <i class="ti ti-x"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                        <table class="table table-hover table-lg" id="tabel-inventaris">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">No</th>
                                    <th class="text-nowrap">Kode</th>
                                    <th>Nama Item</th>
                                    <th class="text-nowrap">Jumlah</th>
                                    <th class="text-nowrap">Kondisi</th>
                                    <th class="text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventaris as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->no_inventaris }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td class="text-center">
                                            {{ $item->volume }} {{ $item->satuan }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->kondisi === 'Baik')
                                                <span class="badge bg-success-subtle text-success">Baik</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Rusak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.inventaris.show', $item->id) }}"
                                                    class="btn btn-sm btn-info" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.inventaris.edit', $item->id) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.inventaris.destroy', $item->id) }}"
                                                    method="POST" class="form-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                        title="Hapus" data-nama="{{ $item->nama_barang }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                                @if ($inventaris->hasPages())
                                    {{ $inventaris->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!auth()->user()->isSuperAdmin())
        <div class="modal fade" id="modalImport" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.inventaris.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-upload me-1"></i> Import Inventaris
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            {{-- Info jurusan --}}
                            <div class="alert alert-primary small mb-3">
                                <i class="ti ti-school me-1"></i>
                                Import ke jurusan: <strong>{{ auth()->user()->jurusan->nama ?? '-' }}</strong>
                            </div>

                            {{-- Step 1: Download Template --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">1. Download Template</label>
                                <a href="{{ route('admin.inventaris.template') }}"
                                    class="btn btn-outline-primary btn-sm w-100">
                                    <i class="ti ti-download me-1"></i> Download Template
                                    ({{ auth()->user()->jurusan->singkatan ?? '-' }})
                                </a>
                                <small class="text-muted d-block mt-1">
                                    Template udah include Lab & Sumber Dana jurusan lu.
                                </small>
                            </div>

                            {{-- Step 2: Upload --}}
                            <div>
                                <label for="file" class="form-label fw-semibold">2. Upload File</label>
                                <input type="file" name="file" id="file"
                                    class="form-control form-control-sm @error('file') is-invalid @enderror"
                                    accept=".xlsx,.xls" required>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: xlsx/xls, max 5MB</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ti ti-upload me-1"></i> Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <script>
        new DataTable('#tabel-inventaris', {
            language: {
                url: "{{ asset('assets/js/id.json') }}"
            },

            paging: false,
            searching: false,
            info: false,

            columnDefs: [{
                targets: 0,
                orderable: false,
                searchable: false,
            }],

            order: [
                [2, 'asc']
            ],
        });

        document.addEventListener('click', function(event) {
            const deleteButton = event.target.closest('.btn-delete');
            if (!deleteButton) return;

            const form = deleteButton.closest('.form-delete');
            const nama = deleteButton.dataset.nama || 'data ini';

            Swal.fire({
                title: 'Yakin hapus?',
                html: `Data <b>${nama}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-trash"></i> Ya, Hapus',
                cancelButtonText: '<i class="ti ti-x"></i> Batal',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
