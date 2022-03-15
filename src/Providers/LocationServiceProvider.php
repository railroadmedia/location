<?php

namespace Railroad\Location\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Railroad\Location\Console\Commands\LocationHelper;
use Railroad\Location\Console\Commands\LocationHelper2;
use Railroad\Location\Services\ConfigService;
use Sokil\IsoCodes\IsoCodesFactory;

class LocationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->setupConfig();

        $this->publishes(
            [
                __DIR__ . '/../../config/location.php' => config_path('location.php'),
            ]
        );
    }

    private function setupConfig()
    {
        ConfigService::$environment = config('location.environment');
        ConfigService::$testingIP = config('location.testing_ip');
        ConfigService::$apiDetails = config('location.api');
        ConfigService::$activeAPI = config('location.active_api');
    }

    public function register()
    {
        $this->app->singleton(IsoCodesFactory::class, function ($app) {
            return new IsoCodesFactory(null, null);
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/location.php', 'location'
        );

        if ($this->app->runningInConsole()) {

            $this->commands(
                [
                    LocationHelper::class,
                    LocationHelper2::class,
                ]
            );

        }
    }
}
