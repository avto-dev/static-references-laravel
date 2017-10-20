<?php

namespace AvtoDev\StaticReferencesLaravel;

use AvtoDev\StaticReferencesLaravel\Providers\AutoCategories\AutoCategoriesProvider;
use AvtoDev\StaticReferencesLaravel\Providers\ReferenceProviderInterface;
use AvtoDev\StaticReferencesLaravel\Traits\InstanceableTrait;
use Exception;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use ReflectionClass;

/**
 * Class StaticReferences.
 *
 * Стек статических справочников.
 *
 * @property-read AutoCategoriesProvider $autoCategories Справочник категорий ТС
 */
class StaticReferences implements StaticReferencesInterface
{
    use InstanceableTrait;

    /**
     * Префикс для ключей кэша.
     *
     * @var string
     */
    const CACHE_KEY_PREFIX = 'reference_';

    /**
     * @var Application
     */
    protected $app;

    /**
     * Стек инстансов провайдеров справочников.
     *
     * @var ReferenceProviderInterface[]
     */
    protected $providers = [];

    /**
     * StaticReferences constructor.
     *
     * @param Application $app
     *
     * @throws Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->initializeProviders();
    }

    /**
     * Производит инициализацию инстансов справочников.
     *
     * @return void
     */
    protected function initializeProviders()
    {
        $cache_enabled = $this->getConfigValue('cache.enabled') === true;
        $this->providers = []; // Не уверен что это оптимально будет очищать память при повторном вызове

        // Создаем инстансы справочников
        foreach ($this->getProvidersClasses() as $provider_class) {
            // Формируем имя ключа для кэша
            $cache_key = static::CACHE_KEY_PREFIX . class_basename($provider_class);

            // Если инстанс справочника есть в кэше
            if ($cache_enabled && $this->getCacheRepository()->has($cache_key)) {
                // То берем его из него
                array_push($this->providers, $this->getCacheRepository()->get($cache_key));
            } else {
                // Иначе - создаем новый инстанс провайдера справочника
                array_push($this->providers, $instance = $this->referenceInstanceFactory($provider_class));

                // И помещаем его ИНСТАНС в кэш
                if ($cache_enabled) {
                    $this->getCacheRepository()->forever($cache_key, $instance);
                }
            }
        }
    }

    /**
     * Извлекает значение конфига справочников по его имени. Имя корневого элемента при этом указывать не требуется.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function getConfigValue($key, $default = null)
    {
        static $instance = null;

        if (!($instance instanceof ConfigRepository)) {
            $instance = $this->app->make('config');
        }

        return $instance->get(sprintf('%s.%s', static::getConfigRootKeyName(), $key), $default);
    }

    /**
     * {@inheritdoc}
     */
    public static function getConfigRootKeyName()
    {
        return 'static-references';
    }

    /**
     * {@inheritdoc}
     */
    public function getProvidersClasses()
    {
        $result = [];

        $classes = array_merge_recursive(
            $this->getPackageProvidersClasses(),
            (array) $this->getConfigValue('user_providers')
        );

        foreach ($classes as $class) {
            $reflection = new ReflectionClass($class);
            // Убеждаемся в том, что классы реализуют интерфейс
            if ($reflection->implementsInterface(ReferenceProviderInterface::class)) {
                array_push($result, $class);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getPackageProvidersClasses()
    {
        return [
            AutoCategoriesProvider::class,
        ];
    }

    /**
     * Возвращает инстанс кэша.
     *
     * @return CacheRepository|null
     */
    protected function getCacheRepository()
    {
        static $instance = null;

        if (!($instance instanceof CacheRepository)) {
            $storage = ($name = $this->getConfigValue('cache.store')) === 'auto'
                ? config('cache.default')
                : $name;

            $instance = $this->app->make('cache')->store($storage);
        }

        return $instance;
    }

    /**
     * Факторка по созданию инстансов справочников.
     *
     * @param string $reference_class
     * @param array  ...$arguments
     *
     * @return ReferenceProviderInterface
     */
    protected function referenceInstanceFactory($reference_class, ...$arguments)
    {
        return new $reference_class($this, ...$arguments);
    }

    /**
     * Возвращает инстанс провайдера справочника по его имени или классу.
     *
     * @param string $provider_name_or_class
     *
     * @return ReferenceProviderInterface|null
     */
    public function get($provider_name_or_class)
    {
        if (($instance = $this->getByName($provider_name_or_class)) instanceof ReferenceProviderInterface) {
            return $instance;
        } elseif (($instance = $this->getByClass($provider_name_or_class)) instanceof ReferenceProviderInterface) {
            return $instance;
        }
    }

    /**
     * Возвращает инстанс провайдера справочника по его имени.
     *
     * @param string $provider_name
     *
     * @return ReferenceProviderInterface|null
     */
    public function getByName($provider_name)
    {
        foreach ($this->providers as $provider) {
            if ($provider->getName() === $provider_name) {
                return $provider;
            }
        }
    }

    /**
     * Возвращает инстанс провайдера справочника по имени его класса.
     *
     * @param string $provider_class
     *
     * @return ReferenceProviderInterface
     */
    public function getByClass($provider_class)
    {
        foreach ($this->providers as $provider) {
            if (get_class($provider) === $provider_class) {
                return $provider;
            }
        }
    }

    /**
     * При обращении к провайдеру справочника по имени - пытаемся его вернуть.
     *
     * @param string $provider_name
     *
     * @return ReferenceProviderInterface|null
     */
    public function __get($provider_name)
    {
        return $this->getByName($provider_name);
    }
}
