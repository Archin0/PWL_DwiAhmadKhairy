<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user(),
            'level' => Level::all(),
            'activeMenu' => 'profile',
            'breadcrumb' => (object)[
                'title' => 'Profil Saya',
                'list' => ['Home', 'Profil']
            ]
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
            'activeMenu' => 'profile',
            'breadcrumb' => (object)[
                'title' => 'Edit Profil',
                'list' => ['Home', 'Profil', 'Edit']
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed|different:current_password',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            /** @var \App\Models\User $user */
            // Update nama
            $user->nama = $request->nama;

            // Update password jika diisi
            if ($request->filled('new_password')) {
                // Validasi password lama
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()->back()
                        ->with('error', 'Password lama tidak sesuai')
                        ->withInput();
                }

                $user->password = bcrypt($request->new_password);
            }

            // Handle foto profil
            if ($request->hasFile('foto_profil')) {
                // Hapus foto lama jika ada
                if ($user->foto_profil) {
                    Storage::disk('public')->delete('profile/' . $user->foto_profil);
                }

                // Simpan foto baru
                $file = $request->file('foto_profil');
                $filename = 'profile_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('profile', $filename, 'public');

                $user->foto_profil = $filename;
            }

            $user->save();

            return redirect()->route('profile.edit')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }
}
