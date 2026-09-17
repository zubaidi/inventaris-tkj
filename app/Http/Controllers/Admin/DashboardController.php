<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Lab;
use App\Models\SumberDana;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary cards
        $totalAset = Inventaris::selectRaw('COALESCE(SUM(volume * harga_satuan), 0) as total')
            ->value('total');

        $asetBaik = Inventaris::where('kondisi', 'Baik')->count();
        $asetRusak = Inventaris::where('kondisi', 'Rusak')->count();

        // Aset terbaru
        $asetTerbaru = Inventaris::with(['lab', 'sumberDana'])
            ->latest()
            ->take(5)
            ->get();

        // Data master (buat kartu tambahan kalau perlu)
        $totalLab = Lab::count();
        $totalDana = SumberDana::count();

        return view('admin.dashboard', compact(
            'totalAset',
            'asetBaik',
            'asetRusak',
            'asetTerbaru',
            'totalLab',
            'totalDana'
        ));
    }
}
