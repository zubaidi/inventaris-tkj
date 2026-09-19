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
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.sumberdana.index', compact('sumberDanas'));
    }

    /**
     * Admin: daftar sumber dana.
     */
    public function index()
    {
        $sumberDanas = SumberDana::withCount('inventaris')
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
        $rules = [
            'nama' => 'required|string|max:255',
        ];

        // 👇 Super admin wajib pilih jurusan
        if (auth()->user()->isSuperAdmin()) {
            $rules['jurusan_id'] = 'required|exists:jurusans,id';
        }

        // Unique nama per jurusan
        $jurusanId = $request->input('jurusan_id') ?? auth()->user()->jurusan_id;
        $rules['nama'] = 'required|string|max:255|unique:sumber_danas,nama,NULL,id,jurusan_id,'.$jurusanId;

        $validated = $request->validate($rules);

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

        $rules = [
            'nama' => 'required|string|max:255',
        ];

        if (auth()->user()->isSuperAdmin()) {
            $rules['jurusan_id'] = 'required|exists:jurusans,id';
        }

        $jurusanId = $request->input('jurusan_id') ?? $sumberDana->jurusan_id;
        $rules['nama'] = 'required|string|max:255|unique:sumber_danas,nama,'.$id.',id,jurusan_id,'.$jurusanId;

        $validated = $request->validate($rules);

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
