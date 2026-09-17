@extends('admin.layouts.app-layout')
@section('title', 'Rekap Inventaris')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h3 class="fw-semibold mb-1">Rekap Inventaris per Barang</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.inventaris.index') }}"
                                class="text-decoration-none">Inventaris</a></li>
                        <li class="breadcrumb-item active">Rekap</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>

        {{-- Card Tabel --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title mb-0">Daftar Rekap</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCetak">
                        <i class="ti ti-printer me-1"></i> Cetak Inventaris
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabel-rekap">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Nomor Inventaris</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Volume</th>
                                <th>Lab / Ruang</th>
                                <th>Kondisi</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rekap as $item)
                                <tr>
                                    <td class="text-center"></td>
                                    <td>
                                        @foreach ($item['nomor_inventaris'] as $no)
                                            <span
                                                class="badge bg-secondary-subtle text-secondary mb-1 d-block">{{ $no }}</span>
                                        @endforeach
                                    </td>
                                    <td class="fw-semibold">{{ $item['nama_barang'] }}</td>
                                    <td class="text-center" data-order="{{ $item['volume'] }}">
                                        <span class="badge bg-primary-subtle text-primary fs-6">{{ $item['volume'] }}</span>
                                    </td>
                                    <td>
                                        @foreach ($item['lab'] as $lab)
                                            <span
                                                class="badge bg-info-subtle text-info mb-1 d-block">{{ $lab }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($item['kondisi'] as $detail)
                                            <div class="small mb-1">
                                                <span class="text-muted">{{ $detail['lab'] }}:</span>
                                                @if ($detail['kondisi'] === 'Baik')
                                                    <span class="badge bg-success-subtle text-success">Baik</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Rusak</span>
                                                @endif
                                                <span class="text-muted">({{ $detail['volume'] }}x)</span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end" data-order="{{ $item['grand_total'] }}">
                                        @foreach ($item['harga_per_lab'] as $harga)
                                            <div class="small mb-1">
                                                <span class="text-muted">{{ $harga['lab'] }}:</span>
                                                <span class="fw-semibold">
                                                    Rp {{ number_format((float) $harga['total'], 0, ',', '.') }}
                                                </span>
                                                @if ($harga['volume'] > 1)
                                                    <br>
                                                    <span class="text-muted" style="font-size: 0.75rem;">
                                                        ({{ $harga['volume'] }} × Rp
                                                        {{ number_format((float) $harga['harga_satuan'], 0, ',', '.') }})
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end fw-bold" data-order="{{ $item['grand_total'] }}">
                                        Rp {{ number_format((float) $item['grand_total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end fw-bold">Grand Total</th>
                                <th class="text-end fw-bold">
                                    Rp {{ number_format((float) $grandTotalKeseluruhan, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Cetak --}}
    <div class="modal fade" id="modalCetak" tabindex="-1" aria-labelledby="modalCetakLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.inventaris.cetak') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCetakLabel">
                            <i class="ti ti-printer me-1"></i> Cetak Inventaris
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="lab_id" class="form-label">Pilih Lab / Ruang</label>
                            <select name="lab_id" id="lab_id" class="form-select">
                                <option value="">Semua Lab (Semua Data)</option>
                                @foreach ($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nama_lab }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Pilih lab tertentu atau cetak semua data.
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-printer me-1"></i> Cetak Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script>
        new DataTable('#tabel-rekap', {
            language: {
                url: "{{ asset('assets/js/id.json') }}",
            },
            order: [
                [2, 'asc']
            ],
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'Semua']
            ],
            columnDefs: [{
                targets: 0,
                orderable: false,
                searchable: false,
            }],
            drawCallback: function() {
                const api = this.api();
                const info = api.page.info(); // ← pakai page.info(), bukan context[0]
                api.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = info.start + i + 1;
                });
            }
        });
    </script>
@endpush
