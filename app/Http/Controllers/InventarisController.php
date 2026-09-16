<?php

namespace App\Http\Controllers;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventarisController extends Controller
{
    public function create()
    {
        return response()->json([
            'labs' => \App\Models\Lab::orderBy('nama_lab')->get(),
            'sumber_danas' => \App\Models\SumberDana::orderBy('nama')->get(),
        ]);
    }

    public function edit($id)
    {
        return $this->show($id);
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

        // Filter kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun_pembelian', $request->tahun);
        }

        // Filter lab
        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        // Filter sumber dana
        if ($request->filled('sumber_dana_id')) {
            $query->where('sumber_dana_id', $request->sumber_dana_id);
        }

        $perPage = $request->input('per_page', 10);
        $data = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List data inventaris',
            'data'    => $data,
        ], 200);
    }

    /**
     * POST /api/inventaris
     * Simpan barang baru. jumlah_total otomatis kehitung.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'         => 'required|date',
            'no_inventaris'   => 'required|string|unique:inventaris,no_inventaris',
            'nama_barang'     => 'required|string|max:255',
            'spesifikasi'     => 'nullable|string|max:255',
            'volume'          => 'required|integer|min:1',
            'satuan'          => 'required|string|max:50',
            'tahun_pembelian' => 'required|integer|min:2000|max:' . date('Y'),
            'harga_satuan'    => 'required|numeric|min:0',
            'kondisi'         => 'required|in:Baik,Rusak',
            'keterangan'      => 'nullable|string',
            'lab_id'          => 'required|exists:labs,id',
            'sumber_dana_id'  => 'required|exists:sumber_danas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // jumlah_total dihitung otomatis di Model (booted)
        $barang = Inventaris::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data'    => $barang->load(['lab', 'sumberDana']),
        ], 201);
    }

    /**
     * GET /api/inventaris/{id}
     * Detail 1 barang.
     */
    public function show($id)
    {
        $barang = Inventaris::with(['lab', 'sumberDana'])->find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail barang',
            'data'    => $barang,
        ], 200);
    }

    /**
     * PUT/PATCH /api/inventaris/{id}
     * Update barang.
     */
    public function update(Request $request, $id)
    {
        $barang = Inventaris::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tanggal'         => 'sometimes|required|date',
            'no_inventaris'   => 'sometimes|required|string|unique:inventaris,no_inventaris,' . $id,
            'nama_barang'     => 'sometimes|required|string|max:255',
            'spesifikasi'     => 'nullable|string|max:255',
            'volume'          => 'sometimes|required|integer|min:1',
            'satuan'          => 'sometimes|required|string|max:50',
            'tahun_pembelian' => 'sometimes|required|integer|min:2000|max:' . date('Y'),
            'harga_satuan'    => 'sometimes|required|numeric|min:0',
            'kondisi'         => 'sometimes|required|in:Baik,Rusak',
            'keterangan'      => 'nullable|string',
            'lab_id'          => 'sometimes|required|exists:labs,id',
            'sumber_dana_id'  => 'sometimes|required|exists:sumber_danas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $barang->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate',
            'data'    => $barang->fresh()->load(['lab', 'sumberDana']),
        ], 200);
    }

    /**
     * DELETE /api/inventaris/{id}
     * Soft delete barang.
     */
    public function destroy($id)
    {
        $barang = Inventaris::find($id);

        if (!$barang) {
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
        $totalAset      = Inventaris::sum('jumlah_total');
        $totalBarang    = Inventaris::count();
        $kondisiBaik    = Inventaris::where('kondisi', 'Baik')->count();
        $kondisiRusak   = Inventaris::where('kondisi', 'Rusak')->count();

        return response()->json([
            'success' => true,
            'message' => 'Summary dashboard',
            'data'    => [
                'total_aset'    => $totalAset,
                'total_aset_rupiah' => 'Rp ' . number_format((float) $totalAset, 0, ',', '.'),
                'total_barang'  => $totalBarang,
                'kondisi_baik'  => $kondisiBaik,
                'kondisi_rusak' => $kondisiRusak,
            ],
        ], 200);
    }
}
