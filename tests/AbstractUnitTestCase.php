<?php

namespace AvtoDev\StaticReferences\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class AbstractUnitTestCase.
 */
abstract class AbstractUnitTestCase extends BaseTestCase
{
    use Traits\ApplicationHelpersTrait,
        Traits\CreatesApplicationTrait;
}
