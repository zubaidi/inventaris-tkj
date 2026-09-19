@extends('admin.layouts.app-layout')
@section('title', 'Data Jurusan')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h3 class="fw-semibold mb-1">Data Jurusan</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Jurusan</li>
                    </ol>
                </nav>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
                <i class="ti ti-plus me-1"></i> Tambah Jurusan
            </button>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Kode</th>
                                <th>Nama Jurusan</th>
                                <th>Singkatan</th>
                                <th>Kepala Jurusan</th>
                                <th class="text-center">Data Terkait</th>
                                <th class="text-center" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jurusans as $i => $jurusan)
                                <tr>
                                    <td class="text-center">{{ $jurusans->firstItem() + $i }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $jurusan->kode }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ $jurusan->nama }}</td>
                                    <td>{{ $jurusan->singkatan }}</td>
                                    <td>{{ $jurusan->kepala_jurusan ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <i class="ti ti-users me-1"></i>{{ $jurusan->users_count }}
                                            </span>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ti ti-building me-1"></i>{{ $jurusan->labs_count }}
                                            </span>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="ti ti-wallet me-1"></i>{{ $jurusan->sumber_danas_count }}
                                            </span>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-box me-1"></i>{{ $jurusan->inventaris_count }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            {{-- Tombol Edit — trigger modal --}}
                                            <button type="button" class="btn btn-sm btn-warning btn-edit-jurusan"
                                                title="Edit" data-id="{{ $jurusan->id }}"
                                                data-kode="{{ $jurusan->kode }}" data-nama="{{ $jurusan->nama }}"
                                                data-singkatan="{{ $jurusan->singkatan }}"
                                                data-kepala_jurusan="{{ $jurusan->kepala_jurusan }}">
                                                <i class="ti ti-pencil"></i>
                                            </button>

                                            {{-- Tombol Hapus — SweetAlert --}}
                                            <form action="{{ route('admin.jurusan.destroy', $jurusan->id) }}"
                                                method="POST" class="form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    title="Hapus" data-nama="{{ $jurusan->nama }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="ti ti-building-off fs-1 text-muted d-block mb-2"></i>
                                        <span class="text-muted">Belum ada data jurusan.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($jurusans->hasPages())
                    <div class="mt-3">
                        {{ $jurusans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================
     MODAL TAMBAH JURUSAN
     ============================================ --}}
    <div class="modal fade" id="modalTambahJurusan" tabindex="-1" aria-labelledby="modalTambahJurusanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.jurusan.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahJurusanLabel">
                            <i class="ti ti-plus me-1"></i> Tambah Jurusan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="tambah_kode" class="form-label">
                                    Kode <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="kode" id="tambah_kode"
                                    class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}"
                                    placeholder="TKJ" required>
                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label for="tambah_singkatan" class="form-label">
                                    Singkatan <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="singkatan" id="tambah_singkatan"
                                    class="form-control @error('singkatan') is-invalid @enderror"
                                    value="{{ old('singkatan') }}" placeholder="TKJ" required>
                                @error('singkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="tambah_nama" class="form-label">
                                    Nama Jurusan <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama" id="tambah_nama"
                                    class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                                    placeholder="Teknik Komputer dan Jaringan" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="tambah_kepala_jurusan" class="form-label">Kepala Jurusan</label>
                                <input type="text" name="kepala_jurusan" id="tambah_kepala_jurusan"
                                    class="form-control @error('kepala_jurusan') is-invalid @enderror"
                                    value="{{ old('kepala_jurusan') }}" placeholder="Nama kepala jurusan (opsional)">
                                @error('kepala_jurusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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

    {{-- ============================================
     MODAL EDIT JURUSAN
     ============================================ --}}
    <div class="modal fade" id="modalEditJurusan" tabindex="-1" aria-labelledby="modalEditJurusanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditJurusan" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditJurusanLabel">
                            <i class="ti ti-pencil me-1"></i> Edit Jurusan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="edit_kode" class="form-label">
                                    Kode <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="kode" id="edit_kode" class="form-control" required>
                            </div>

                            <div class="col-md-8">
                                <label for="edit_singkatan" class="form-label">
                                    Singkatan <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="singkatan" id="edit_singkatan" class="form-control"
                                    required>
                            </div>

                            <div class="col-12">
                                <label for="edit_nama" class="form-label">
                                    Nama Jurusan <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama" id="edit_nama" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label for="edit_kepala_jurusan" class="form-label">Kepala Jurusan</label>
                                <input type="text" name="kepala_jurusan" id="edit_kepala_jurusan"
                                    class="form-control">
                            </div>
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
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEditEl = document.getElementById('modalEditJurusan');
            const formEditEl = document.getElementById('formEditJurusan');

            // === EDIT — set value + action + show modal ===
            document.querySelectorAll('.btn-edit-jurusan').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    formEditEl.action = `/admin/jurusan/${this.dataset.id}`;

                    document.getElementById('edit_kode').value = this.dataset.kode || '';
                    document.getElementById('edit_nama').value = this.dataset.nama || '';
                    document.getElementById('edit_singkatan').value = this.dataset.singkatan || '';
                    document.getElementById('edit_kepala_jurusan').value = this.dataset
                        .kepala_jurusan || '';

                    bootstrap.Modal.getOrCreateInstance(modalEditEl).show();
                });
            });

            // Reset form pas modal edit di-close
            modalEditEl.addEventListener('hidden.bs.modal', function() {
                formEditEl.reset();
                formEditEl.action = '';
            });

            // === DELETE — SweetAlert ===
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-delete');
                if (!btn) return;
                e.preventDefault();

                const nama = btn.dataset.nama || 'data ini';
                const form = btn.closest('.form-delete');

                Swal.fire({
                    title: 'Yakin hapus?',
                    html: `Jurusan <b>${nama}</b> akan dihapus permanen.`,
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

            // === Auto-open modal tambah kalau ada error validasi ===
            @if ($errors->any() && !old('_method'))
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahJurusan')).show();
            @endif

            // === Auto-open modal edit kalau error dari update ===
            @if ($errors->any() && old('_method') === 'PUT')
                const id = '{{ old('_id') }}';
                if (id) {
                    formEditEl.action = `/admin/jurusan/${id}`;
                }
                bootstrap.Modal.getOrCreateInstance(modalEditEl).show();
            @endif
        });
    </script>
@endpush
