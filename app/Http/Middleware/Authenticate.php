<?php

namespace App\Http\Middleware;

use Closure, Session;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Session::has('logged_in_user')) {
            if ($request->ajax()) {
                return response()->json(array('auth_required' => true));
            } else {
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}
