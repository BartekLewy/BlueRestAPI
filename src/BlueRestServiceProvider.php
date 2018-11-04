<?php

namespace BlueRestAPI;

use BlueRestAPI\Controller\ItemController;
use BlueRestAPI\Model\ItemRepository;
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

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    public function register()
    {
        $this->app->make(ItemController::class);
        $this->app->bind(ItemController::class, function ($app) {
            return new ItemController($app->make(ItemRepository::class));
        });
    }
}