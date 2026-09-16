<?php

namespace App\Http\Controllers;

use App\Models\SumberDana;
use Illuminate\Http\Request;

class SumberDanaController extends Controller
{
    /**
     * Halaman publik: daftar sumber dana.
     */
    public function publicIndex()
    {
        $sumberDanas = SumberDana::withCount('inventaris')
            ->withSum('inventaris', 'jumlah_total')
            ->orderBy('nama')
            ->get();

        return view('sumber-dana.index', compact('sumberDanas'));
    }

    /**
     * Admin: daftar sumber dana.
     */
    public function index()
    {
        $sumberDanas = SumberDana::withCount('inventaris')
            ->withSum('inventaris', 'jumlah_total')
            ->orderBy('nama')
            ->get();

        return view('admin.sumberdana.index', compact('sumberDanas'));
    }

    /**
     * Admin: form tambah sumber dana.
     */
    public function create()
    {
        return redirect()->route('admin.sumber-dana.index');
    }

    public function show($id)
    {
        return response()->json(SumberDana::withCount('inventaris')->findOrFail($id));
    }

    /**
     * Admin: simpan sumber dana baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:sumber_danas,nama',
        ]);

        SumberDana::create($validated);

        return redirect()
            ->route('admin.sumber-dana.index')
            ->with('success', 'Sumber dana berhasil ditambahkan.');
    }

    /**
     * Admin: form edit sumber dana.
     */
    public function edit($id)
    {
        SumberDana::findOrFail($id);

        return redirect()->route('admin.sumber-dana.index');
    }

    /**
     * Admin: update sumber dana.
     */
    public function update(Request $request, $id)
    {
        $sumberDana = SumberDana::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:sumber_danas,nama,' . $id,
        ]);

        $sumberDana->update($validated);

        return redirect()
            ->route('admin.sumber-dana.index')
            ->with('success', 'Sumber dana berhasil diupdate.');
    }

    /**
     * Admin: hapus sumber dana.
     */
    public function destroy($id)
    {
        $sumberDana = SumberDana::findOrFail($id);

        if ($sumberDana->inventaris()->count() > 0) {
            return redirect()
                ->route('admin.sumber-dana.index')
                ->with('error', 'Sumber dana masih dipakai barang, nggak bisa dihapus.');
        }

        $sumberDana->delete();

        return redirect()
            ->route('admin.sumber-dana.index')
            ->with('success', 'Sumber dana berhasil dihapus.');
    }
}
