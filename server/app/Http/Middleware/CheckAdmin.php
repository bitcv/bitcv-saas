<?php

namespace App\Http\Middleware;

use App\Utils\AuthUser;
use Closure;
use App\Utils\Auth;

class CheckAdmin
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
        
        if (!$request->session()->get('authuinfo')['uid']) {
            return response()->json(['errcode' => 302]);
        }
        return $next($request);
    }

}
