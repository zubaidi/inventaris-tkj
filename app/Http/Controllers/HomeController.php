<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $inventaris = Inventaris::with(['lab', 'sumberDana'])
            ->when($request->filled('search'), fn ($query) => $query->search($request->input('search')))
            ->latest()
            ->paginate(10);

        return view('welcome', compact('inventaris'));
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
