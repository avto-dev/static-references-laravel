<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use AvtoDev\StaticReferences\ServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AbstractUnitTestCase extends BaseTestCase
{
    use Traits\ApplicationHelpersTrait;

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
        $app->register(ServiceProvider::class);

        return $app;
    }
}
