<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Tenancy\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        
        TenantManager::setCurrentTenant(null);

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->tenant_id) {
                $tenant = Tenant::where('id', $user->tenant_id)
                          ->first();

                if ($tenant) {
                    TenantManager::setCurrentTenant($tenant);

                } 
            }
        }

        return $next($request);
    }
}