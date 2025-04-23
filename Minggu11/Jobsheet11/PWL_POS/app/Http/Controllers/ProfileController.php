<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
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
        $request->validate([
            'profile_photo' => 'nullable|image|max:5120', // maks 5MB
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists('profile/' . $user->profile_photo)) {
                Storage::disk('public')->delete('profile/' . $user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile', $filename, 'public');

            $user->profile_photo = $filename;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
