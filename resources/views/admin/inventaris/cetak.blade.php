<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Inventaris — {{ $namaLab }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000;
            padding: 15px 20px;
            background: #fff;
        }

        /* Tombol aksi — ilang pas print */
        .no-print {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            font-family: Arial, sans-serif;
        }

        .btn-primary {
            background: #3b82f6;
            color: #fff;
        }

        .btn-secondary {
            background: #6b7280;
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Header dokumen */
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
        }

        .header h2 {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header h3 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 11px;
            color: #333;
        }

        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0 12px;
            text-transform: uppercase;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        thead th {
            background: #e5e5e5;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }

        tbody td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        tfoot td {
            font-weight: bold;
            background: #f3f4f6;
            font-size: 11px;
        }

        /* Kolom tanda centang */
        .check-cell {
            text-align: center;
            font-weight: bold;
            width: 35px;
        }

        /* Tanda tangan */
        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            font-size: 11px;
        }

        .signature .box {
            text-align: center;
            min-width: 180px;
        }

        .signature .space {
            height: 60px;
        }

        /* Print mode */
        @media print {
            body {
                padding: 0;
                margin: 10mm;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4 landscape;
                margin: 8mm;
            }

            table {
                font-size: 9px;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- Tombol aksi (nggak ke-print) --}}
    <div class="no-print">
        <button class="btn btn-secondary" onclick="window.close()">
            <i>×</i> Tutup
        </button>
        <button class="btn btn-primary" onclick="window.print()">
            🖨 Cetak
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <h2>SMK NEGERI 1</h2>
        <h3>JURUSAN TEKNIK KOMPUTER DAN JARINGAN</h3>
        <p>Jl. Pendidikan No. 1, Telp. (021) 1234567</p>
    </div>

    {{-- Judul --}}
    <div class="title">
        Daftar Inventaris {{ $namaLab }}
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">NO</th>
                <th rowspan="2" style="width: 55px;">TANGGAL</th>
                <th rowspan="2">NOMOR INVENTARIS</th>
                <th rowspan="2">NAMA BARANG</th>
                <th rowspan="2">SPESIFIKASI</th>
                <th rowspan="2" style="width: 40px;">VOLUME</th>
                <th rowspan="2" style="width: 50px;">SATUAN</th>
                <th rowspan="2" style="width: 50px;">TAHUN PEMBELIAN</th>
                <th rowspan="2" style="width: 70px;">HARGA SATUAN</th>
                <th rowspan="2" style="width: 80px;">JUMLAH TOTAL</th>
                <th colspan="2" style="width: 80px;">KONDISI BARANG</th>
                <th rowspan="2" style="width: 80px;">SUMBER DANA</th>
                <th rowspan="2" style="width: 80px;">KETERANGAN</th>
            </tr>
            <tr>
                <th style="width: 35px;">BAIK</th>
                <th style="width: 40px;">TIDAK</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inventaris as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $item->tanggal?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $item->no_inventaris }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->spesifikasi ?? '-' }}</td>
                    <td class="text-center">{{ $item->volume }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-center">{{ $item->tahun_pembelian }}</td>
                    <td class="text-right text-nowrap">
                        Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td class="text-right text-nowrap">
                        Rp {{ number_format((float) ($item->volume * $item->harga_satuan), 0, ',', '.') }}
                    </td>
                    <td class="check-cell">
                        {{ $item->kondisi === 'Baik' ? '✓' : '' }}
                    </td>
                    <td class="check-cell">
                        {{ $item->kondisi === 'Rusak' ? '✓' : '' }}
                    </td>
                    <td>{{ $item->sumberDana->nama ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center" style="padding: 20px;">
                        Tidak ada data inventaris.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-center" style="font-weight: bold;">
                    TOTAL ASSET {{ strtoupper($namaLab) }}
                </td>
                <td class="text-right text-nowrap" colspan="2">
                    Rp {{ number_format((float) $grandTotal, 0, ',', '.') }}
                </td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    {{-- Tanda tangan --}}
    <div class="signature">
        <div class="box">
            <div>Mengetahui,</div>
            <div>Kepala Jurusan TKJ</div>
            <div class="space"></div>
            <div style="border-top: 1px solid #000; padding-top: 4px;">
                (.........................................)
            </div>
        </div>
        <div class="box">
            <div>{{ now()->translatedFormat('d F Y') }}</div>
            <div>Admin Lab</div>
            <div class="space"></div>
            <div style="border-top: 1px solid #000; padding-top: 4px;">
                (.........................................)
            </div>
        </div>
    </div>

    <script>
        // Auto print setelah halaman ke-load
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>

</body>

</html>
