<?php

namespace AvtoDev\StaticReferencesLaravel\PreferencesProviders;

use AvtoDev\StaticReferencesLaravel\References\ReferenceInterface;
use AvtoDev\StaticReferencesLaravel\StaticReferencesInterface;

interface ReferenceProviderInterface
{
    /**
     * Если данный метод вернёт true, то инстанс самого справочника будет создан при создании его провайдера. В
     * противном случае - инстанс будет создаваться только при непосредственном вызове провайдера по его бинду (алиасу).
     *
     * @return bool
     */
    public function isDeferred();

    /**
     * Вызывается автоматически при создании провайдера справочника.
     *
     * Не стоит размещать в данном методе какие-либо "тяжелые" операции.
     *
     * @param StaticReferencesInterface $static_references
     *
     * @return void
     */
    public function boot(StaticReferencesInterface $static_references);

    /**
     * Возвращает инстанс самого справочника.
     *
     * @return ReferenceInterface
     */
    public function instance();

    /**
     * Возвращает массив алиасов-биндов для провайдера справочника.
     *
     * @return string[]
     */
    public function binds();
}
