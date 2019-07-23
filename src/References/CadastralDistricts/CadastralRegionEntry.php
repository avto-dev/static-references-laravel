<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\CadastralDistricts;

use AvtoDev\StaticReferences\References\AbstractReferenceEntry;

class CadastralRegionEntry extends AbstractReferenceEntry
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
     * @var CadastralDistricts
     */
    protected $districts;

    /**
     * {@inheritdoc}
     */
    public function configure($input = []): void
    {
        if (\is_array($input)) {
            foreach ($input as $key => $value) {
                switch ($key) {
                    case 'code':
                        $this->code = \trim($value);
                        break;
                    case 'name':
                        $this->name = \trim($value);
                        break;
                    case 'districts':
                        $this->districts = new CadastralDistricts((array) $value);
                        break;
                }
            }
        }
    }

    /**
     * Возвращает код субъекта РФ.
     *
     * @return string
     */
    public function getRegionCode(): string
    {
        return $this->code;
    }

    /**
     * Возвращает наименование субъекта РФ.
     *
     * @return string
     */
    public function getRegionName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает список районов субъекта РФ.
     *
     * @return CadastralDistricts
     */
    public function getDistricts(): CadastralDistricts
    {
        return $this->districts;
    }
}
