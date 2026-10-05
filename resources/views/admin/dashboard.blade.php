@extends('admin.layouts.app-layout')
@section('title', 'Dashboard')
@push('style')
    <style>
        .accordion-modern .accordion-item {
            background: #fff;
        }

        .accordion-modern .accordion-button {
            background: #fff;
            color: #1e293b;
            padding: 14px 16px;
            font-size: 0.9rem;
            box-shadow: none !important;
            border: none;
        }

        .accordion-modern .accordion-button:not(.collapsed) {
            background: #f8fafc;
            color: #3b82f6;
        }

        .accordion-modern .accordion-body {
            padding: 16px;
            background: #fafbfc;
            border-top: 1px solid #f1f5f9;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <div class="d-flex align-items-start justify-content-between mb-4">
            <div>
                <h3 class="fw-semibold mb-1">Dashboard</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1 text-muted">Total Nilai Asset</h6>
                                <h4 class="fw-bold mb-0">
                                    Rp {{ number_format((float) $totalAset, 0, ',', '.') }}
                                </h4>
                            </div>
                            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                                style="width: 52px; height: 52px;">
                                <i class="ti ti-wallet fs-4 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1 text-muted">Asset Baik</h6>
                                <h4 class="fw-bold mb-0 text-success">{{ $asetBaik }}</h4>
                            </div>
                            <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                                style="width: 52px; height: 52px;">
                                <i class="ti ti-circle-check fs-4 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1 text-muted">Asset Rusak</h6>
                                <h4 class="fw-bold mb-0 text-danger">{{ $asetRusak }}</h4>
                            </div>
                            <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center"
                                style="width: 52px; height: 52px;">
                                <i class="ti ti-alert-triangle fs-4 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ============================================
            REKAP PER JURUSAN — Accordion
        ============================================ --}}
        @if ((auth()->user()->isPimpinan() || auth()->user()->isSuperAdmin()) && $rekapPerJurusan->count())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">

                            {{-- Header Card --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="fw-semibold mb-1">Rekap Per Jurusan</h5>
                                    <p class="text-muted small mb-0">Total inventaris per jurusan</p>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Total Keseluruhan</small>
                                    <h6 class="fw-bold mb-0 text-primary">
                                        Rp {{ number_format((float) $grandTotalSemua, 0, ',', '.') }}
                                    </h6>
                                </div>
                            </div>

                            {{-- Accordion --}}
                            <div class="accordion accordion-modern" id="accordionJurusanDashboard">

                                @foreach ($rekapPerJurusan as $rekap)
                                    @php $collapseId = 'collapseJurusan' . $rekap['jurusan']->id; @endphp

                                    <div class="accordion-item border-0 shadow-sm mb-2 rounded overflow-hidden">

                                        {{-- Header Accordion --}}
                                        <h2 class="accordion-header" id="heading{{ $rekap['jurusan']->id }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false" aria-controls="{{ $collapseId }}">

                                                <div class="d-flex align-items-center gap-3 w-100 me-2">
                                                    {{-- Icon --}}
                                                    <div class="rounded-3 bg-primary-subtle d-flex align-items-center justify-content-center shrink-0"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="ti ti-school text-primary"></i>
                                                    </div>

                                                    {{-- Info Jurusan --}}
                                                    <div class="grow">
                                                        <div class="fw-semibold text-dark">
                                                            {{ $rekap['jurusan']->nama }}
                                                        </div>
                                                        <small class="text-muted" style="font-size: 0.7rem;">
                                                            {{ $rekap['jurusan']->singkatan }} · {{ $rekap['total'] }} item
                                                            ·
                                                            Rp
                                                            {{ number_format((float) $rekap['total_aset'], 0, ',', '.') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>

                                        {{-- Body Accordion --}}
                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $rekap['jurusan']->id }}"
                                            data-bs-parent="#accordionJurusanDashboard">

                                            <div class="accordion-body pt-3">

                                                {{-- Summary Cards --}}
                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-4">
                                                        <div
                                                            class="d-flex align-items-center gap-3 p-3 rounded bg-primary-subtle h-100">
                                                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shrink-0"
                                                                style="width: 44px; height: 44px;">
                                                                <i class="ti ti-wallet fs-5 text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted d-block">Total Aset</small>
                                                                <h6 class="fw-bold mb-0 text-primary">
                                                                    Rp
                                                                    {{ number_format((float) $rekap['total_aset'], 0, ',', '.') }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div
                                                            class="d-flex align-items-center gap-3 p-3 rounded bg-success-subtle h-100">
                                                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shrink-0"
                                                                style="width: 44px; height: 44px;">
                                                                <i class="ti ti-circle-check fs-5 text-success"></i>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted d-block">Kondisi Baik</small>
                                                                <h6 class="fw-bold mb-0 text-success">
                                                                    {{ $rekap['baik'] }} item
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div
                                                            class="d-flex align-items-center gap-3 p-3 rounded bg-danger-subtle h-100">
                                                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shrink-0"
                                                                style="width: 44px; height: 44px;">
                                                                <i class="ti ti-alert-triangle fs-5 text-danger"></i>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted d-block">Kondisi Rusak</small>
                                                                <h6 class="fw-bold mb-0 text-danger">
                                                                    {{ $rekap['rusak'] }} item
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Breakdown Per Lab --}}
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fw-semibold mb-0 small text-muted text-uppercase">
                                                        <i class="ti ti-building me-1"></i> Detail Per Ruang / Lab
                                                    </h6>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        {{ $rekap['per_lab']->count() }} Lab
                                                    </span>
                                                </div>

                                                @if ($rekap['per_lab']->count())
                                                    <div class="table-responsive">
                                                        <table class="table table-hover align-middle mb-0">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 50px;" class="text-center">#</th>
                                                                    <th>Nama Lab / Ruang</th>
                                                                    <th class="text-center">Total</th>
                                                                    <th class="text-center">Baik</th>
                                                                    <th class="text-center">Rusak</th>
                                                                    <th class="text-end">Total Aset</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($rekap['per_lab'] as $i => $labData)
                                                                    <tr>
                                                                        <td class="text-center text-muted">
                                                                            {{ $i + 1 }}</td>
                                                                        <td>
                                                                            <div class="fw-semibold">
                                                                                {{ $labData['lab']->nama_lab }}</div>
                                                                            @if ($labData['lab']->lokasi)
                                                                                <small class="text-muted"
                                                                                    style="font-size: 0.75rem;">
                                                                                    <i class="ti ti-map-pin"
                                                                                        style="font-size: 0.7rem;"></i>
                                                                                    {{ $labData['lab']->lokasi }}
                                                                                </small>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span
                                                                                class="badge bg-primary-subtle text-primary">
                                                                                {{ $labData['total'] }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span
                                                                                class="badge bg-success-subtle text-success">
                                                                                {{ $labData['baik'] }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span
                                                                                class="badge bg-danger-subtle text-danger">
                                                                                {{ $labData['rusak'] }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-end fw-semibold text-nowrap">
                                                                            Rp
                                                                            {{ number_format((float) $labData['total_aset'], 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot class="table-light">
                                                                <tr>
                                                                    <th colspan="2" class="text-end">Total
                                                                        {{ $rekap['jurusan']->singkatan }}</th>
                                                                    <th class="text-center">{{ $rekap['total'] }}</th>
                                                                    <th class="text-center text-success">
                                                                        {{ $rekap['baik'] }}</th>
                                                                    <th class="text-center text-danger">
                                                                        {{ $rekap['rusak'] }}</th>
                                                                    <th class="text-end text-primary text-nowrap">
                                                                        Rp
                                                                        {{ number_format((float) $rekap['total_aset'], 0, ',', '.') }}
                                                                    </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4 text-muted">
                                                        <i class="ti ti-building-off fs-1 d-block mb-2"></i>
                                                        <small>Belum ada lab di jurusan ini</small>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Row 2: Tabel + Menu Cepat --}}
        <div class="row">
            <div class="{{ auth()->user()->isPimpinan() ? 'col-12' : 'col-lg-8' }}">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title mb-0">Daftar Aset Terbaru</h5>
                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>No. Inventaris</th>
                                        <th>Nama Barang</th>
                                        <th>Kondisi</th>
                                        <th>Lab</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($asetTerbaru as $item)
                                        <tr>
                                            <td><span class="badge bg-secondary">{{ $item->no_inventaris }}</span></td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>
                                                @if ($item->kondisi === 'Baik')
                                                    <span class="badge bg-success-subtle text-success">Baik</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Rusak</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->lab->nama_lab ?? '-' }}</td>
                                            <td>{{ $item->tanggal?->format('d/m/Y') ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada data aset.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Menu Cepat — cuma admin & user, bukan pimpinan --}}
            @if (!auth()->user()->isPimpinan())
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Menu Cepat</h5>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.inventaris.create') }}"
                                    class="btn btn-primary d-flex align-items-center gap-2">
                                    <i class="ti ti-plus fs-5"></i> Tambah Aset
                                </a>
                                <a href="{{ route('admin.inventaris.index') }}"
                                    class="btn btn-outline-primary d-flex align-items-center gap-2">
                                    <i class="ti ti-list fs-5"></i> Lihat Semua Aset
                                </a>
                                <a href="{{ route('admin.inventaris.export') }}"
                                    class="btn btn-outline-success d-flex align-items-center gap-2">
                                    <i class="ti ti-download fs-5"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
