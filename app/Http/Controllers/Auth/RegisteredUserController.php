<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
/** PERBAIKAN: Gunakan model Karyawan **/
use App\Models\Karyawan; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            /** PERBAIKAN: Validasi unik merujuk ke tabel karyawans **/
            'nik' => ['required', 'string', 'max:20', 'unique:karyawans,nik'], 
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:karyawans,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /** PERBAIKAN: Simpan ke model Karyawan **/
        $karyawan = Karyawan::create([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'password' => Hash::make($request->password),
            'jabatan' => 'Karyawan', // Default jabatan lebih profesional
            'is_admin' => 0,
            'status' => 'aktif', // Pastikan status default terisi jika diperlukan
        ]);

        event(new Registered($karyawan));

        // Langsung aktifkan Remember Me agar user tetap login
        Auth::login($karyawan, true);

        // Jika Anda menggunakan verifikasi email, arahkan ke notice
        // Jika tidak, bisa langsung ke dashboard
        return redirect()->route('verification.notice');
    }
}