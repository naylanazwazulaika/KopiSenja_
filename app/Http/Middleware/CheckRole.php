<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        $userRole = match($user->role) {
            'owner' => 'admin',
            'pelanggan' => 'customer',
            'admin_staff', 'staff_admin' => 'staff',
            default => $user->role,
        };

        $normalizedRoles = array_map(function($r) {
            return match($r) {
                'owner' => 'admin',
                'pelanggan' => 'customer',
                'admin_staff', 'staff_admin' => 'staff',
                default => $r,
            };
        }, $roles);

        if (in_array($userRole, $normalizedRoles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
