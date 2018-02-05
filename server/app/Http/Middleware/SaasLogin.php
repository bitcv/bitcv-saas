<?php

namespace App\Http\Middleware;

use Closure;

class SaasLogin
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
        if (! session()->get('saas_admin')) {
            return redirect(route('saas.login'));
        }
        return $next($request);
    }
}
