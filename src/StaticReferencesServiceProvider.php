<?php

namespace AvtoDev\StaticReferencesLaravel;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class StaticReferencesServiceProvider.
 *
 * Сервис-провайдер пакета, реализующего работу со статическими справочниками.
 */
class StaticReferencesServiceProvider extends IlluminateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Выполнение после-регистрационной загрузки сервисов.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            realpath($config_path = static::getConfigFilePath()) => config_path(basename($config_path)),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->initializeConfigs();
        $this->registerService();
    }

    /**
     * Возвращает путь до файла-конфигурации пакета.
     *
     * @return string
     */
    public static function getConfigFilePath()
    {
        return __DIR__ . '/config/static-references.php';
    }

    /**
     * Get config root key name.
     *
     * @return string
     */
    public static function getConfigRootKeyName()
    {
        return basename(static::getConfigFilePath(), '.php'); // 'static-references'
    }

    /**
     * Initialize configs.
     *
     * @return void
     */
    protected function initializeConfigs()
    {
        $this->mergeConfigFrom(static::getConfigFilePath(), static::getConfigRootKeyName());
    }

    /**
     * Возвращает контейнер с конфигурацией приложения.
     *
     * @return ConfigRepository
     */
    protected function config()
    {
        return $this->app->make('config');
    }

    /**
     * Регистрирует контейнер статических справочников.
     *
     * @return void
     */
    protected function registerService()
    {
        $this->app->singleton(StaticReferencesInterface::class, function (Application $app) {
            return new StaticReferences($app->make('config')->get(static::getConfigRootKeyName()));
        });

        $this->app->bind(StaticReferences::class, StaticReferencesInterface::class);
        $this->app->bind('static-references', StaticReferencesInterface::class);
    }
}
