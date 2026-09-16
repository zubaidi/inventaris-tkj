<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function publicIndex()
    {
        $labs = Lab::withCount('inventaris')
            ->withSum('inventaris', 'jumlah_total')
            ->orderBy('nama_lab')
            ->get();

        return view('lab.index', compact('labs'));
    }

    /**
     * Admin: daftar lab (buat CRUD).
     */
    public function index()
    {
        $labs = Lab::withCount('inventaris')
            ->withSum('inventaris', 'jumlah_total')
            ->orderBy('nama_lab')
            ->get();

        return view('admin.labs.index', compact('labs'));
    }

    /**
     * Admin: form tambah lab.
     */
    public function create()
    {
        return view('admin.labs.create');
    }

    public function show($id)
    {
        return response()->json(Lab::withCount('inventaris')->findOrFail($id));
    }

    /**
     * Admin: simpan lab baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lab'   => 'required|string|max:255|unique:labs,nama_lab',
            'lokasi'     => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Lab::create($validated);

        return redirect()
            ->route('admin.labs.index')
            ->with('success', 'Lab berhasil ditambahkan.');
    }

    /**
     * Admin: form edit lab.
     */
    public function edit($id)
    {
        $lab = Lab::findOrFail($id);

        return view('admin.labs.edit', compact('lab'));
    }

    /**
     * Admin: update lab.
     */
    public function update(Request $request, $id)
    {
        $lab = Lab::findOrFail($id);

        $validated = $request->validate([
            'nama_lab'   => 'required|string|max:255|unique:labs,nama_lab,' . $id,
            'lokasi'     => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $lab->update($validated);

        return redirect()
            ->route('admin.labs.index')
            ->with('success', 'Lab berhasil diupdate.');
    }

    /**
     * Admin: hapus lab.
     */
    public function destroy($id)
    {
        $lab = Lab::findOrFail($id);

        if ($lab->inventaris()->count() > 0) {
            return redirect()
                ->route('admin.labs.index')
                ->with('error', 'Lab masih punya barang, hapus barangnya dulu.');
        }

        $lab->delete();

        return redirect()
            ->route('admin.labs.index')
            ->with('success', 'Lab berhasil dihapus.');
    }
}
