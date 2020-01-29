<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

use Tarampampam\Wrappers\Json;

class AutoRegion implements EntityInterface
{
    /**
     * @link <https://goo.gl/LnRyLS>
     *
     * @var int
     */
    protected $region_code;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string[]
     */
    protected $short_titles;

    /**
     * @var int[]
     */
    protected $auto_codes;

    /**
     * @var string
     */
    protected $okato;

    /**
     * @var string
     */
    protected $iso_31662;

    /**
     * @var string
     */
    protected $type;

    /**
     * Create a new entity instance.
     *
     * @param int      $region_code
     * @param string   $title
     * @param string[] $short_titles
     * @param int[]    $auto_codes
     * @param string   $okato
     * @param string   $iso_31662
     * @param string   $type
     */
    public function __construct(
        int $region_code,
        string $title,
        array $short_titles,
        array $auto_codes,
        string $okato,
        string $iso_31662,
        string $type
    )
    {
        $this->region_code  = $region_code;
        $this->title        = $title;
        $this->short_titles = $short_titles;
        $this->auto_codes   = $auto_codes;
        $this->okato        = $okato;
        $this->iso_31662    = $iso_31662;
        $this->type         = $type;
    }

    /**
     * Get region code.
     *
     * @link <https://goo.gl/LnRyLS>
     *
     * @return int
     */
    public function getRegionCode(): int
    {
        return $this->region_code;
    }

    /**
     * Get region title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get region short titles.
     *
     * @return string[]
     */
    public function getShortTitles(): array
    {
        return $this->short_titles;
    }

    /**
     * Get GIBDD codes.
     *
     * @return int[]
     */
    public function getAutoCodes(): array
    {
        return $this->auto_codes;
    }

    /**
     * Get OKATO region code.
     *
     * @return string
     */
    public function getOkato(): string
    {
        return $this->okato;
    }

    /**
     * Get region code (ISO-31662).
     *
     * @return string
     */
    public function getIso31662(): string
    {
        return $this->iso_31662;
    }

    /**
     * Get type (`республика`, `край`, etc).
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{
     *                region_code:int,
     *                title:string,
     *                auto_codes:array,
     *                iso_31662:string,
     *                okato:string,
     *                short_titles:array,
     *                type:string
     *              }
     */
    public function toArray(): array
    {
        return [
            'region_code'  => $this->region_code,
            'title'        => $this->title,
            'auto_codes'   => $this->auto_codes,
            'iso_31662'    => $this->iso_31662,
            'okato'        => $this->okato,
            'short_titles' => $this->short_titles,
            'type'         => $this->type,
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
