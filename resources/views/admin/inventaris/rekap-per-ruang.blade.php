@extends('admin.layouts.app-layout')
@section('title', 'Rekap Per Ruang')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h3 class="fw-semibold mb-1">Rekap Inventaris Per Ruang</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.inventaris.index') }}" class="text-decoration-none">Inventaris</a>
                        </li>
                        <li class="breadcrumb-item active">Rekap Per Ruang</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Filter Lab --}}
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.inventaris.rekap-per-ruang') }}"
                    class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label for="lab_id" class="form-label fw-semibold">Pilih Lab / Ruang</label>
                        <select name="lab_id" id="lab_id" class="form-select" onchange="this.form.submit()">
                            <option value="">— Pilih Lab / Ruang —</option>
                            @foreach ($labs as $lab)
                                <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_lab }}
                                    @if ($lab->lokasi)
                                        — {{ $lab->lokasi }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-filter me-1"></i> Tampilkan
                        </button>
                        @if (request('lab_id'))
                            <a href="{{ route('admin.inventaris.rekap-per-ruang') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x me-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Info Lab Terpilih --}}
        @if ($labTerpilih)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        {{-- Kiri: Icon + Info Lab --}}
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 56px; height: 56px;">
                                <i class="ti ti-building fs-5 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1">{{ $labTerpilih->nama_lab }}</h5>
                                <p class="mb-0 text-muted small">
                                    <i class="ti ti-map-pin me-1"></i>
                                    {{ $labTerpilih->lokasi ?? 'Lokasi tidak diset' }}
                                </p>
                            </div>
                        </div>

                        {{-- Kanan: Total Aset --}}
                        <div class="text-end">
                            <small class="text-muted d-block mb-1">Total Nilai Asset</small>
                            <h4 class="fw-bold mb-0 text-primary">
                                Rp {{ number_format((float) $grandTotal, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tabel --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabel-rekap-ruang">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Nomor Inventaris</th>
                                <th>Nama Barang</th>
                                <th>Spesifikasi</th>
                                <th class="text-center">Volume</th>
                                <th class="text-center">Kondisi</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Jumlah Total</th>
                                <th>Sumber Dana</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventaris as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $item->no_inventaris }}</td>
                                    <td class="fw-semibold">{{ $item->nama_barang }}</td>
                                    <td>{{ $item->spesifikasi ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $item->volume }} {{ $item->satuan }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->kondisi === 'Baik')
                                            <span class="badge bg-success-subtle text-success">Baik</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Rusak</span>
                                        @endif
                                    </td>
                                    <td class="text-end" data-order="{{ $item->harga_satuan }}">
                                        Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-semibold"
                                        data-order="{{ $item->volume * $item->harga_satuan }}">
                                        Rp {{ number_format((float) ($item->volume * $item->harga_satuan), 0, ',', '.') }}
                                    </td>
                                    <td>{{ $item->sumberDana->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        @if ($labTerpilih)
                                            <i class="ti ti-box-off fs-1 text-muted d-block mb-2"></i>
                                            <div class="text-muted">Tidak ada barang di {{ $labTerpilih->nama_lab }}.</div>
                                        @else
                                            <i class="ti ti-filter fs-1 text-muted d-block mb-2"></i>
                                            <div class="text-muted">Pilih lab/ruang dulu untuk menampilkan data.</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($inventaris->count() > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="7" class="text-end fw-bold">Grand Total</th>
                                    <th class="text-end fw-bold">
                                        Rp {{ number_format((float) $grandTotal, 0, ',', '.') }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
