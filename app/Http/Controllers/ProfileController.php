<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Isi data nama & email dari request yang sudah divalidasi
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // --- LOGIKA UPLOAD FOTO ---
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // Buat nama file unik: nik_timestamp.ekstensi
            $fileName = ($user->nik ?? 'user') . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Pindahkan ke folder public/images
            $file->move(public_path('images'), $fileName);

            // Hapus foto lama jika ada dan bukan foto default
            if ($user->foto && $user->foto !== 'default.jpg') {
                $oldPath = public_path('images/' . $user->foto);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Simpan nama file baru ke database
            $user->foto = $fileName;
        }
        // --------------------------

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update: Mengajukan penghapusan akun ke Admin/HRD.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Validasi Password dan Teks Konfirmasi
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
            'confirm_text' => ['required', 'string', 'in:KONFIRMASI'],
        ], [
            'confirm_text.in' => 'Anda harus mengetik KONFIRMASI dengan huruf kapital.',
        ]);

        $user = $request->user();

        // 2. Update status dan catat waktu pengajuan
        $user->update([
            'status' => 'pending_delete', 
            'delete_requested_at' => now(), 
        ]);

        // 3. Proses Logout
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 4. Kembali ke login dengan pesan notifikasi
        return Redirect::to('/login')->with('info', 'Permohonan penghapusan akun berhasil dikirim. Akun Anda dinonaktifkan sementara menunggu persetujuan Admin.');
    }
}