<?php

namespace AvtoDev\StaticReferencesLaravel;

use Exception;
use ReflectionClass;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Cache\Repository as CacheRepository;
use AvtoDev\StaticReferencesLaravel\Traits\InstanceableTrait;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use AvtoDev\StaticReferencesLaravel\Providers\ReferenceProviderInterface;

/**
 * Class AbstractReferencesStack.
 *
 * Абстрактный класс стека справочников.
 */
abstract class AbstractReferencesStack implements ReferencesStackInterface
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
     * Стек инстансов провайдеров справочников, где ключ массива - это его класс.
     *
     * @var ReferenceProviderInterface[]
     */
    protected $instances = [];

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
        $this->initializeInstances();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getBasicReferencesClasses();

    /**
     * {@inheritdoc}
     */
    public function getProvidersClasses()
    {
        $result = [];

        $classes = array_merge_recursive(
            $this->getBasicReferencesClasses(),
            (array) $this->getConfigValue('extra_references')
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
     * Производит инициализацию инстансов справочников.
     *
     * @return void
     */
    protected function initializeInstances()
    {
        $cache_enabled   = $this->getConfigValue('cache.enabled') === true;
        $this->instances = []; // Не уверен что это оптимально будет очищать память при повторном вызове

        // Создаем инстансы справочников
        foreach ($this->getProvidersClasses() as $provider_class) {
            // Формируем имя ключа для кэша
            $cache_key = static::CACHE_KEY_PREFIX . class_basename($provider_class);

            // Если инстанс справочника есть в кэше
            if ($cache_enabled && $this->getCacheRepository()->has($cache_key)) {
                // То берем его из него
                $this->instances[$provider_class] = $this->getCacheRepository()->get($cache_key);
            } else {
                // Иначе - создаем новый инстанс провайдера справочника
                $this->instances[$provider_class] = $this->referenceInstanceFactory($provider_class);

                // И помещаем его ИНСТАНС в кэш
                if ($cache_enabled) {
                    $this->getCacheRepository()->forever($cache_key, $this->instances[$provider_class]);
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

        if (! ($instance instanceof ConfigRepository)) {
            $instance = $this->app->make('config');
        }

        return $instance->get(sprintf('%s.%s', static::getConfigRootKeyName(), $key), $default);
    }

    /**
     * Возвращает инстанс кConfigurableэша.
     *
     * @return CacheRepository|null
     */
    protected function getCacheRepository()
    {
        static $instance = null;

        if (! ($instance instanceof CacheRepository)) {
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
}
