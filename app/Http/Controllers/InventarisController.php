<?php

namespace App\Http\Controllers;

use App\Exports\InventarisExport;
use App\Models\Inventaris;
use App\Models\Lab;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventarisController extends Controller
{
    /**
     * GET /api/inventaris
     * List semua barang + support search & filter.
     *
     * Query params:
     *  - search       : cari nama/no inventaris/spesifikasi
     *  - kondisi      : Baik / Rusak
     *  - tahun        : 2023, 2024, dst
     *  - lab_id       : filter by lab
     *  - sumber_dana_id : filter by sumber dana
     *  - per_page     : jumlah per halaman (default 10)
     */
    public function index(Request $request)
    {
        $query = Inventaris::with(['lab', 'sumberDana']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun_pembelian', $request->tahun);
        }
        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }
        if ($request->filled('sumber_dana_id')) {
            $query->where('sumber_dana_id', $request->sumber_dana_id);
        }

        // 👇 25 per halaman
        $perPage = $request->input('per_page', 10);
        if (! in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $inventaris = $query->latest()->paginate($perPage)->withQueryString();

        // Data dropdown filter
        $labs = Lab::orderBy('nama_lab')->get();
        $sumberDanas = SumberDana::orderBy('nama')->get();
        $tahunList = Inventaris::select('tahun_pembelian')
            ->distinct()
            ->orderBy('tahun_pembelian', 'desc')
            ->pluck('tahun_pembelian');

        // Summary
        $totalAset = Inventaris::selectRaw('COALESCE(SUM(volume * harga_satuan), 0) as total')
            ->value('total');
        $totalBarang = Inventaris::count();

        return view('admin.inventaris.index', compact(
            'inventaris', 'labs', 'sumberDanas', 'tahunList',
            'totalAset', 'totalBarang'
        ));
    }

    public function create()
    {
        $labs = Lab::orderBy('nama_lab')->get();
        $sumberDanas = SumberDana::orderBy('nama')->get();

        return view('admin.inventaris.create', compact('labs', 'sumberDanas'));
    }

    public function edit($id)
    {
        $barang = Inventaris::with(['lab', 'sumberDana'])->findOrFail($id);
        $labs = Lab::orderBy('nama_lab')->get();
        $sumberDanas = SumberDana::orderBy('nama')->get();

        return view('admin.inventaris.edit', compact('barang', 'labs', 'sumberDanas'));
    }

    public function export(Request $request)
    {
        $items = Inventaris::with(['lab', 'sumberDana'])->latest()->get();

        return response()->streamDownload(function () use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No Inventaris', 'Nama Barang', 'Lab', 'Sumber Dana', 'Jumlah Total', 'Kondisi']);

            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->no_inventaris,
                    $item->nama_barang,
                    $item->lab?->nama_lab,
                    $item->sumberDana?->nama,
                    $item->jumlah_total,
                    $item->kondisi,
                ]);
            }

            fclose($handle);
        }, 'inventaris.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * POST /api/inventaris
     * Simpan barang baru. jumlah_total otomatis kehitung.
     */
    public function store(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string|max:255',
            'volume' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'tahun_pembelian' => 'required|integer|min:2000|max:'.date('Y'),
            'harga_satuan' => 'required|numeric|min:0',
            'kondisi' => 'required|in:Baik,Rusak',
            'keterangan' => 'nullable|string',
            'lab_id' => 'required|exists:labs,id',
            'sumber_dana_id' => 'required|exists:sumber_danas,id',
        ];

        // Super admin wajib pilih jurusan
        if (auth()->user()->isSuperAdmin()) {
            $rules['jurusan_id'] = 'required|exists:jurusans,id';
        }

        // Unique no_inventaris per jurusan
        // Ambil jurusan_id dari request (super admin) atau dari user
        $jurusanId = $request->input('jurusan_id') ?? auth()->user()->jurusan_id;
        $rules['no_inventaris'] = 'required|string|unique:inventaris,no_inventaris,NULL,id,jurusan_id,'.$jurusanId;

        $validated = $request->validate($rules);

        Inventaris::create($validated);

        return redirect()
            ->route('admin.inventaris.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * GET /api/inventaris/{id}
     * Detail 1 barang.
     */
    public function show($id)
    {
        $barang = Inventaris::with(['lab', 'sumberDana'])->findOrFail($id);

        return view('admin.inventaris.show', compact('barang'));
    }

    /**
     * PUT/PATCH /api/inventaris/{id}
     * Update barang.
     */
    public function update(Request $request, $id)
    {
        $barang = Inventaris::findOrFail($id);

        $rules = [
            'tanggal' => 'sometimes|required|date',
            'nama_barang' => 'sometimes|required|string|max:255',
            'spesifikasi' => 'nullable|string|max:255',
            'volume' => 'sometimes|required|integer|min:1',
            'satuan' => 'sometimes|required|string|max:50',
            'tahun_pembelian' => 'sometimes|required|integer|min:2000|max:'.date('Y'),
            'harga_satuan' => 'sometimes|required|numeric|min:0',
            'kondisi' => 'sometimes|required|in:Baik,Rusak',
            'keterangan' => 'nullable|string',
            'lab_id' => 'sometimes|required|exists:labs,id',
            'sumber_dana_id' => 'sometimes|required|exists:sumber_danas,id',
        ];

        // 👇 Super admin bisa pindahin jurusan
        if (auth()->user()->isSuperAdmin()) {
            $rules['jurusan_id'] = 'sometimes|required|exists:jurusans,id';
        }

        // 👇 Unique no_inventaris per jurusan, exclude ID sendiri
        $jurusanId = $request->input('jurusan_id') ?? $barang->jurusan_id;
        $rules['no_inventaris'] = 'sometimes|required|string|unique:inventaris,no_inventaris,'.$id.',id,jurusan_id,'.$jurusanId;

        $validated = $request->validate($rules);

        $barang->update($validated);

        return redirect()
            ->route('admin.inventaris.index')
            ->with('success', 'Barang berhasil diupdate.');
    }

    /**
     * DELETE /api/inventaris/{id}
     * Soft delete barang.
     */
    public function destroy($id)
    {
        $barang = Inventaris::find($id);

        if (! $barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus',
        ], 200);
    }

    /**
     * GET /api/inventaris/dashboard
     * Summary buat Dashboard.
     */
    public function dashboard()
    {
        $totalAset = Inventaris::sum('jumlah_total');
        $totalBarang = Inventaris::count();
        $kondisiBaik = Inventaris::where('kondisi', 'Baik')->count();
        $kondisiRusak = Inventaris::where('kondisi', 'Rusak')->count();

        return response()->json([
            'success' => true,
            'message' => 'Summary dashboard',
            'data' => [
                'total_aset' => $totalAset,
                'total_aset_rupiah' => 'Rp '.number_format((float) $totalAset, 0, ',', '.'),
                'total_barang' => $totalBarang,
                'kondisi_baik' => $kondisiBaik,
                'kondisi_rusak' => $kondisiRusak,
            ],
        ], 200);
    }

    public function rekapInventaris(Request $request)
    {
        $inventaris = Inventaris::with(['lab', 'sumberDana'])
            ->when($request->filled('search'), fn ($q) => $q->search($request->search))
            ->when($request->filled('lab_id'), fn ($q) => $q->where('lab_id', $request->lab_id))
            ->when($request->filled('kondisi'), fn ($q) => $q->where('kondisi', $request->kondisi))
            ->orderBy('nama_barang')
            ->get();

        $rekap = $inventaris->groupBy('nama_barang')->map(function ($items, $namaBarang) {
            $hargaPerLab = $items->groupBy('lab_id')->map(function ($labItems) {
                $lab = $labItems->first()->lab;
                $totalVolume = $labItems->sum('volume');
                $totalHarga = $labItems->sum(fn ($i) => $i->volume * $i->harga_satuan);
                $hargaSatuan = $labItems->first()->harga_satuan;

                return [
                    'lab' => $lab->nama_lab ?? '-',
                    'volume' => $totalVolume,
                    'harga_satuan' => (float) $hargaSatuan,
                    'total' => (float) $totalHarga,
                ];
            })->values();

            return [
                'nama_barang' => $namaBarang,
                'nomor_inventaris' => $items->pluck('no_inventaris')->unique()->values(),
                'nomor_inventaris_str' => $items->pluck('no_inventaris')->unique()->implode(', '), // buat search
                'volume' => $items->sum('volume'),
                'lab' => $items->pluck('lab.nama_lab')->filter()->unique()->values(),
                'lab_str' => $items->pluck('lab.nama_lab')->filter()->unique()->implode(', '), // buat search
                'kondisi' => $items->map(fn ($i) => [
                    'nama' => $i->nama_barang,
                    'lab' => $i->lab->nama_lab ?? '-',
                    'kondisi' => $i->kondisi,
                    'volume' => $i->volume,
                ])->values(),
                'kondisi_str' => $items->pluck('kondisi')->unique()->implode(', '), // buat search
                'harga_per_lab' => $hargaPerLab,
                'grand_total' => $items->sum(fn ($i) => $i->volume * $i->harga_satuan),
            ];
        })->values();
        $grandTotalKeseluruhan = $rekap->sum('grand_total');
        $labs = Lab::orderBy('nama_lab')->get();

        return view('admin.inventaris.rekap', compact('rekap', 'grandTotalKeseluruhan', 'labs'));
    }

    public function rekapPerRuang(Request $request)
    {
        // Dropdown filter — ambil semua lab
        $labs = Lab::orderBy('nama_lab')->get();

        $labId = $request->input('lab_id');
        $labTerpilih = null;
        $inventaris = collect();   // default kosong
        $grandTotal = 0;

        // Kalau lab dipilih, baru ambil data
        if ($labId) {
            $labTerpilih = Lab::find($labId);

            if ($labTerpilih) {
                $inventaris = Inventaris::with(['lab', 'sumberDana'])
                    ->where('lab_id', $labId)
                    ->orderBy('nama_barang')
                    ->get();

                $grandTotal = $inventaris->sum(fn ($i) => $i->volume * $i->harga_satuan);
            }
        }

        return view('admin.inventaris.rekap-per-ruang', compact(
            'labs',
            'inventaris',
            'labTerpilih',
            'grandTotal'
        ));
    }

    public function cetakInventaris(Request $request)
    {
        $labId = $request->input('lab_id');
        $lab = $labId ? Lab::find($labId) : null;
        $namaLab = $lab ? $lab->nama_lab : 'Semua Lab';

        $filename = 'Inventaris_'.str_replace(' ', '_', $namaLab).'_'.date('Ymd_His').'.xlsx';

        return Excel::download(new InventarisExport($labId, $namaLab), $filename);
    }
}
