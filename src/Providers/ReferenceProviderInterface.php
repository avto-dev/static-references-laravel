<?php

namespace AvtoDev\StaticReferencesLaravel\Providers;

use Closure;
use Countable;
use AvtoDev\StaticReferencesLaravel\StaticReferencesInterface;

/**
 * Interface ReferenceProviderInterface.
 */
interface ReferenceProviderInterface extends Countable
{
    /**
     * ReferenceProviderInterface constructor.
     *
     * @param StaticReferencesInterface $references_stack
     */
    public function __construct(StaticReferencesInterface $references_stack);

    /**
     * Возвращает короткое имя справочника.
     *
     * Важно: крайне желательно использовать имена в camelCase, для удобного обращения с помощью магических методов.
     *
     * @return string
     */
    public static function getName();

    /**
     * Возвращает все элементы из своего стека.
     *
     * @return ReferenceEntityInterface[]
     */
    public function all();

    /**
     * Перебирает все элементы стека с помощью callback-функции.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function each(Closure $callback);
}
