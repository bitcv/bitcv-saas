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
        if (strpos($host, ':') > 0) {
            $host = substr($host, 0, strpos($host, ':'));
        }
        if ($host != $saasdomain) {
            if (substr($host, -1 - strlen($saasdomain)) == '.'.$saasdomain) {
                $subname = substr($host, 0, strlen($host)-strlen($saasdomain)-1);
                $proj = $saas->getBySubname($subname);
            } else {
                $proj = $saas->getByDomain($host);
            }
            if (!isset($proj['proj_id'])) {
                if (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'])
                    return redirect($_SERVER['REQUEST_SCHEME'].'://'.$saasdomain);
            }
            app()->proj = $proj;
        } else {
            app()->proj = [];
        }
        return $next($request);
    }
}
