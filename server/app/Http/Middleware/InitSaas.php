<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Saas;

class InitSaas
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
        $host = $_SERVER['HTTP_HOST'];
        $saas = new Saas();
        $saasdomain = config('app.domain');
        if ($host != $saasdomain) {
            if (substr($host, -1 - strlen($saasdomain)) == '.'.$saasdomain) {
                $subname = substr($host, 0, strlen($host)-strlen($saasdomain)-1);
                $proj = $saas->getBySubname($subname);
            } else {
                $proj = $saas->getByDomain($host);
            }
            if (!isset($proj['proj_id'])) {
                return redirect($_SERVER['REQUEST_SCHEME'].'://'.$saasdomain);
            }
            app()->proj = $proj;
        } else {
            app()->proj = [];
        }
        return $next($request);
    }
}
