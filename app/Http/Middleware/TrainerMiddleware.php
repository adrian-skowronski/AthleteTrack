<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TrainerController;


class TrainerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isCoach()) {
            return $next($request);
        }
        return redirect('/')->with('error', 'Nie masz dostępu do tej strony.');
    }
}
