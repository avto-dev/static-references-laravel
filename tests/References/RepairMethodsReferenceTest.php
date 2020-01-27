<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;
use Illuminate\Support\Str;

/**
 * @covers \AvtoDev\StaticReferences\References\RepairMethods\RepairMethods<extended>
 * @covers \AvtoDev\StaticReferences\References\RepairMethods\RepairMethodsEntry<extended>
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
    public function testArrayKeys(): void
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
    public function testBasicData(): void
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
    protected function getReferenceClassName(): string
    {
        return RepairMethods::class;
    }
}
