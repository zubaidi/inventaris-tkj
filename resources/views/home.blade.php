<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris TKJ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            color: #1f2937;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: #1e293b;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
        }

        .navbar .logo svg {
            width: 28px;
            height: 28px;
            fill: #3b82f6;
        }

        .navbar .admin-btn {
            background: #3b82f6;
            color: #fff;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .navbar .admin-btn:hover {
            background: #2563eb;
        }

        /* Search Section */
        .search-section {
            max-width: 900px;
            margin: 2.5rem auto 0;
            padding: 0 1.5rem;
        }

        .search-box {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .search-box h2 {
            font-size: 1.3rem;
            margin-bottom: 0.3rem;
            color: #0f172a;
        }

        .search-box p {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-form input:focus {
            border-color: #3b82f6;
        }

        .search-form button {
            padding: 12px 28px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-form button:hover {
            background: #2563eb;
        }

        /* Results */
        .results-section {
            max-width: 900px;
            margin: 1.5rem auto 3rem;
            padding: 0 1.5rem;
        }

        .results-info {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .results-info strong {
            color: #1e293b;
        }

        .table-wrapper {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        #tabel-inventaris {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
        }

        #tabel-inventaris thead {
            background: #1e293b;
            color: #fff;
        }

        #tabel-inventaris th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        #tabel-inventaris td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        #tabel-inventaris tbody tr:hover {
            background: #f8fafc;
        }

        #tabel-inventaris tbody tr:last-child td {
            border-bottom: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1e293b;
            color: #fff;
        }

        th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .kondisi-baik {
            background: #dcfce7;
            color: #166534;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .kondisi-rusak {
            background: #fee2e2;
            color: #991b1b;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .harga {
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.4;
        }

        .empty-state p {
            font-size: 1rem;
        }

        .empty-state .hint {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Mobile version */
        @media (max-width: 576px) {
            .search-form {
                flex-direction: column;
            }

            .search-form button {
                width: 100%;
            }
        }

        @media (max-width: 767.98px) {
            .table-wrapper {
                overflow-x: visible;
                /* matiin scroll, karena udah stacked */
                background: transparent;
                box-shadow: none;
                border-radius: 0;
            }

            #tabel-inventaris {
                min-width: unset;
                /* reset min-width */
            }

            /* Sembunyiin header tabel */
            #tabel-inventaris thead {
                display: none;
            }

            /* Ubah tabel jadi block */
            #tabel-inventaris,
            #tabel-inventaris tbody,
            #tabel-inventaris tr,
            #tabel-inventaris td {
                display: block;
                width: 100%;
            }

            /* Tiap baris jadi card */
            #tabel-inventaris tr {
                background: #fff;
                margin-bottom: 1rem;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
                padding: 1rem;
                border: 1px solid #f1f5f9;
            }

            /* Tiap cell jadi baris label : value */
            #tabel-inventaris td {
                padding: 8px 0;
                border-bottom: 1px dashed #f1f5f9;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                text-align: right;
            }

            #tabel-inventaris td:last-child {
                border-bottom: none;
            }

            /* Label dari data-label */
            #tabel-inventaris td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                font-size: 0.8rem;
                text-transform: uppercase;
                text-align: left;
                flex-shrink: 0;
            }

            /* Kolom aksi biar ke kanan */
            #tabel-inventaris td[data-label="Aksi"] {
                justify-content: flex-end;
                padding-top: 1rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('assets/image/logo.png') }}" alt="TKj" width="36" height="36">
        </a>
        <a href="{{ route('login') }}" class="admin-btn">Admin Area</a>
    </nav>

    <section class="search-section">
        <div class="search-box">
            <h2>Cari Barang</h2>
            <p>Ketik nama barang atau kode barang untuk melihat data inventaris</p>
            <form action="{{ route('home') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Contoh: AC, Lab Komputer, INV-001..."
                    value="{{ $search ?? '' }}" autofocus>
                <button type="submit">Cari</button>
            </form>
        </div>
    </section>

    <section class="results-section">
        @if ($search)
            <div class="results-info">
                Menampilkan hasil pencarian untuk <strong>"{{ $search }}"</strong> — {{ $inventaris->count() }}
                data ditemukan
            </div>

            @if ($inventaris->count())
                <div class="table-wrapper">
                    <table id="tabel-inventaris">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Inventaris</th>
                                <th>Nama Barang</th>
                                <th>Kondisi</th>
                                <th>Lab</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventaris as $item)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration }}</td>
                                    <td data-label="No Inventaris">{{ $item->no_inventaris }}</td>
                                    <td data-label="Nama Barang">{{ $item->nama_barang }}</td>
                                    <td data-label="Kondisi">
                                        @if ($item->kondisi === 'Baik')
                                            <span class="kondisi-baik">Baik</span>
                                        @else
                                            <span class="kondisi-rusak">Rusak</span>
                                        @endif
                                    </td>
                                    <td data-label="Lab">{{ $item->lab->nama_lab ?? '-' }}</td>
                                    <td data-label="Harga" class="harga">{{ $item->jumlah_total_rupiah }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="table-wrapper">
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <p>Barang tidak ditemukan</p>
                        <p class="hint">Coba kata kunci lain, misal: AC, Printer, Lab</p>
                    </div>
                </div>
            @endif
        @else
            <div class="table-wrapper">
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <p>Mulai ketik untuk mencari barang</p>
                    <p class="hint">Hasil pencarian akan muncul di sini</p>
                </div>
            </div>
        @endif
    </section>
</body>

</html>
