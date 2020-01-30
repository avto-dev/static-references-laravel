<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

use Tarampampam\Wrappers\Json;

class VehicleType implements EntityInterface
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $group_title;

    /**
     * @var string
     */
    protected $group_slug;

    /**
     * Create a new entity instance.
     *
     * @param int    $code
     * @param string $title
     * @param string $group_title
     * @param string $group_slug
     */
    public function __construct(int $code, string $title, string $group_title, string $group_slug)
    {
        $this->code        = $code;
        $this->title       = $title;
        $this->group_title = $group_title;
        $this->group_slug  = $group_slug;
    }

    /**
     * Get type code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Get type title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get type group title.
     *
     * @return string
     */
    public function getGroupTitle(): string
    {
        return $this->group_title;
    }

    /**
     * Get type group slug.
     *
     * @return string
     */
    public function getGroupSlug(): string
    {
        return $this->group_slug;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{code:int, title:string, group_title:string, group_slug:string}
     */
    public function toArray(): array
    {
        return [
            'code'        => $this->code,
            'title'       => $this->title,
            'group_title' => $this->group_title,
            'group_slug'  => $this->group_slug,
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
