<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware

{
    public function handle($request, Closure $next)
{
    if (auth()->user() && auth()->user()->isAdmin()) {
        return $next($request);
    }
    return redirect('/');
}
}

