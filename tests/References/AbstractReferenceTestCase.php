<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoCategories\AutoCategoryEntry::configure
 * @covers \AvtoDev\StaticReferences\References\AutoFines\AutoFineEntry::configure
 * @covers \AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry::configure
 * @covers \AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActionEntry::configure
 * @covers \AvtoDev\StaticReferences\References\RepairMethods\RepairMethodsEntry::configure
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
    protected function setUp(): void
    {
        parent::setUp();

        $this->clearCache();

        $this->instance = $this->app->make($this->getReferenceClassName());
    }

    /**
     * @return void
     */
    public function testAll(): void
    {
        $class = $this->instance->getReferenceEntryClassName();

        foreach ($this->instance->all() as $item) {
            $this->assertInstanceOf($class, $item);
        }

        $this->assertInstanceOf($class, $this->instance->random());
    }

    /**
     * @return void
     */
    public function testEntryToArrayAndToJson(): void
    {
        $first = $this->instance->first();

        $this->assertInternalType('array', $first->toArray());
        $this->assertJson($first->toJson());
    }

    /**
     * @return void
     */
    abstract public function testArrayKeys(): void;

    /**
     * @return string
     */
    abstract protected function getReferenceClassName(): string;
}
