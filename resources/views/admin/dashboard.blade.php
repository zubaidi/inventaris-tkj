@extends('admin.layouts.app-layout')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1 text-muted">Jumlah Asset</h6>
                                <h2 class="fw-bold mb-0">{{ $totalAset }}</h2>
                            </div>
                            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="ti ti-box fs-4 text-primary"></i>
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
                                <h2 class="fw-bold mb-0 text-success">{{ $asetBaik }}</h2>
                            </div>
                            <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
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
                                <h2 class="fw-bold mb-0 text-danger">{{ $asetRusak }}</h2>
                            </div>
                            <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                <i class="ti ti-alert-triangle fs-4 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title mb-0">Daftar Aset Terbaru</h5>
                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
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
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada data aset.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Menu Cepat</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.inventaris.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                                <i class="ti ti-plus fs-5"></i> Tambah Aset
                            </a>
                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                                <i class="ti ti-list fs-5"></i> Lihat Semua Aset
                            </a>
                            <a href="{{ route('admin.inventaris.export') }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                                <i class="ti ti-download fs-5"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
