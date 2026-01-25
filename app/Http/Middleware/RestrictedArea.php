<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictedArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Masukkan IP Public atau Range IP Local Wi-Fi RS Anda di sini
        $allowedIps = [ '192.168.1.1']; // Ganti dengan IP asli RS

        if (!in_array($request->ip(), $allowedIps)) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda harus terhubung ke Wi-Fi RSU Anna Medika untuk melakukan presensi.');
        }

        return $next($request);
    }
}
