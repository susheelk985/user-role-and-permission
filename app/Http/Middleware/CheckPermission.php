<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $this->roles()->whereHas('permissions', function ($q) use ($permission) {
            if (!auth()->user() || !auth()->user()->hasPermission($permission)) {
                // Redirect or return an unauthorized response
                return redirect('/')->with('error', 'Unauthorized');
            }    $q->where('name', $permission);
        })->exists();

        return $next($request);
    }
}
