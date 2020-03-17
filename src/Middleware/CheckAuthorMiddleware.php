<?php

namespace Nksoft\Master\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAuthorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->to('login');
        }
        return $next($request);
    }
}
