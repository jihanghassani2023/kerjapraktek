<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAccess
{
    protected $allowedEmails = [
        'admin' => [
            'andreasprasongko@admin.mgtech',
        ],
        'kepala_toko' => [
            'robertchandra@kepalatoko.mgtech',
        ],
        'teknisi' => [
            'tengkuh@teknisi.mgtech',
        ],
    ];

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (Auth::check()) {
            Auth::logout();  // Logout pengguna yang sudah login
            $request->session()->invalidate();  // Hancurkan sesi
            $request->session()->regenerateToken();  // Regenerasi token sesi
        }

        // Jika email atau role tidak sesuai, redirect ke login
        return redirect()->route('login');
    }
}
