<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Melakukan proses otentikasi (cek email & password)
        $request->authenticate();

        /** * PERBAIKAN: Gunakan Auth::user() 
         * Meskipun modelnya Karyawan, Laravel memanggilnya lewat user()
         */
        $karyawan = Auth::user(); 
        
        // 1. Cek apakah akun sedang dalam proses hapus
        if ($karyawan->status === 'pending_delete') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('Akun Anda sedang dalam proses peninjauan penghapusan oleh HRD. Akses ditangguhkan.'),
            ]);
        }

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // 2. Tentukan tujuan Redirect berdasarkan Role (is_admin)
        if ($karyawan->is_admin) {
            return redirect()->intended(route('admin.dashboards'));
        }

        // Redirect untuk karyawan biasa
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}