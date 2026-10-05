<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:super_admin,kepala_sekolah,waka,admin,user',
            'jurusan_id' => [
                'nullable',
                'exists:jurusans,id',
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->role, ['admin', 'user']) && empty($value)) {
                        $fail('Jurusan wajib dipilih untuk role '.ucfirst($request->role).'.');
                    }
                },
            ],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:super_admin,kepala_sekolah,waka,admin,user',
            'jurusan_id' => [
                'nullable',
                'exists:jurusans,id',
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->role, ['admin', 'user']) && empty($value)) {
                        $fail('Jurusan wajib dipilih untuk role '.ucfirst($request->role).'.');
                    }
                },
            ],
        ]);

        // Handle password — hanya update kalau diisi
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        if ((int) $id === (int) auth()->id()) {
            return redirect()->route('admin.user.index')->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }
}
