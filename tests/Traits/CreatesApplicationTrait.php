<?php

namespace AvtoDev\StaticReferences\Tests\Traits;

use AvtoDev\StaticReferences\StaticReferencesServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

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
        $app->register(StaticReferencesServiceProvider::class);

        return $app;
    }
}
