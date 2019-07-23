<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\CadastralDistricts;

use AvtoDev\StaticReferences\References\AbstractReferenceEntry;

class CadastralDistrictEntry extends AbstractReferenceEntry
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * {@inheritdoc}
     */
    public function configure($input = []): void
    {
        if (\is_array($input)) {
            $this->code = \trim($input['code'] ?? '');
            $this->name = \trim($input['name'] ?? '');
        }
    }

    /**
     * Возвращает номер кадастрового района.
     *
     * @return string
     */
    public function getDistrictCode(): string
    {
        return $this->code;
    }

    /**
     * Возвращает наименование кадастрового района.
     *
     * @return string
     */
    public function getDistrictName(): string
    {
        return $this->name;
    }
}
