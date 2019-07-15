<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions;

/**
 * @covers \AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions<extended>
 * @covers \AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActionEntry<extended>
 */
class RegistrationActionsReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var RegistrationActions
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
        $this->assertGreaterThan(50, count($this->instance->all()));
        $assert_with = 'Восстановление регистрации после аннулирования';

        /*
         * По кодам.
         */
        // На латинице
        $this->assertEquals($assert_with, $this->instance->getByCode(18)->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByCode('18')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByCode(' 18 ')->getDescription());

        $this->assertTrue($this->instance->hasCode(18));
        $this->assertTrue($this->instance->hasCode(' ffff 18 '));
        $this->assertFalse($this->instance->hasCode('Ы'));

        /*
         * По описаниям.
         */
        $description       = 'Восстановление регистрации после аннулирования';
        $description_short = ' после аннулирования';
        $assert_with       = 18;

        $this->assertContains($assert_with, $this->instance->getByDescription($description)->getCodes());
        $this->assertContains($assert_with, $this->instance->getByDescription(' ' . $description)->getCodes());
        $this->assertContains($assert_with, $this->instance->getByDescription($description_short)->getCodes());
        $this->assertContains($assert_with, $this->instance->getByDescription(Str::upper($description))->getCodes());

        $this->assertTrue($this->instance->hasDescription($description));
        $this->assertTrue($this->instance->hasDescription($description_short));
        $this->assertFalse($this->instance->hasDescription('ЫDfsdgfs dsfDFfds'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceClassName(): string
    {
        return RegistrationActions::class;
    }
}
