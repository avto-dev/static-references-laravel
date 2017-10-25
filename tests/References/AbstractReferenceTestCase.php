<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\References;

use AvtoDev\StaticReferencesLaravel\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferencesLaravel\References\ReferenceInterface;
use AvtoDev\StaticReferencesLaravel\Tests\Mocks\StaticReferencesMock;
use AvtoDev\StaticReferencesLaravel\PreferencesProviders\ReferenceProviderInterface;

/**
 * Class AbstractReferenceTestCase.
 */
abstract class AbstractReferenceTestCase extends AbstractUnitTestCase
{
    /**
     * @var StaticReferencesMock
     */
    protected $static_references;

    /**
     * @var ReferenceInterface
     */
    protected $reference_instance;

    /**
     * @var string
     */
    protected $reference_class = null;

    /**
     * @var string
     */
    protected $reference_provider_class = null;

    /**
     * @var string[]
     */
    protected $binds = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->static_references  = new StaticReferencesMock();
        $this->reference_instance = $this->static_references->make($this->reference_provider_class);

        $this->assertInstanceOf($this->reference_class, $this->reference_instance);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->reference_instance);
        unset($this->static_references);

        parent::tearDown();
    }

    /**
     * Тест метода `all()`.
     *
     * @return void
     */
    public function testAll()
    {
        $class = $this->reference_instance->getReferenceEntryClassName();

        foreach ($this->reference_instance->all() as $item) {
            $this->assertInstanceOf($class, $item);
        }

        $this->assertInstanceOf($class, $this->reference_instance->random());
    }

    /**
     * Тест биндов.
     */
    public function testBinds()
    {
        /** @var ReferenceProviderInterface $provider */
        $provider = new $this->reference_provider_class();

        foreach (array_unique(array_merge($provider->binds(), $this->binds)) as $bind) {
            $this->assertInstanceOf($this->reference_class, $ref = $this->static_references->make($bind));
            $this->assertEquals($ref, $this->static_references->$bind);
        }
    }

    /**
     * Тест преобразования элементов справочника в массив и json.
     */
    public function testEntryToArrayAndToJson()
    {
        $first = $this->reference_instance->first();

        $this->assertTrue(is_array($first->toArray()));
        $this->assertJson($first->toJson());
    }
}
