<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $inventaris = collect();

        if ($search) {
            $inventaris = Inventaris::with(['lab', 'sumberDana'])
                ->where('nama_barang', 'like', "%{$search}%")
                ->orWhere('no_inventaris', 'like', "%{$search}%")
                ->latest()
                ->get();
        }

        return view('home', compact('inventaris', 'search'));
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
