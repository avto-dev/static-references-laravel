<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use AvtoDev\StaticReferences\References\SubjectCodes;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\SubjectCodes
 */
class SubjectCodesTest extends AbstractUnitTestCase
{
    /**
     * @var SubjectCodes
     */
    protected $reference;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var m\MockInterface|StaticReferenceInterface $static_reference */
        $static_reference = m::mock(StaticReferenceInterface::class)
            ->expects('getData')
            ->andReturn([
                [
                    'title'          => 'foo title',
                    'code'           => 1,
                    'gibdd'          => [10],
                    'code_iso_31662' => 'foo-1',
                ],
                [
                    'title'          => 'bar title',
                    'code'           => 2,
                    'gibdd'          => [20],
                    'code_iso_31662' => 'bar-1',
                ],
            ])
            ->once()
            ->getMock();

        $this->reference = new SubjectCodes($static_reference);
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
            $this->assertInstanceOf(SubjectCodesInfo::class, $item);
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
    public function testGetByGibddCode(): void
    {
        $this->assertSame('foo title', $this->reference->getByGibddCode(10)->getTitle());
        $this->assertSame('bar title', $this->reference->getByGibddCode(20)->getTitle());

        $this->assertNull($this->reference->getByGibddCode(\random_int(21, 100)));
    }

    /**
     * @return void
     */
    public function testHasGibddCode(): void
    {
        $this->assertTrue($this->reference->hasGibddCode(10));
        $this->assertTrue($this->reference->hasGibddCode(20));

        $this->assertFalse($this->reference->hasGibddCode(\random_int(21, 100)));
    }

    /**
     * @return void
     */
    public function testGetBySubjectCode(): void
    {
        $this->assertSame('foo title', $this->reference->getBySubjectCode(1)->getTitle());
        $this->assertSame('bar title', $this->reference->getBySubjectCode(2)->getTitle());

        $this->assertNull($this->reference->getBySubjectCode(\random_int(3, 100)));
    }

    /**
     * @return void
     */
    public function testHasSubjectCode(): void
    {
        $this->assertTrue($this->reference->hasSubjectCode(1));
        $this->assertTrue($this->reference->hasSubjectCode(2));

        $this->assertFalse($this->reference->hasSubjectCode(\random_int(3, 100)));
    }

    /**
     * @return void
     */
    public function testSameObjectOnDifferentGetters(): void
    {
        $first  = $this->reference->getBySubjectCode(1);
        $second = $this->reference->getByGibddCode(10);

        $this->assertSame($first, $second);
    }

    /**
     * @return void
     */
    public function testCount(): void
    {
        $this->assertSame(2, $this->reference->count());
    }
}
