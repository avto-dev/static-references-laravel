<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\AbstractReferencesStack;
use AvtoDev\StaticReferencesLaravel\ReferencesStackInterface;
use AvtoDev\StaticReferencesLaravel\Traits\InstanceableTrait;
use AvtoDev\StaticReferencesLaravel\Tests\Mocks\AbstractReferencesStackMock;

/**
 * Class AbstractReferencesStackTest.
 */
class AbstractReferencesStackTest extends AbstractUnitTestCase
{
    /**
     * @var AbstractReferencesStackMock
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new AbstractReferencesStackMock($this->app);
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
    }

    /**
     * Тест методов, прилетевших из трейтов.
     *
     * @return void
     */
    public function testTraits()
    {
        /*
         * @see InstanceableTrait
         */
        $this->assertInstanceOf(AbstractReferencesStackMock::class, $this->instance->instance());
    }

    /**
     * Тест публичных констант.
     *
     * @return void
     */
    public function testConstants()
    {
        $this->assertNotEmpty(AbstractReferencesStack::CACHE_KEY_PREFIX);
    }

    /**
     * Тест метода `getProvidersClasses()`.
     *
     * @return void
     */
    public function testGetProvidersClasses()
    {
        $this->assertNotEmpty($this->instance->getProvidersClasses());

        foreach ($this->instance->getProvidersClasses() as $provider_class) {
            $this->assertTrue(class_exists($provider_class));
        }
    }
}
