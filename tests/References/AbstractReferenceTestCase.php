<?php

namespace AvtoDev\StaticReferences\Tests\References;

use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;

/**
 * Class AbstractReferenceTestCase.
 */
abstract class AbstractReferenceTestCase extends AbstractUnitTestCase
{
    /**
     * @var ReferenceInterface
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = $this->app->make($this->getReferenceClassName());
    }

    /**
     * Тест метода `all()`.
     *
     * @return void
     */
    public function testAll()
    {
        $class = $this->instance->getReferenceEntryClassName();

        foreach ($this->instance->all() as $item) {
            $this->assertInstanceOf($class, $item);
        }

        $this->assertInstanceOf($class, $this->instance->random());
    }

    /**
     * Тест преобразования элементов справочника в массив и json.
     */
    public function testEntryToArrayAndToJson()
    {
        $first = $this->instance->first();

        $this->assertTrue(is_array($first->toArray()));
        $this->assertJson($first->toJson());
    }

    /**
     * Тест метода преобразования элемента справочника в массив.
     *
     * @return void
     */
    abstract public function testArrayKeys();

    /**
     * Returns reference class name.
     *
     * @return string
     */
    abstract protected function getReferenceClassName();
}
