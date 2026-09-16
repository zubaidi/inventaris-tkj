@extends('admin.layouts.app-layout')
@section('title', 'Data Lab')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert.min.css') }}">
    <style>
        #tabel-lab thead th {
            text-align: center;
            vertical-align: middle;
        }

        #modalEditLab .form-control {
            color: #212529 !important;
            -webkit-text-fill-color: #212529;
        }

        #modalEditLab .form-control:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
            -webkit-text-fill-color: #212529 !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">Data Ruang dan Lab TKJ</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 small">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.labs.index') }}" class="text-decoration-none">Atur Labs</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Index</li>
                                </ol>
                            </nav>
                        </div>
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#modalTambahLab">
                            <i class="ti ti-plus fs-5"></i> Tambah Data
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id="tabel-lab">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">No</th>
                                    <th>Nama Lab</th>
                                    <th>Lokasi</th>
                                    <th>Keterangan</th>
                                    <th class="text-nowrap">Jml Barang</th>
                                    <th class="text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labs as $lab)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $lab->nama_lab }}</td>
                                        <td>{{ $lab->lokasi ?? '-' }}</td>
                                        <td>{{ $lab->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-primary-subtle text-primary">{{ $lab->inventaris_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-warning btn-edit-lab"
                                                    title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditLab"
                                                    data-id="{{ $lab->id }}" data-nama_lab="{{ $lab->nama_lab }}"
                                                    data-lokasi="{{ $lab->lokasi }}"
                                                    data-keterangan="{{ $lab->keterangan }}">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                                <form action="{{ route('admin.labs.destroy', $lab->id) }}" method="POST"
                                                    class="form-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                        title="Hapus" data-nama="{{ $lab->nama_lab }}">
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
                </div>
            </div>
        </div>
    </div>
    {{-- modal tambah data lab --}}
    {{-- Modal Tambah Lab --}}
    <div class="modal fade" id="modalTambahLab" tabindex="-1" aria-labelledby="modalTambahLabLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.labs.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabLabel">
                            <i class="ti ti-plus me-1"></i> Tambah Lab Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Nama Lab --}}
                        <div class="mb-3">
                            <label for="nama_lab" class="form-label">
                                Nama Lab <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama_lab') is-invalid @enderror"
                                id="nama_lab" name="nama_lab" value="{{ old('nama_lab') }}" placeholder="Contoh: Lab 7"
                                required>
                            @error('nama_lab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi"
                                name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Gedung TKJ Lt.2">
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="3" placeholder="Contoh: Lab utama jurusan TKJ">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end modal tambah lab --}}
    {{-- modal edit lab --}}
    {{-- Modal Edit Lab --}}
    <div class="modal fade" id="modalEditLab" tabindex="-1" aria-labelledby="modalEditLabLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditLab" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabLabel">
                            <i class="ti ti-pencil me-1"></i> Edit Lab
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Nama Lab --}}
                        <div class="mb-3">
                            <label for="edit_nama_lab" class="form-label">
                                Nama Lab <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="edit_nama_lab" name="nama_lab"
                                placeholder="Contoh: Lab 7" required>
                        </div>

                        {{-- Lokasi --}}
                        <div class="mb-3">
                            <label for="edit_lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="edit_lokasi" name="lokasi"
                                placeholder="Contoh: Gedung TKJ Lt.2">
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-3">
                            <label for="edit_keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"
                                placeholder="Contoh: Lab utama jurusan TKJ"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end modal edit lab --}}
@endsection
@push('script')
    {{-- <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.init.js') }}"></script>
    <script>
        const table = new DataTable('#tabel-lab', {
            language: {
                url: "{{ asset('assets/js/id.json') }}"
            }
        });

        // Referensi elemen
        const modalEditEl = document.getElementById('modalEditLab');
        const formEditEl = document.getElementById('formEditLab');
        const inputNama = document.getElementById('edit_nama_lab');
        const inputLokasi = document.getElementById('edit_lokasi');
        const inputKet = document.getElementById('edit_keterangan');

        // === HANDLE KLIK EDIT ===
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit-lab');
            if (!btn) return;
            e.preventDefault();

            // Set action form
            formEditEl.action = `/admin/labs/${btn.dataset.id}`;

            // Set value ke input
            inputNama.value = btn.dataset.nama_lab || '';
            inputLokasi.value = btn.dataset.lokasi || '';
            inputKet.value = btn.dataset.keterangan || '';

            // Buka modal
            const modal = bootstrap.Modal.getOrCreateInstance(modalEditEl);
            modal.show();
        });

        // === RESET SAAT MODAL DITUTUP ===
        modalEditEl.addEventListener('hidden.bs.modal', function() {
            formEditEl.reset();
            formEditEl.action = '';
        });

        // delete sweetalert
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;
            e.preventDefault();

            const nama = btn.dataset.nama || 'data ini';
            const form = btn.closest('.form-delete');

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
