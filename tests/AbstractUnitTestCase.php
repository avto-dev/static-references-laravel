<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AbstractUnitTestCase extends BaseTestCase
{
    use Traits\ApplicationHelpersTrait,
        Traits\CreatesApplicationTrait;
}
