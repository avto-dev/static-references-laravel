<?php

namespace AvtoDev\StaticReferences;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;
use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions;

class StaticReferencesServiceProvider extends ServiceProvider
{
    /**
     * Возвращает массив имён классов справочников.
     *
     * @return string[]
     */
    public function getReferencesClasses()
    {
        return [
            AutoRegions::class,
            AutoCategories::class,
            RegistrationActions::class,
            RepairMethods::class,
        ];
    }

    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->getReferencesClasses() as $references_class) {
            $this->app->singleton($references_class, function (Application $app) use ($references_class) {
                return $this->bootUpReferenceInstance($references_class, $app);
            });
        }
    }

    /**
     * Производит инициализацию инстанса справочника по имени его класса.
     *
     * Алсо - производит проверку его наличия в кэше, валидируя его актуальность по его хэшу.
     *
     * @param string      $references_class
     * @param Application $app
     *
     * @throws Exception
     *
     * @return mixed
     */
    protected function bootUpReferenceInstance($references_class, Application $app)
    {
        $cache = $this->cacheFactory(null, $app);

        /** @var ReferenceInterface $references_class */
        $hash = $references_class::getVendorStaticReferenceInstance()->getHash();

        if ($cache->has($cache_key = $this->generateCacheKey($references_class, $hash))) {
            return $cache->get($cache_key);
        }

        $instance = new $references_class;

        // По умолчанию - храним справочник в кэше одни сутки до его пересоздания
        $cache->put($cache_key, $instance, Carbon::now()->addDays(1));

        return $instance;
    }

    /**
     * Generate cache key name.
     *
     * @param array ...$arguments
     *
     * @return string
     */
    protected function generateCacheKey(...$arguments)
    {
        return sprintf('static_reference_%s', \crc32(\serialize($arguments)));
    }

    /**
     * Возвращает инстанс кэша приложения. Если не передать имя хранилища, то будет использовано то, что установлено
     * у приложения по умолчанию.
     *
     * @param null|string      $cache_storage_name Имя используемого хранилища кэша
     * @param null|Application $app                Инстанс приложения (опционально)
     *
     * @return CacheContract
     */
    protected function cacheFactory($cache_storage_name = null, $app = null)
    {
        /** @var Application $app */
        $app = $app instanceof Application
            ? $app
            : $this->app;

        $storage_name = $cache_storage_name === null
            ? $app->make('config')->get('cache.default')
            : $cache_storage_name;

        return $app->make('cache')->store($storage_name);
    }
}
