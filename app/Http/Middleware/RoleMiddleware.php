<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login'); // not logged in
        }

        if (!in_array(Auth::user()->role->name, $roles)) {
            abort(403, 'Unauthorized Access'); // forbidden
        }

        return $next($request);
    }
}
