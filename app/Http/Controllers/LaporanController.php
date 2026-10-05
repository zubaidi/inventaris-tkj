<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris;
use App\Models\Jurusan;
// use App\Models\Lab;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Pimpinan liat semua jurusan di sekolahnya
        $jurusans = Jurusan::withCount('inventaris')
            ->with(['labs' => function ($q) {
                $q->withCount('inventaris');
            }])
            ->orderBy('nama')
            ->get();

        // Total aset per jurusan
        $rekap = $jurusans->map(function ($jurusan) {
            $totalAset = Inventaris::withoutGlobalScope('tenant')
                ->where('jurusan_id', $jurusan->id)
                ->selectRaw('COALESCE(SUM(volume * harga_satuan), 0) as total')
                ->value('total');

            $baik  = Inventaris::withoutGlobalScope('tenant')
                ->where('jurusan_id', $jurusan->id)
                ->where('kondisi', 'Baik')->count();
            $rusak = Inventaris::withoutGlobalScope('tenant')
                ->where('jurusan_id', $jurusan->id)
                ->where('kondisi', 'Rusak')->count();

            return [
                'jurusan'    => $jurusan,
                'total_aset' => $totalAset,
                'baik'       => $baik,
                'rusak'      => $rusak,
            ];
        });

        $grandTotal = $rekap->sum('total_aset');

        return view('admin.laporan.index', compact('rekap', 'grandTotal'));
    }
}
