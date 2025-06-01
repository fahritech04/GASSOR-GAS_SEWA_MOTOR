<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfilePenyewaController extends Controller
{
    public function index ()
    {
        return view ('pages.profile.profile-penyewa');
    }
    public function edit ()
    {
        $user = Auth::user();
        return view ('pages.profile.editprofile-penyewa', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'phone' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_profile_image' => 'nullable|in:0,1',
        ]);

        try {
            // Handle profile image
            if ($request->remove_profile_image == '1') {
                if ($user->profile_image_url && Storage::disk('public')->exists($user->profile_image_url)) {
                    Storage::disk('public')->delete($user->profile_image_url);
                }
                $validated['profile_image_url'] = null;
            } elseif ($request->hasFile('profile_image')) {
                if ($user->profile_image_url && Storage::disk('public')->exists($user->profile_image_url)) {
                    Storage::disk('public')->delete($user->profile_image_url);
                }
                $path = $request->file('profile_image')->store('profile_images', 'public');
                $validated['profile_image_url'] = $path;
            }

            $user->update($validated);
            return redirect()->route('editprofile.penyewa')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('editprofile.penyewa')->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }
}
