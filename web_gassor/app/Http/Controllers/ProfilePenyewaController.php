<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePenyewaController extends Controller
{
    public function index()
    {
        return view('pages.profile.profile-penyewa');
    }

    public function edit()
    {
        $user = Auth::user();

        return view('pages.profile.editprofile-penyewa', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        // Cek approval admin
        if (!$user->is_approved) {
            return redirect()->route('editprofile.penyewa')->with('error', 'Data Anda belum diverifikasi oleh admin. Silakan tunggu persetujuan admin sebelum dapat mengubah profil.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:500',
            'username' => 'required|string|max:500',
            'email' => 'required|email|max:500|unique:users,email,'.$user->id,
            'tempat_lahir' => 'required|string|max:500',
            'tanggal_lahir' => 'required|date',
            'phone' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'remove_profile_image' => 'nullable|in:0,1',
            'ktp_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'sim_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'ktm_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
        ]);

        // Username Unik
        $usernameExists = User::where('username', $validated['username'])
            ->where('id', '!=', $user->id)
            ->exists();
        if ($usernameExists) {
            return redirect()->route('editprofile.penyewa')->with('error', 'Username sudah digunakan oleh pengguna lain.');
        }
        // Phone Unik
        $phoneExists = User::where('phone', $validated['phone'])
            ->where('id', '!=', $user->id)
            ->exists();
        if ($phoneExists) {
            return redirect()->route('editprofile.penyewa')->with('error', 'Nomor telepon sudah digunakan oleh pengguna lain.');
        }

        try {
            // Foto Profil
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

            // Gambar KTP
            if ($request->input('remove_ktp_image') == '1') {
                if ($user->ktp_image_url && Storage::disk('public')->exists($user->ktp_image_url)) {
                    Storage::disk('public')->delete($user->ktp_image_url);
                }
                $validated['ktp_image_url'] = null;
            } elseif ($request->hasFile('ktp_image')) {
                if ($user->ktp_image_url && Storage::disk('public')->exists($user->ktp_image_url)) {
                    Storage::disk('public')->delete($user->ktp_image_url);
                }
                $ktpPath = $request->file('ktp_image')->store('ktp_images', 'public');
                $validated['ktp_image_url'] = $ktpPath;
            }
            // Gambar SIM
            if ($request->input('remove_sim_image') == '1') {
                if ($user->sim_image_url && Storage::disk('public')->exists($user->sim_image_url)) {
                    Storage::disk('public')->delete($user->sim_image_url);
                }
                $validated['sim_image_url'] = null;
            } elseif ($request->hasFile('sim_image')) {
                if ($user->sim_image_url && Storage::disk('public')->exists($user->sim_image_url)) {
                    Storage::disk('public')->delete($user->sim_image_url);
                }
                $simPath = $request->file('sim_image')->store('sim_images', 'public');
                $validated['sim_image_url'] = $simPath;
            }
            // Gambar KTM
            if ($request->input('remove_ktm_image') == '1') {
                if ($user->ktm_image_url && Storage::disk('public')->exists($user->ktm_image_url)) {
                    Storage::disk('public')->delete($user->ktm_image_url);
                }
                $validated['ktm_image_url'] = null;
            } elseif ($request->hasFile('ktm_image')) {
                if ($user->ktm_image_url && Storage::disk('public')->exists($user->ktm_image_url)) {
                    Storage::disk('public')->delete($user->ktm_image_url);
                }
                $ktmPath = $request->file('ktm_image')->store('ktm_images', 'public');
                $validated['ktm_image_url'] = $ktmPath;
            }

            $user->update($validated);
            $user->ktp_image_url = $validated['ktp_image_url'] ?? $user->ktp_image_url;
            $user->sim_image_url = $validated['sim_image_url'] ?? $user->sim_image_url;
            $user->ktm_image_url = $validated['ktm_image_url'] ?? $user->ktm_image_url;
            $user->save();

            return redirect()->route('editprofile.penyewa')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('editprofile.penyewa')->with('error', 'Terjadi kesalahan saat memperbarui profil: '.$e->getMessage());
        }
    }
}
