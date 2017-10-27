<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\References;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\PreferencesProviders\RegistrationActionsProvider;
use AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionsReference;

class RegistrationActionsReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var RegistrationActionsReference
     */
    protected $reference_instance;

    /**
     * @var string
     */
    protected $reference_class = RegistrationActionsReference::class;

    /**
     * @var string
     */
    protected $reference_provider_class = RegistrationActionsProvider::class;

    /**
     * {@inheritdoc}
     */
    function testArrayKeys()
    {
        foreach (['codes', 'description'] as $key_name) {
            $this->assertArrayHasKey($key_name, $this->reference_instance->first()->toArray());
        }
    }

    /**
     * Тест базовых акцессоров данных.
     *
     * @return void
     */
    public function testBasicData()
    {
        $this->assertGreaterThan(50, count($this->reference_instance->all()));
        $assert_with = 'Восстановление регистрации после аннулирования';

        /*
         * По кодам.
         */
        // На латинице
        $this->assertEquals($assert_with, $this->reference_instance->getByCode(18)->getDescription());
        $this->assertEquals($assert_with, $this->reference_instance->getByCode('18')->getDescription());
        $this->assertEquals($assert_with, $this->reference_instance->getByCode(' 18 ')->getDescription());

        $this->assertTrue($this->reference_instance->hasCode(18));
        $this->assertTrue($this->reference_instance->hasCode(' ffff 18 '));
        $this->assertFalse($this->reference_instance->hasCode('Ы'));

        /*
         * По описаниям.
         */
        $description       = 'Восстановление регистрации после аннулирования';
        $description_short = ' после аннулирования';
        $assert_with       = 18;

        $this->assertContains($assert_with, $this->reference_instance->getByDescription($description)->getCodes());
        $this->assertContains($assert_with, $this->reference_instance->getByDescription(' ' . $description)->getCodes());
        $this->assertContains($assert_with, $this->reference_instance->getByDescription($description_short)->getCodes());
        $this->assertContains($assert_with, $this->reference_instance->getByDescription(Str::upper($description))->getCodes());

        $this->assertTrue($this->reference_instance->hasDescription($description));
        $this->assertTrue($this->reference_instance->hasDescription($description_short));
        $this->assertFalse($this->reference_instance->hasDescription('ЫDfsdgfs dsfDFfds'));
    }
}
