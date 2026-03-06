<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya izinkan pengguna dengan ID = 1 (Administrator Utama)
        if (auth()->check() && auth()->user()->id === 1) {
            return $next($request);
        }

        // Jika bukan, alihkan ke dashboard dengan pesan peringatan error
        session()->flash('error', 'Akses ditolak! Halaman tersebut khusus Super Admin.');
        return redirect()->route('dashboard');
    }
}
