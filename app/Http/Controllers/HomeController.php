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
        $search = $request->input('search');
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

        $grandTotal = $inventaris->sum(fn ($i) => $i->volume * $i->harga_satuan);

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
