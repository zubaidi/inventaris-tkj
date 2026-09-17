@extends('admin.layouts.app-layout')
@section('title', 'Detail Inventaris')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">Detail Inventaris</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.inventaris.index') }}" class="text-decoration-none">Inventaris</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Detail</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.inventaris.edit', $barang->id) }}" class="btn btn-warning">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-3">Informasi Barang</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="35%">No Inventaris</th>
                                                <td>{{ $barang->no_inventaris }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Item</th>
                                                <td>{{ $barang->nama_barang }}</td>
                                            </tr>
                                            <tr>
                                                <th>Spesifikasi</th>
                                                <td>{{ $barang->spesifikasi ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah</th>
                                                <td>{{ $barang->volume }} {{ $barang->satuan }}</td>
                                            </tr>
                                            <tr>
                                                <th>Harga Satuan</th>
                                                <td>Rp {{ number_format((float) $barang->harga_satuan, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Total</th>
                                                <td><strong>{{ $barang->jumlah_total_rupiah }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Kondisi</th>
                                                <td>
                                                    @if ($barang->kondisi === 'Baik')
                                                        <span class="badge bg-success-subtle text-success">Baik</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">Rusak</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-3">Detail Lainnya</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="35%">Tanggal</th>
                                                <td>{{ $barang->tanggal ? $barang->tanggal->format('d/m/Y') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tahun Pembelian</th>
                                                <td>{{ $barang->tahun_pembelian }}</td>
                                            </tr>
                                            <tr>
                                                <th>Lab</th>
                                                <td>{{ $barang->lab->nama_lab ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sumber Dana</th>
                                                <td>{{ $barang->sumberDana->nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Keterangan</th>
                                                <td>{{ $barang->keterangan ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
