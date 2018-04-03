<?php

namespace AvtoDev\StaticReferences\Tests\References;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;

/**
 * Class RepairMethodsReferenceTest.
 *
 * @group repair_methods
 */
class RepairMethodsReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var RepairMethods
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testArrayKeys()
    {
        foreach (['codes', 'description'] as $key_name) {
            $this->assertArrayHasKey($key_name, $this->instance->first()->toArray());
        }
    }

    /**
     * Тест базовых акцессоров данных.
     *
     * @return void
     */
    public function testBasicData()
    {
        $this->assertGreaterThan(18, count($this->instance->all()));
        $assert_with = 'Частичная замена';

        // По кодам.

        // На латинице
        $this->assertEquals($assert_with, $this->instance->getByCode('ET')->getDescription());
        $this->assertNull($this->instance->getByCode(' ET '));

        $this->assertFalse($this->instance->hasCode(' ET '));

        // По описаниям.
        $description = 'Частичная замена';
        $assert_with = 'ET';

        $this->assertContains($assert_with, $this->instance->getByDescription($description)->getCodes());
        $this->assertContains($assert_with, $this->instance->getByDescription(' ' . $description)->getCodes());
        $this->assertContains($assert_with, $this->instance->getByDescription(Str::upper($description))->getCodes());

        $this->assertTrue($this->instance->hasDescription($description));
        $this->assertFalse($this->instance->hasDescription('ЫDfsdgfs dsfDFfds'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceClassName()
    {
        return RepairMethods::class;
    }
}
