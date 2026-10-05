@extends('admin.layouts.app-layout')
@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h3 class="fw-semibold mb-1">Edit Data User</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.user.index') }}" class="text-decoration-none">User</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>

                        {{-- ROLE --}}
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role"
                                required onchange="toggleJurusan()">
                                <option value="">-- Pilih Role --</option>
                                <option value="super_admin"
                                    {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                    Jurusan</option>
                                <option value="kepala_sekolah"
                                    {{ old('role', $user->role) == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah
                                </option>
                                <option value="waka" {{ old('role', $user->role) == 'waka' ? 'selected' : '' }}>Wakil
                                    Kepala Sekolah</option>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- JURUSAN --}}
                        <div class="col-md-6">
                            <label for="jurusan_id" class="form-label">
                                Jurusan <span class="text-danger" id="label-jurusan-required"
                                    style="display: none;">*</span>
                            </label>
                            <select class="form-select @error('jurusan_id') is-invalid @enderror" id="jurusan_id"
                                name="jurusan_id">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach (\App\Models\Jurusan::orderBy('nama')->get() as $jurusan)
                                    <option value="{{ $jurusan->id }}"
                                        {{ old('jurusan_id', $user->jurusan_id) == $jurusan->id ? 'selected' : '' }}>
                                        {{ $jurusan->nama }} ({{ $jurusan->singkatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jurusan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="hint-jurusan">Wajib untuk role Admin & User. Kosongkan untuk Super
                                Admin / Pimpinan.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
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
@push('script')
    <script>
        function toggleJurusan() {
            const role = document.getElementById('role').value;
            const jurusan = document.getElementById('jurusan_id');
            const label = document.getElementById('label-jurusan-required');
            const hint = document.getElementById('hint-jurusan');

            // Role yang BUTUH jurusan
            const butuhJurusan = ['admin', 'user'].includes(role);

            if (butuhJurusan) {
                jurusan.setAttribute('required', 'required');
                label.style.display = 'inline';
                hint.textContent = 'Wajib dipilih untuk role Admin / User.';
                hint.className = 'text-danger';
            } else {
                jurusan.removeAttribute('required');
                label.style.display = 'none';
                hint.textContent = 'Kosongkan untuk Super Admin / Pimpinan.';
                hint.className = 'text-muted';
            }
        }

        document.addEventListener('DOMContentLoaded', toggleJurusan);
    </script>
@endpush
