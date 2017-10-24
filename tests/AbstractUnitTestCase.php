<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class AbstractUnitTestCase.
 */
abstract class AbstractUnitTestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Register our service-provider manually
        //$app->register(B2BApiServiceProvider::class);

        $this->app = $app; // грязный хак, но тут - срать
        $this->clearCache();

        return $app;
    }

    /**
     * Чистим кэш.
     */
    protected function clearCache()
    {
        $this->app->make(Kernel::class)->call('cache:clear');
        $this->app->make('cache')->flush();
    }
}
