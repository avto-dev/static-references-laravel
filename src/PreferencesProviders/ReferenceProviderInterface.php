<?php

namespace AvtoDev\StaticReferencesLaravel\PreferencesProviders;

use AvtoDev\StaticReferencesLaravel\StaticReferencesInterface;
use AvtoDev\StaticReferencesLaravel\References\ReferenceInterface;

/**
 * Interface ReferenceProviderInterface.
 */
interface ReferenceProviderInterface
{
    /**
     * Если данный метод вернёт true, то инстанс самого **справочника** НЕ будет создан при создании его провайдера, а
     * создастся только при непосредственном вызове метода извлечения инстанса справочника по его бинду (алиасу).
     *
     * В противном случае - "тяжелый" метод ->instance() будет вызван сразу же при создании инстанса данного провайдера
     * справочника.
     *
     * @see instance()
     *
     * @return bool
     */
    public function isDeferred();

    /**
     * Вызывается автоматически при создании провайдера справочника.
     *
     * Не стоит размещать в данном методе какие-либо "тяжелые" операции.
     *
     * Использование его является строго опциональным.
     *
     * @param StaticReferencesInterface $static_references
     *
     * @return void
     */
    public function boot(StaticReferencesInterface $static_references);

    /**
     * Возвращает инстанс самого справочника. Должен содержать "тяжелый" конструктор инстанса самого справочника.
     *
     * @return ReferenceInterface
     */
    public function instance();

    /**
     * Возвращает массив алиасов-биндов для провайдера справочника. В дальнейшем, при вызове метода извлечения инстанса
     * самого **справочника** (ф не его провайдера) происходит это именно по этим биндам-алиасам.
     *
     * @return string[]
     */
    public function binds();
}
