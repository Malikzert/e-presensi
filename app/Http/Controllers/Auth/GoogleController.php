<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Mengarahkan user ke halaman login Google
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
            // Ambil data user dari Google
            $userGoogle = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
            
            // Cari apakah email Google tersebut terdaftar di database RSU Anna Medika
            $user = User::where('email', $userGoogle->getEmail())->first();

            if ($user) {
                // Jika terdaftar, langsung login-kan user tersebut
                Auth::login($user, true);
                
                // Redirect ke dashboard
                return redirect()->intended('/dashboard');
            } else {
                // Jika email tidak terdaftar di database MySQL
                return redirect()->route('login')->withErrors([
                    'email' => 'Email Google (' . $userGoogle->getEmail() . ') tidak terdaftar sebagai karyawan RSU Anna Medika. Silakan hubungi bagian IT/HRD.'
                ]);
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}