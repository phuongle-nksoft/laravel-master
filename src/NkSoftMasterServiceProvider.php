<?php

namespace Nksoft\Master;

use Illuminate\Support\ServiceProvider;

class NkSoftMasterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Nksoft\Master\Controllers\UsersController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'master');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/master')
        ]);
        $this->publishes([
            __DIR__.'/public' => public_path('vendor/nksoft')
        ], 'public');
    }
}
