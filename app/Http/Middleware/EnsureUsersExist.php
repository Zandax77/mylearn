<?php

namespace App\Http\Middleware;

use App\Services\AdminBootstrapper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUsersExist
{
    public function handle(Request $request, Closure $next): Response
    {
        AdminBootstrapper::ensureSuperAdmin();
        return $next($request);
    }
}

