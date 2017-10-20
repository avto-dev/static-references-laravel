<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\Mocks;

use AvtoDev\StaticReferencesLaravel\Providers\AbstractReferenceProvider;

/**
 * Class AbstractReferenceProviderMock.
 */
class AbstractReferenceProviderMock extends AbstractReferenceProvider
{
    public static function getName()
    {
        return 'some_test';
    }

    protected function getReferenceEntityClassName()
    {
        return ReferenceEntityMock::class;
    }

    protected function getSourcesFilesPaths()
    {
        return __DIR__ . '/some_data.json';
    }
}
