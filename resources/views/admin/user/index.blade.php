@extends('admin.layouts.app-layout')
@section('title', 'Data User')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="datatables">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h3 class="fw-semibold mb-1">Data User</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 small">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">User</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="{{ route('admin.user.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="ti ti-plus fs-5"></i> Tambah Data
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id="tabel-user">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th class="text-nowrap">Role</th>
                                    <th class="text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">
                                            @if ($user->role === 'admin')
                                                <span class="badge bg-primary-subtle text-primary">Admin</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">User</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                @if ((int) $user->id !== (int) auth()->id())
                                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="form-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus" data-nama="{{ $user->name }}">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
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
@endsection

@push('script')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <script>
        new DataTable('#tabel-user', {
            language: {
                url: "{{ asset('assets/js/id.json') }}"
            },
            order: [[0, 'asc']],
            columnDefs: [
                { targets: [0, 3, 4], orderable: false, searchable: false },
                { targets: [0, 3, 4], className: 'text-center' }
            ]
        });

        document.addEventListener('click', function (event) {
            const deleteButton = event.target.closest('.btn-delete');
            if (!deleteButton) return;

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
    </script>
@endpush
