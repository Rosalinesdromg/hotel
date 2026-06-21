<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
   public function handle(Request $request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    // Customer coba akses halaman staff
    if (auth()->user()->hasRole('customer') && !in_array('customer', $roles)) {
        return redirect('/my-bookings');
    }

    foreach ($roles as $role) {
        if ($request->user()->hasRole($role)) {
            return $next($request);
        }
    }

    abort(403, 'Akses ditolak.');
}
}