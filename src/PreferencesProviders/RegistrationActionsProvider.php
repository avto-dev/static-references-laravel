<?php

namespace AvtoDev\StaticReferencesLaravel\PreferencesProviders;

use AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionsReference;

/**
 * Class RegistrationActionsProvider.
 *
 * Провайдер справочника "Регистрационные действия".
 */
class RegistrationActionsProvider extends AbstractReferenceProvider
{
    /**
     * {@inheritdoc}
     */
    public function instance()
    {
        return new RegistrationActionsReference;
    }

    /**
     * {@inheritdoc}
     */
    public function binds()
    {
        return ['RegistrationActions', 'registrationActions', RegistrationActionsReference::class];
    }
}
