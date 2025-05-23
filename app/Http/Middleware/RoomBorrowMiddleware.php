<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoomBorrowMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff', 'teacher'])) {
            abort(403, 'Unauthorized action. Only teachers can borrow rooms.');
        }

        return $next($request);
    }
}
