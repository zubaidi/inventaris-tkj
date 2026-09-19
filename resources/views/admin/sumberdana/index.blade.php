@extends('admin.layouts.app-layout')
@section('title', 'Data Sumber Dana')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">Data Sumber Dana</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 small">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.sumber-dana.index') }}" class="text-decoration-none">Sumber
                                            Dana</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Index</li>
                                </ol>
                            </nav>
                        </div>
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#modalTambahSumberDana">
                            <i class="ti ti-plus fs-5"></i> Tambah Data
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id="tabel-sumber-dana">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    {{-- <th>Jurusan</th> --}}
                                    <th>Nama Sumber Dana</th>
                                    <th class="text-nowrap">Jml Barang</th>
                                    <th class="text-nowrap">Total Nilai</th>
                                    <th class="text-nowrap text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sumberDanas as $sumberDana)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        {{-- <td>{{ $sumberDana->jurusan->singkatan }}</td> --}}
                                        <td>{{ $sumberDana->nama }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary">
                                                {{ $sumberDana->inventaris_count }}
                                            </span>
                                        </td>
                                        <td data-order="{{ $sumberDana->total_aset }}">
                                            Rp {{ number_format((float) $sumberDana->total_aset, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-warning btn-edit-sumber-dana"
                                                    title="Edit" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditSumberDana" data-id="{{ $sumberDana->id }}"
                                                    data-nama="{{ $sumberDana->nama }}">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                                <form action="{{ route('admin.sumber-dana.destroy', $sumberDana->id) }}"
                                                    method="POST" class="form-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                        title="Hapus" data-nama="{{ $sumberDana->nama }}">
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

    <div class="modal fade" id="modalTambahSumberDana" tabindex="-1" aria-labelledby="modalTambahSumberDanaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.sumber-dana.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahSumberDanaLabel">
                            <i class="ti ti-plus me-1"></i> Tambah Sumber Dana
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (auth()->user()->isSuperAdmin())
                            <div class="mb-3">
                                <label for="jurusan_id" class="form-label">
                                    Jurusan <span class="text-danger">*</span>
                                </label>
                                <select name="jurusan_id" id="jurusan_id"
                                    class="form-select @error('jurusan_id') is-invalid @enderror" required>
                                    <option value="">— Pilih Jurusan —</option>
                                    @foreach (\App\Models\Jurusan::orderBy('nama')->get() as $j)
                                        <option value="{{ $j->id }}"
                                            {{ old('jurusan_id', $model->jurusan_id ?? '') == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <label for="nama" class="form-label">Nama Sumber Dana <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama') }}" placeholder="Contoh: BOS" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

    <div class="modal fade" id="modalEditSumberDana" tabindex="-1" aria-labelledby="modalEditSumberDanaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditSumberDana" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSumberDanaLabel">
                            <i class="ti ti-pencil me-1"></i> Edit Sumber Dana
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (auth()->user()->isSuperAdmin())
                            <div class="mb-3">
                                <label for="jurusan_id" class="form-label">
                                    Jurusan <span class="text-danger">*</span>
                                </label>
                                <select name="jurusan_id" id="jurusan_id"
                                    class="form-select @error('jurusan_id') is-invalid @enderror" required>
                                    <option value="">— Pilih Jurusan —</option>
                                    @foreach (\App\Models\Jurusan::orderBy('nama')->get() as $j)
                                        <option value="{{ $j->id }}"
                                            {{ old('jurusan_id', $model->jurusan_id ?? '') == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jurusan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <label for="edit_nama" class="form-label">Nama Sumber Dana <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
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
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <script>
        new DataTable('#tabel-sumber-dana', {
            language: {
                url: "{{ asset('assets/js/id.json') }}"
            },
            order: [
                [1, 'asc']
            ],
            columnDefs: [{
                    targets: [0, 4],
                    orderable: false,
                    searchable: false
                },
                {
                    targets: [0, 3],
                    className: 'text-center'
                },
            ],
        });

        const modalEdit = document.getElementById('modalEditSumberDana');
        const formEdit = document.getElementById('formEditSumberDana');
        const inputNama = document.getElementById('edit_nama');

        document.addEventListener('click', function(event) {
            const button = event.target.closest('.btn-edit-sumber-dana');
            if (button) {
                formEdit.action = `/admin/sumber-dana/${button.dataset.id}`;
                inputNama.value = button.dataset.nama || '';
            }

            const deleteButton = event.target.closest('.btn-delete');
            if (!deleteButton) {
                return;
            }

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

        modalEdit.addEventListener('hidden.bs.modal', function() {
            formEdit.reset();
            formEdit.action = '';
        });
    </script>
@endpush
