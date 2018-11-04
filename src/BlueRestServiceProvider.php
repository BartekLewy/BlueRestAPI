<?php

namespace BlueRestAPI;

use BlueRestAPI\Controller\ItemController;
use Illuminate\Support\ServiceProvider;

/**
 * Class BlueRestServiceProvider
 * @package BlueRestAPI
 */
class BlueRestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

//        $this->publishes([
//            __DIR__ . '/../config/package.php' => config_path('package.php')
//        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    public function register()
    {
        $this->app->make(ItemController::class);
    }
}