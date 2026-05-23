<?php

namespace App\Http\Middleware;

use App\Services\AdminBootstrapper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan super admin ada saat aplikasi baru dijalankan / belum ada user
        AdminBootstrapper::ensureSuperAdmin();

        return $next($request);
    }
}


