<?php

namespace App\Http\Middleware;

use Closure;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && ! auth()->user()->isActive()) {
            auth()->logout();

            flash('Ваш пользователь заблокирован!','danger');
            return redirect()->route('login');
        }

        return $next($request);
    }
}
