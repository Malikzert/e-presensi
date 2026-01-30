<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Mengarahkan Karyawan ke halaman login Google
     */
    public function redirectToGoogle()
    {
        // Tambahkan baris ini untuk mengabaikan error SSL di localhost
        return Socialite::driver('google')
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->redirect();
    }

    /**
     * Menangani callback dari Google setelah login berhasil
     */
    public function handleGoogleCallback()
    {
        try {
            $KaryawanGoogle = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
            
            $Karyawan = Karyawan::where('email', $KaryawanGoogle->getEmail())->first();

            if ($Karyawan) {
                // Login-kan Karyawan
                Auth::login($Karyawan, true);
                
                // --- LOGIKA REDIRECT BERDASARKAN ROLE ---
                if ($Karyawan->is_admin == 1) {
                    // Jika Admin, arahkan ke dashboard admin
                    return redirect()->intended('/admin/dashboards');
                }
                
                // Jika Karyawan biasa, arahkan ke dashboard karyawan
                return redirect()->intended('/dashboard');
                // ----------------------------------------
                
            } else {
                return redirect()->route('login')->withErrors([
                    'email' => 'Email Google (' . $KaryawanGoogle->getEmail() . ') tidak terdaftar.'
                ]);
            }

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login Google.');
        }
    }
}