@extends('admin.layouts.app-layout')
@section('title', 'Edit Inventaris')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">Edit Data Inventaris</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.inventaris.index') }}"
                                        class="text-decoration-none">Inventaris</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.inventaris.update', $barang->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @if (auth()->user()->isSuperAdmin())
                        <div class="col-md-6">
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
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                                name="tanggal" value="{{ old('tanggal', $barang->tanggal?->format('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="no_inventaris" class="form-label">No Inventaris <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_inventaris') is-invalid @enderror"
                                id="no_inventaris" name="no_inventaris"
                                value="{{ old('no_inventaris', $barang->no_inventaris) }}" required>
                            @error('no_inventaris')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nama_barang" class="form-label">Nama Barang <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                                required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tahun_pembelian" class="form-label">Tahun Pembelian <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('tahun_pembelian') is-invalid @enderror"
                                id="tahun_pembelian" name="tahun_pembelian"
                                value="{{ old('tahun_pembelian', $barang->tahun_pembelian) }}" min="2000"
                                max="{{ date('Y') }}" required>
                            @error('tahun_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="spesifikasi" class="form-label">Spesifikasi</label>
                            <textarea class="form-control @error('spesifikasi') is-invalid @enderror" id="spesifikasi" name="spesifikasi"
                                rows="3">{{ old('spesifikasi', $barang->spesifikasi) }}</textarea>
                            @error('spesifikasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="volume" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('volume') is-invalid @enderror" id="volume"
                                name="volume" value="{{ old('volume', $barang->volume) }}" min="1" required>
                            @error('volume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                                name="satuan" value="{{ old('satuan', $barang->satuan) }}" required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="harga_satuan" class="form-label">Harga Satuan <span
                                    class="text-danger">*</span></label>
                            <input type="number" step="0.01"
                                class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan"
                                name="harga_satuan" value="{{ old('harga_satuan', $barang->harga_satuan) }}"
                                min="0" required>
                            @error('harga_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="lab_id" class="form-label">Lab <span class="text-danger">*</span></label>
                            <select class="form-select @error('lab_id') is-invalid @enderror" id="lab_id"
                                name="lab_id" required>
                                <option value="">-- Pilih Lab --</option>
                                @foreach ($labs as $lab)
                                    <option value="{{ $lab->id }}"
                                        {{ old('lab_id', $barang->lab_id) == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->nama_lab }}</option>
                                @endforeach
                            </select>
                            @error('lab_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sumber_dana_id" class="form-label">Sumber Dana <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('sumber_dana_id') is-invalid @enderror" id="sumber_dana_id"
                                name="sumber_dana_id" required>
                                <option value="">-- Pilih Sumber Dana --</option>
                                @foreach ($sumberDanas as $sumberDana)
                                    <option value="{{ $sumberDana->id }}"
                                        {{ old('sumber_dana_id', $barang->sumber_dana_id) == $sumberDana->id ? 'selected' : '' }}>
                                        {{ $sumberDana->nama }}</option>
                                @endforeach
                            </select>
                            @error('sumber_dana_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi"
                                name="kondisi" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Baik" {{ old('kondisi', $barang->kondisi) == 'Baik' ? 'selected' : '' }}>
                                    Baik</option>
                                <option value="Rusak"
                                    {{ old('kondisi', $barang->kondisi) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="3">{{ old('keterangan', $barang->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
