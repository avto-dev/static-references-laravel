<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\AutoFines\AutoFines;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoFines\AutoFines<extended>
 * @covers \AvtoDev\StaticReferences\References\AutoFines\AutoFineEntry<extended>
 */
class AutoFinesReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var AutoFines
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testArrayKeys(): void
    {
        foreach (['article', 'description'] as $key_name) {
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
        $this->assertGreaterThanOrEqual(179, count($this->instance->all()));
        $assert_with = 'Нарушение правил перевозки грузов, а равно правил буксировки';

        /*
         * По кодам.
         */
        // На кириллице
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21.1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21Ч.1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21часть  1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21 часть 1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21 ч. 1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle(' 12.21   ч.   1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21ч1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('   12.21ч1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21Ч1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21Ч1 ')->getDescription());

        // На латинице
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21 part.1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21P.1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21part  1')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByArticle('12.21 PaRt 1')->getDescription());

        $articles = [
            '12.21.1 Ч.9',
            '12.29 Ч.1',
            '12.32.1',
            '14.38 Ч.3',
            '8.23',
            '11.21 Ч.1',
            '12.16 Ч.3.1',
            '12.21.1 Ч.1',
        ];

        foreach ($articles as $article) {
            $this->assertTrue($this->instance->hasArticle($article));
        }

        foreach (['dfsfsdf', '123123', '123.123.123', 'sdgdf356gsd'] as $invalid_article) {
            $this->assertFalse($this->instance->hasArticle($invalid_article));
        }

        /*
         * По описаниям.
         */
        $assert_with = '12.22';
        $description = 'Нарушение правил учебной езды';

        $this->assertEquals($assert_with, $this->instance->getByDescription($description)->getArticle());
        $this->assertEquals($assert_with, $this->instance->getByDescription(' ' . $description)->getArticle());
        $this->assertEquals($assert_with, $this->instance->getByDescription($description . ' ')->getArticle());
        $this->assertEquals($assert_with, $this->instance->getByDescription(Str::upper($description))->getArticle());

        $this->assertTrue($this->instance->hasDescription($description));
        $this->assertTrue($this->instance->hasDescription(' ' . $description));
        $this->assertFalse($this->instance->hasDescription('ЫDfsdgfs dsfDFfds'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceClassName(): string
    {
        return AutoFines::class;
    }
}
