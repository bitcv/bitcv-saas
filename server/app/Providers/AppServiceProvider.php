<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \DB::listen(function($query) {
            if (env('APP_ENV') == 'local') {
                $sql = str_replace("?", "'%s'", $query->sql);
                $log = vsprintf($sql, $query->bindings);
                \Log::debug($log);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
