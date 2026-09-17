@extends('admin.layouts.app-layout')
@section('title', 'Backup Database')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h3 class="fw-semibold mb-1">Backup Database</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Backup Database</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Info Database --}}
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Database</h5>
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted">Driver</small>
                        <div class="fw-semibold">{{ $info['driver'] }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Nama Database</small>
                        <div class="fw-semibold">{{ $info['database'] }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Host</small>
                        <div class="fw-semibold">{{ $info['host'] }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Ukuran</small>
                        <div class="fw-semibold text-primary">{{ $size }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Download --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">Download Backup (.sql)</h5>
                        <p class="text-muted small mb-0">
                            Download semua data dalam format <code>.sql</code>. Cocok buat restore ke server lain.
                        </p>
                    </div>
                    <a href="{{ route('admin.backup.database.download') }}" class="btn btn-primary">
                        <i class="ti ti-database-export me-1"></i> Download SQL
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistik Tabel --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Tabel yang Akan Di-Backup</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Users</div>
                            <div class="fs-4 fw-bold">{{ number_format($stats['users']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Labs</div>
                            <div class="fs-4 fw-bold">{{ number_format($stats['labs']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Sumber Dana</div>
                            <div class="fs-4 fw-bold">{{ number_format($stats['sumber_danas']) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Inventaris</div>
                            <div class="fs-4 fw-bold text-primary">{{ number_format($stats['inventaris']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning mt-3 small">
            <i class="ti ti-alert-triangle me-1"></i>
            <strong>Perhatian:</strong> File backup berisi data sensitif. Simpan di tempat aman.
        </div>
    </div>
@endsection

