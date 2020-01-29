<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoFines;

use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\References\ReferenceEntryInterface;

class AutoFineEntry implements ReferenceEntryInterface
{
    /**
     * @var string
     */
    protected $article;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * Create a new entry instance.
     *
     * @param string      $article
     * @param string|null $description
     */
    public function __construct(string $article, ?string $description)
    {
        $this->article     = $article;
        $this->description = $description;
    }

    /**
     * Get auto fine article number.
     *
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }

    /**
     * Get fine article description.
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
     * @return array{article:string, description:?string}
     */
    public function toArray(): array
    {
        return [
            'article'     => $this->article,
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
