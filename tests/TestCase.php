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
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections', [
            'sqlite' => [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ],
            'mysql' => [
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'testing',
                'username' => 'testing',
                'password' => '',
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);
    }

    /**
     * Loads the base migrations.
     *
     * @param  string $database
     * @return void
     */
    protected function loadBaseMigrations(string $database = 'sqlite')
    {
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__.'/../database/migrations/base'),
            '--database' => $database,
        ]);
    }
}
