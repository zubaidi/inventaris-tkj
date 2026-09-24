<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Jurusan;
use App\Models\Lab;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:80',
        ]);
        $search = $request->input('search');
        $search = $search ? trim(strip_tags($search)) : null;
        $query = Inventaris::with(['lab', 'sumberDana']);

        if ($search) {
            $query->search($search);
        }

        $inventaris = $query->latest()->paginate(15)->withQueryString();

        // Data buat Tab 2 — Rekap per Lab
        $jurusans = Jurusan::with(['labs' => function ($q) {
            $q->withCount('inventaris')->orderBy('nama_lab');
        }])
            ->withCount('inventaris')
            ->orderBy('nama')
            ->get();

        return view('home', compact('inventaris', 'jurusans', 'search'));
    }

    public function perRuang($id)
    {
        $lab = Lab::findOrFail($id);

        $inventaris = Inventaris::with(['lab', 'sumberDana'])
            ->where('lab_id', $id)
            ->orderBy('nama_barang')
            ->paginate(15);

        $grandTotal = Inventaris::where('lab_id', $id)
        ->selectRaw('COALESCE(SUM(volume * harga_satuan), 0) as total')
        ->value('total');

        return view('home-per-ruang', compact('lab', 'inventaris', 'grandTotal'));
    }

    public function show($id)
    {
        return response()->json(Inventaris::with(['lab', 'sumberDana'])->findOrFail($id));
    }

    public function export(Request $request)
    {
        return app(InventarisController::class)->export($request);
    }
}
