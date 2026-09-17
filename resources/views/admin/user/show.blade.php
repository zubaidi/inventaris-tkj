@extends('admin.layouts.app-layout')
@section('title', 'Detail User')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">Detail User</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.user.index') }}" class="text-decoration-none">User</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Detail</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width:90px; height:90px;">
                                        <i class="ti ti-user fs-2 text-primary"></i>
                                    </div>
                                    <h4 class="fw-semibold mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="35%">Nama</th>
                                                <td>{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Role</th>
                                                <td>
                                                    @if ($user->role === 'admin')
                                                        <span class="badge bg-primary-subtle text-primary">Admin</span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary">User</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Dibuat</th>
                                                <td>{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Terakhir Diubah</th>
                                                <td>{{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-' }}</td>
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
