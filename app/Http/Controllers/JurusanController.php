<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use Illuminate\Validation\Rule;

class JurusanController extends Controller
{
     /**
     * List semua jurusan.
     */
    public function index()
    {
        $jurusans = Jurusan::withCount(['users', 'labs', 'sumberDanas', 'inventaris'])
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.jurusan.index', compact('jurusans'));
    }

    /**
     * Form tambah jurusan.
     */
    public function create()
    {
        // return view('admin.jurusan.create');
    }

    /**
     * Simpan jurusan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'           => 'required|string|max:20|unique:jurusans,kode',
            'nama'           => 'required|string|max:255',
            'singkatan'      => 'required|string|max:50',
            'kepala_jurusan' => 'nullable|string|max:255',
        ]);

        Jurusan::create($validated);

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    /**
     * Form edit jurusan.
     */
    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        // return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Update jurusan.
     */
    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $validated = $request->validate([
            'kode'           => ['required', 'string', 'max:20', Rule::unique('jurusans', 'kode')->ignore($id)],
            'nama'           => 'required|string|max:255',
            'singkatan'      => 'required|string|max:50',
            'kepala_jurusan' => 'nullable|string|max:255',
        ]);

        $jurusan->update($validated);

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diupdate.');
    }

    /**
     * Hapus jurusan (dengan proteksi).
     */
    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        // Cek relasi — jangan hapus kalau masih ada data terkait
        $errors = [];
        if ($jurusan->labs()->count() > 0) {
            $errors[] = "{$jurusan->labs()->count()} lab";
        }
        if ($jurusan->inventaris()->count() > 0) {
            $errors[] = "{$jurusan->inventaris()->count()} inventaris";
        }
        if ($jurusan->users()->count() > 0) {
            $errors[] = "{$jurusan->users()->count()} user";
        }
        if ($jurusan->sumberDanas()->count() > 0) {
            $errors[] = "{$jurusan->sumberDanas()->count()} sumber dana";
        }

        if (!empty($errors)) {
            return back()->with('error',
                'Tidak bisa hapus ' . $jurusan->nama . '. Masih ada ' . implode(', ', $errors) . ' terkait.'
            );
        }

        $jurusan->delete();

        return redirect()
            ->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
