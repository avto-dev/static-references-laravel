<?php

namespace AvtoDev\StaticReferencesLaravel\Providers;

use Closure;
use Countable;
use AvtoDev\StaticReferencesLaravel\ReferencesStackInterface;

/**
 * Interface ReferenceProviderInterface.
 */
interface ReferenceProviderInterface extends Countable
{
    /**
     * Возвращает короткое имя справочника.
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

    /**
     * ReferenceProviderInterface constructor.
     *
     * @param ReferencesStackInterface $references_stack
     */
    public function __construct(ReferencesStackInterface $references_stack);
}
