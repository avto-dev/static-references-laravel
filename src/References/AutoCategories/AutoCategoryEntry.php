<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoCategories;

use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\References\ReferenceEntryInterface;

class AutoCategoryEntry implements ReferenceEntryInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * Create a new entry instance.
     *
     * @param string      $code
     * @param string|null $description
     */
    public function __construct(string $code, ?string $description)
    {
        $this->code        = $code;
        $this->description = $description;
    }

    /**
     * Get category code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * get category description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{code:string, description:?string}
     */
    public function toArray(): array
    {
        return [
            'code'        => $this->code,
            'description' => $this->description,
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
