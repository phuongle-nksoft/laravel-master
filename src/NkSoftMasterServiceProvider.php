<?php

namespace Nksoft\Master;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Nksoft\Products\Models\Orders;

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
            __DIR__ . '/config/nksoft.php' => config_path('nksoft.php'),
        ], 'nksoft');
        $this->mergeConfigFrom(__DIR__ . '/config/nksoft.php', 'nksoft');
        view()->composer('master::parts.sidebar', function ($view) {
            $view->with([
                'sidebar' => \Nksoft\Master\Models\Navigations::where(['is_active' => 1])->orderBy('order_by')->get(),
                'newOrder' => Orders::whereIn('status', [1, 2])->whereDate('created_at', Carbon::today())->count(),
            ]);
        });
        view()->composer('master::parts.header', function ($view) {
            $view->with(['histories' => \Nksoft\Master\Models\Histories::get()]);
        });
    }
}
