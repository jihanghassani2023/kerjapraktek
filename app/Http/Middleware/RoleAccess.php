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

    protected $passwords = [
        'admin' => 'AdminMg-Tech1',
        'kepala_toko' => 'KepalaTokoMg-Tech1',
        'teknisi' => 'TeknisiMg-Tech1',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $allowedRoles = ['admin', 'teknisi', 'kepala_toko'];

        if ($user && in_array($user->role, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}
