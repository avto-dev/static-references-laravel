<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences;

use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;
use AvtoDev\StaticReferences\References\AutoFines\AutoFines;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions;
use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Contracts\Container\Container;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Get references classes.
     *
     * @return string[]
     */
    public function getReferencesClasses(): array
    {
        return [
            AutoRegions::class,
            AutoCategories::class,
            RegistrationActions::class,
            RepairMethods::class,
            AutoFines::class,
            CadastralRegions::class,
        ];
    }

    /**
     * Register package services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->getReferencesClasses() as $references_class) {
            $this->app->singleton($references_class, function (Container $app) use ($references_class) {
                return $this->bootUpReferenceInstance($app, $references_class);
            });
        }
    }

    /**
     * Get reference instance by class name.
     *
     * @param Container $app
     * @param string    $references_class
     *
     * @throws Exception
     *
     * @return ReferenceInterface
     */
    protected function bootUpReferenceInstance(Container $app, string $references_class): ReferenceInterface
    {
        $cache = $this->cacheFactory($app);

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
    protected function generateCacheKey(...$arguments): string
    {
        return \sprintf('static_reference_%s', \crc32(\serialize($arguments)));
    }

    /**
     * Get cache instance.
     *
     * @param Container   $app
     * @param null|string $cache_storage_name
     *
     * @return CacheContract
     */
    protected function cacheFactory(Container $app, ?string $cache_storage_name = null): CacheContract
    {
        return $app->make('cache')->store($cache_storage_name ?? $app->make('config')->get('cache.default'));
    }
}
