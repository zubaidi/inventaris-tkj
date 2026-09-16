<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAset = Inventaris::count();
        $asetBaik = Inventaris::where('kondisi', 'Baik')->count();
        $asetRusak = Inventaris::where('kondisi', 'Rusak')->count();

        $asetTerbaru = Inventaris::with(['lab', 'sumberDana'])
            ->latest('tanggal')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalAset',
            'asetBaik',
            'asetRusak',
            'asetTerbaru',
        ));
    }
}
