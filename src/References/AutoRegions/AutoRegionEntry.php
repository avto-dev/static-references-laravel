<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoRegions;

use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\References\ReferenceEntryInterface;

class AutoRegionEntry implements ReferenceEntryInterface
{
    /**
     * @link <https://goo.gl/LnRyLS>
     *
     * @var int
     */
    protected $region_code;

    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string[]|null
     */
    protected $short_titles;

    /**
     * @var int[]|null
     */
    protected $auto_codes;

    /**
     * @var string|null
     */
    protected $okato;

    /**
     * @var string|null
     */
    protected $iso_31662;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * Create a new entry instance.
     *
     * @param int           $region_code
     * @param string|null   $title
     * @param string[]|null $short_titles
     * @param int[]|null    $auto_codes
     * @param string|null   $okato
     * @param string|null   $iso_31662
     * @param string|null   $type
     */
    public function __construct(
        int $region_code,
        ?string $title,
        ?array $short_titles,
        ?array $auto_codes,
        ?string $okato,
        ?string $iso_31662,
        ?string $type
    ) {
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
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get region short titles.
     *
     * @return string[]|null
     */
    public function getShortTitles(): ?array
    {
        return $this->short_titles;
    }

    /**
     * Get GIBDD codes.
     *
     * @return int[]|null
     */
    public function getAutoCodes(): ?array
    {
        return $this->auto_codes;
    }

    /**
     * Get OKATO region code.
     *
     * @return null|string
     */
    public function getOkato(): ?string
    {
        return $this->okato;
    }

    /**
     * Get region code (ISO-31662).
     *
     * @return null|string
     */
    public function getIso31662(): ?string
    {
        return $this->iso_31662;
    }

    /**
     * Get type (`республика`, `край`, etc).
     *
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{
     *                code:int,
     *                title:?string,
     *                gibdd:?array,
     *                code_iso_31662:?string,
     *                okato:?string,
     *                short:?array,
     *                type:?string
     *                }
     */
    public function toArray(): array
    {
        return [
            'code'           => $this->region_code,
            'title'          => $this->title,
            'gibdd'          => $this->auto_codes,
            'code_iso_31662' => $this->iso_31662,
            'okato'          => $this->okato,
            'short'          => $this->short_titles,
            'type'           => $this->type,
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
