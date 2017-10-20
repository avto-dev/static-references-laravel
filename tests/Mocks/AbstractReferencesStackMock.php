<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\Mocks;

use AvtoDev\StaticReferencesLaravel\AbstractReferencesStack;

/**
 * Class AbstractReferencesStackMock.
 */
class AbstractReferencesStackMock extends AbstractReferencesStack
{
    /**
     * {@inheritdoc}
     */
    public static function getConfigRootKeyName()
    {
        return 'test';
    }

    /**
     * {@inheritdoc}
     */
    public function getBasicReferencesClasses()
    {
        return [
            AbstractReferenceProviderMock::class,
        ];
    }
}
