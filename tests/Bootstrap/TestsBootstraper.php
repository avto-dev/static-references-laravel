<?php

namespace AvtoDev\StaticReferences\Tests\Bootstrap;

use Illuminate\Contracts\Console\Kernel;
use AvtoDev\StaticReferences\StaticReferencesServiceProvider;

/**
 * Class TestsBootstraper.
 *
 * Инициализатор первичной загрузки тестов phpunit-а.
 */
class TestsBootstraper extends AbstractTestsBootstraper
{
    /**
     * Регистрирует необходимые сервис-провайдеры приложения.
     *
     * @return bool
     */
    protected function bootServiceProviders()
    {
        $this->log('Register service-provider');

        $this->app->register(StaticReferencesServiceProvider::class);

        return true;
    }

    /**
     * Очищает кэш.
     *
     * @return bool
     */
    protected function bootMakeMigrations()
    {
        $this->log('Clear cache');

        $this->app->make(Kernel::class)->call('cache:clear');

        return true;
    }
}
