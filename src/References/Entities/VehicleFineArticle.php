<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

use Tarampampam\Wrappers\Json;

class VehicleFineArticle implements EntityInterface
{
    /**
     * @var string
     */
    protected $article;

    /**
     * @var string
     */
    protected $description;

    /**
     * Create a new entity instance.
     *
     * @param string $article
     * @param string $description
     */
    public function __construct(string $article, string $description)
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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{article:string, description:string}
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
