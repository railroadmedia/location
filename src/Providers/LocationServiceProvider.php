<?php

namespace Railroad\Location\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Railroad\Location\Services\ConfigService;

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

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}