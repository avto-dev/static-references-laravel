<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

use Tarampampam\Wrappers\Json;

class SubjectCodesInfo implements EntityInterface
{
    /**
     * @link <https://goo.gl/LnRyLS>
     *
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int[]
     */
    protected $gibdd;

    /**
     * @var string
     */
    protected $code_iso_31662;

    /**
     * Create a new entity instance.
     *
     * @param int    $code
     * @param string $title
     * @param int[]  $gibdd
     * @param string $code_iso_31662
     */
    public function __construct(int $code, string $title, array $gibdd, string $code_iso_31662)
    {
        $this->code           = $code;
        $this->title          = $title;
        $this->gibdd          = $gibdd;
        $this->code_iso_31662 = $code_iso_31662;
    }

    /**
     * Get subject code.
     *
     * @link <https://goo.gl/LnRyLS>
     *
     * @return int
     */
    public function getSubjectCode(): int
    {
        return $this->code;
    }

    /**
     * Get subject title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get GIBDD codes that used in current subject.
     *
     * @return int[]
     */
    public function getGibddCodes(): array
    {
        return $this->gibdd;
    }

    /**
     * Get subject code (ISO-31662).
     *
     * @return string
     */
    public function getIso31662Code(): string
    {
        return $this->code_iso_31662;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{code:int, title:string, gibdd:array, code_iso_31662:string}
     */
    public function toArray(): array
    {
        return [
            'code'           => $this->code,
            'title'          => $this->title,
            'gibdd'          => $this->gibdd,
            'code_iso_31662' => $this->code_iso_31662,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0): string
    {
        return (string) Json::encode($this->toArray(), $options);
    }
}
