<?php

namespace Railroad\Location\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Railroad\Location\Providers\LocationServiceProvider;


class LocationTestCase extends BaseTestCase
{

    protected function setUp()
    {
        parent::setUp();

        $this->artisan('cache:clear', []);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // setup package config for testing
        $defaultConfig = require(__DIR__ . '/../config/location.php');
        $app['config']->set('location.environment', $defaultConfig['environment']);
        $app['config']->set('location.testing_ip', $defaultConfig['testing_ip']);
        $app['config']->set('location.api', $defaultConfig['api']);
        $app['config']->set('location.active_api', $defaultConfig['active_api']);

        // register provider
        $app->register(LocationServiceProvider::class);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}