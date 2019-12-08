<?php

namespace App\Http\Middleware;

use App\User;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Auth::login(User::whereRoleId(User::ROLE_ADMIN)->first());
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(Response::HTTP_UNAUTHORIZED, 'No authenticated as admin');
        }
        return $next($request);
    }
}
