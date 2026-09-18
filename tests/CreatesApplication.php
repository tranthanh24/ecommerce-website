<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->afterBootstrapping(\Illuminate\Foundation\Bootstrap\LoadConfiguration::class, function ($app) {
            if ($app['config']->get('database.default') !== 'sqlite'
                || $app['config']->get('database.connections.sqlite.database') !== ':memory:'
                || !empty($app['config']->get('database.connections.sqlite.url'))) {
                throw new \LogicException('Tests require the isolated SQLite :memory: database from phpunit.xml.');
            }
        });

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
