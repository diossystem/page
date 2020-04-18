<?php

namespace Tests;

use Dios\System\Page\PageServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Replaces TestCase.
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../database/factories');

        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__.'/../database/migrations/base'),
            '--database' => 'testing',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            PageServiceProvider::class,
        ];
    }

    /**
     * Defines environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
