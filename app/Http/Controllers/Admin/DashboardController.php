<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Jurusan;
use App\Models\Lab;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ============================================
        // CARD UTAMA
        // ============================================
        $totalAset = Inventaris::selectRaw('COALESCE(SUM(volume * harga_satuan), 0) as total')->value('total');
        $totalBarang = Inventaris::count();
        $asetBaik = Inventaris::where('kondisi', 'Baik')->count();
        $asetRusak = Inventaris::where('kondisi', 'Rusak')->count();

        // ============================================
        // REKAP PER JURUSAN — Pimpinan & Super Admin
        // ============================================
        $rekapPerJurusan = collect();
        $grandTotalSemua = 0;

        if ($user->isPimpinan() || $user->isSuperAdmin()) {
            $jurusans = Jurusan::orderBy('nama')->get();

            // Query agregat per jurusan
            $rekapRaw = Inventaris::withoutGlobalScope('tenant')
                ->selectRaw('
            jurusan_id,
            COUNT(*) as total,
            SUM(CASE WHEN kondisi = "Baik" THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN kondisi = "Rusak" THEN 1 ELSE 0 END) as rusak,
            COALESCE(SUM(volume * harga_satuan), 0) as total_aset
        ')
                ->groupBy('jurusan_id')
                ->get()
                ->keyBy('jurusan_id');

            // Query agregat per lab
            $rekapLabRaw = Inventaris::withoutGlobalScope('tenant')
                ->selectRaw('
            lab_id,
            COUNT(*) as total,
            SUM(CASE WHEN kondisi = "Baik" THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN kondisi = "Rusak" THEN 1 ELSE 0 END) as rusak,
            COALESCE(SUM(volume * harga_satuan), 0) as total_aset
        ')
                ->groupBy('lab_id')
                ->get()
                ->keyBy('lab_id');

            $rekapPerJurusan = $jurusans->map(function ($jurusan) use ($rekapRaw, $rekapLabRaw) {
                // Ambil semua lab di jurusan ini
                $labs = Lab::where('jurusan_id', $jurusan->id)
                    ->orderBy('nama_lab')
                    ->get();

                // Breakdown per lab
                $perLab = $labs->map(function ($lab) use ($rekapLabRaw) {
                    $stats = $rekapLabRaw->get($lab->id);

                    return [
                        'lab' => $lab,
                        'total' => (int) ($stats->total ?? 0),
                        'baik' => (int) ($stats->baik ?? 0),
                        'rusak' => (int) ($stats->rusak ?? 0),
                        'total_aset' => (float) ($stats->total_aset ?? 0),
                    ];
                });

                $raw = $rekapRaw->get($jurusan->id);

                return [
                    'jurusan' => $jurusan,
                    'total_aset' => (float) ($raw->total_aset ?? 0),
                    'baik' => (int) ($raw->baik ?? 0),
                    'rusak' => (int) ($raw->rusak ?? 0),
                    'total' => (int) ($raw->total ?? 0),
                    'per_lab' => $perLab,
                ];
            });

            $grandTotalSemua = $rekapPerJurusan->sum('total_aset');
        }

        // ============================================
        // ASET TERBARU
        // ============================================
        $asetTerbaru = Inventaris::with(['lab', 'sumberDana'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalAset',
            'totalBarang',
            'asetBaik',
            'asetRusak',
            'rekapPerJurusan',
            'grandTotalSemua',
            'asetTerbaru'
        ));
    }
}
