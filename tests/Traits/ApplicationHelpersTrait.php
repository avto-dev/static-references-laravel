<?php

namespace AvtoDev\StaticReferences\Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

/**
 * Class ApplicationHelpersTrait.
 *
 * Трейт вспомогательных методов по работе с инстансом приложения и подобные.
 *
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */
trait ApplicationHelpersTrait
{
    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return ApplicationContract
     */
    abstract public function createApplication();

    /**
     * Возвращает контейнер консоли.
     *
     * @param ApplicationContract|null $app
     *
     * @return Kernel|\App\Console\Kernel
     */
    public function console(ApplicationContract $app = null)
    {
        $app = $this->resolveApplication($app);

        return $app->make(Kernel::class);
    }

    /**
     * Возвращает контейнер конфигов.
     *
     * @param ApplicationContract|null $app
     *
     * @return ConfigRepository
     */
    public function config(ApplicationContract $app = null)
    {
        $app = $this->resolveApplication($app);

        return $app->make('config');
    }

    /**
     * Clear cache.
     *
     * @param ApplicationContract|null $app
     *
     * @return void
     */
    public function clearCache(ApplicationContract $app = null)
    {
        $this->console($app)->call('cache:clear');
    }

    /**
     * Возвращает инстанс приложения.
     *
     * @param ApplicationContract|null $app
     *
     * @return ApplicationContract|\Illuminate\Foundation\Application
     */
    protected function resolveApplication(ApplicationContract $app = null)
    {
        if ($app instanceof ApplicationContract) {
            return $app;
        } elseif ($this->app instanceof ApplicationContract) {
            return $this->app;
        } else {
            return $this->createApplication();
        }
    }
}
