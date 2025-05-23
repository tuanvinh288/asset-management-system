<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
