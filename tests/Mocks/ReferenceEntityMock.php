<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\Mocks;

use AvtoDev\StaticReferencesLaravel\Providers\ReferenceEntityInterface;

class ReferenceEntityMock implements ReferenceEntityInterface
{
    public $raw_data;

    public $some;

    public $value;

    public function __construct(array $raw_data = [])
    {
        $this->raw_data = $raw_data;

        $this->some  = $raw_data['some'];
        $this->value = $raw_data['value'];
    }

    public function configure($input = [])
    {
        // @todo: Implement configure() method.
    }
}
