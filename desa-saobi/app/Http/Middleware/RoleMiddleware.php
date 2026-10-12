<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();
        if (!$user) {
            abort(403, 'Anda belum masuk ke sistem.');
        }

        $userRoleStr = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;

        $allowed = false;
        foreach ($roles as $roleArg) {
            $splitRoles = explode(',', $roleArg);
            foreach ($splitRoles as $singleRole) {
                $singleRole = trim($singleRole);
                if ($userRoleStr === $singleRole) {
                    $allowed = true;
                    break 2;
                }
                // Jika rute butuh role admin, Kepala Desa juga diberikan akses panel admin
                if ($singleRole === 'admin' && ($userRoleStr === 'admin' || $userRoleStr === 'kepala_desa')) {
                    $allowed = true;
                    break 2;
                }
                // Kompatibilitas warga dan client
                if (($singleRole === 'client' || $singleRole === 'warga') && ($userRoleStr === 'warga' || $userRoleStr === 'client')) {
                    $allowed = true;
                    break 2;
                }
            }
        }

        if (!$allowed) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}