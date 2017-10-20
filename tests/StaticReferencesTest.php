<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\AbstractReferencesStack;
use AvtoDev\StaticReferencesLaravel\ReferencesStackInterface;
use AvtoDev\StaticReferencesLaravel\StaticReferences;

/**
 * Class StaticReferencesTest.
 */
class StaticReferencesTest extends AbstractUnitTestCase
{
    /**
     * @var StaticReferences
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new StaticReferences($this->app);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * Тест реализации.
     *
     * @return void
     */
    public function testImplementations()
    {
        $this->assertInstanceOf(ReferencesStackInterface::class, $this->instance);
        $this->assertInstanceOf(AbstractReferencesStack::class, $this->instance);
    }

    /**
     * Тест метода `getBasicReferencesClasses()`.
     *
     * @return void
     */
    public function testGetBasicReferencesClasses()
    {
        $this->assertTrue(is_array($this->instance->getBasicReferencesClasses()));
    }
}
