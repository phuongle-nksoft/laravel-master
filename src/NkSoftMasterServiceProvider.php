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
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/views', 'master');
        $this->loadTranslationsFrom(__DIR__ . '/language', 'nksoft');
        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/master'),
            __DIR__ . '/public' => public_path('nksoft'),
            __DIR__ . '/language' => resource_path('lang/vendor/nksoft'),
        ], 'nksoft');

        view()->composer('master::parts.sidebar', function ($view) {
            $view->with(['sidebar' => \Nksoft\Master\Models\Navigations::where(['is_active' => 1])->orderBy('order_by')->get()]);
        });
    }
}
