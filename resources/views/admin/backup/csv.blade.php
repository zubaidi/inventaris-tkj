@extends('admin.layouts.app-layout')
@section('title', 'Export CSV')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h3 class="fw-semibold mb-1">Export CSV</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Export CSV</li>
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

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-1">Pilih Tabel</h5>
                <p class="text-muted small mb-3">
                    Download data per tabel dalam format <code>.csv</code>. Bisa dibuka di Excel / Google Sheets.
                </p>

                <div class="row g-2">
                    @php
                        $tables = [
                            'users' => ['label' => 'Users', 'icon' => 'ti-users', 'color' => 'primary'],
                            'labs' => ['label' => 'Labs', 'icon' => 'ti-building', 'color' => 'info'],
                            'sumber_danas' => ['label' => 'Sumber Dana', 'icon' => 'ti-wallet', 'color' => 'warning'],
                            'inventaris' => ['label' => 'Inventaris', 'icon' => 'ti-box', 'color' => 'success'],
                        ];
                    @endphp

                    @foreach ($tables as $key => $t)
                        <div class="col-md-3">
                            <a href="{{ route('admin.backup.csv.download', $key) }}"
                                class="btn btn-outline-{{ $t['color'] }} w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="ti {{ $t['icon'] }} fs-4"></i>
                                <div class="text-start">
                                    <div class="fw-semibold">{{ $t['label'] }}</div>
                                    <small class="text-muted">{{ number_format($stats[$key]) }} baris</small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
