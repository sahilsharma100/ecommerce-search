<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next, $guard = 'web')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('client.login');
        }

        if (!Auth::guard($guard)->user()->isVerifiedEmail()) {
            return redirect()->route('client.verify-email');
        }

        if (Auth::guard($guard)->user()->status != 1) {
            return redirect()->route('client.account-restricted');
        }

        return $next($request);
    }
}
