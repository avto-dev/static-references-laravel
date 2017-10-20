<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\Providers;

use AvtoDev\StaticReferencesLaravel\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferencesLaravel\Tests\Mocks\AbstractReferenceProviderMock;
use AvtoDev\StaticReferencesLaravel\Tests\Mocks\AbstractReferencesStackMock;
use AvtoDev\StaticReferencesLaravel\Tests\Mocks\ReferenceEntityMock;

/**
 * Class AbstractReferenceTest.
 */
class AbstractReferenceTest extends AbstractUnitTestCase
{
    /**
     * @var AbstractReferenceProviderMock
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new AbstractReferenceProviderMock(new AbstractReferencesStackMock($this->app));
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
     * Тест метода `getName()`.
     *
     * @return void
     */
    public function testGetName()
    {
        $this->assertEquals('some_test', $this->instance->getName());
    }

    /**
     * Тест метода `getName()`.
     *
     * @return void
     */
    public function testAll()
    {
        $this->assertCount(2, $all = $this->instance->all());

        $this->assertEquals('one', $all[0]->some);
        $this->assertEquals(1, $all[0]->value);

        $this->assertEquals('two', $all[1]->some);
        $this->assertEquals(2, $all[1]->value);
    }

    /**
     * Тест метода `each()`.
     *
     * @return void
     */
    public function testEach()
    {
        $counter = 0;

        $this->instance->each(function (ReferenceEntityMock $item) use (&$counter) {
            if ($item->some === 'one') {
                ++$counter;
            }
            if ($item->some === 'two') {
                ++$counter;
            }
        });

        $this->assertEquals(2, $counter);
    }
}
