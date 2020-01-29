<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AvtoDev\StaticReferences\References\AutoFines;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\AutoFine;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoFines<extended>
 */
class AutoFinesTest extends AbstractUnitTestCase
{
    /**
     * @var AutoFines
     */
    protected $reference;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var m\MockInterface|StaticReference $static_reference */
        $static_reference = m::mock(StaticReference::class)
            ->expects('getData')
            ->andReturn([
                ['article' => '1.2.3 Ч.3', 'description' => 'foo desc'],
                ['article' => '2.3', 'description' => 'bar desc'],
            ])
            ->once()
            ->getMock();

        $this->reference = new AutoFines($static_reference);
    }

    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(ReferenceInterface::class, $this->reference);
    }

    /**
     * @return void
     */
    public function testIterator(): void
    {
        $array = [];

        foreach ($this->reference as $item) {
            $this->assertInstanceOf(AutoFine::class, $item);
            $array[] = $item;
        }

        $this->assertCount(2, $array);
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = $this->reference->toArray();

        $this->assertCount(2, $as_array);

        foreach ($as_array as $item) {
            $this->assertIsArray($item);
            $this->assertNotEmpty($item);
        }
    }

    /**
     * @return void
     */
    public function testGetByCode(): void
    {
        $this->assertSame($foo_desc = 'foo desc', $this->reference->getByArticle('1.2.3 Ч.3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3.3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 часть 3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 part 3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('  1.2.3 part 3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 part 3  ')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 3')->getDescription());

        $this->assertNull($this->reference->getByArticle(Str::random()));
    }

    /**
     * @return void
     */
    public function testHasCode(): void
    {
        $this->assertTrue($this->reference->hasArticle('1.2.3 Ч.3'));
        $this->assertTrue($this->reference->hasArticle('2.3'));

        $this->assertTrue($this->reference->hasArticle('1.2.3.3'));
        $this->assertTrue($this->reference->hasArticle('1.2.3 часть 3'));
        $this->assertTrue($this->reference->hasArticle('1.2.3 part 3'));
        $this->assertTrue($this->reference->hasArticle('  1.2.3 part 3'));
        $this->assertTrue($this->reference->hasArticle('1.2.3 part 3  '));
        $this->assertTrue($this->reference->hasArticle('1.2.3 3'));

        $this->assertFalse($this->reference->hasArticle(Str::random()));
    }

    /**
     * @return void
     */
    public function testCount(): void
    {
        $this->assertSame(2, $this->reference->count());
    }
}
