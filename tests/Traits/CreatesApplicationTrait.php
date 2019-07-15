<?php

namespace AvtoDev\StaticReferences\Tests\Traits;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use AvtoDev\StaticReferences\ServiceProvider;

trait CreatesApplicationTrait
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Register our service-provider manually
        $app->register(ServiceProvider::class);

        return $app;
    }
}
