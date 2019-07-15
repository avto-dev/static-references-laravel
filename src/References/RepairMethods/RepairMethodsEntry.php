<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\RepairMethods;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\AbstractReferenceEntry;

class RepairMethodsEntry extends AbstractReferenceEntry
{
    /**
     * Коды метода ремонта.
     *
     * @var string[]|array
     */
    protected $codes;

    /**
     * Описание метода ремонта.
     *
     * @var string
     */
    protected $description;

    /**
     * {@inheritdoc}
     */
    public function configure($input = []): void
    {
        if (\is_array($input)) {
            foreach ($input as $key => $value) {
                switch ($key = Str::lower((string) $key)) {
                    // Коды регистрационного действия
                    case 'codes':
                        $value       = ! is_array($value)
                            ? explode(',', (string) $value)
                            : $value;
                        $this->codes = \array_filter(\array_map(function ($item) {
                            return (string) $item;
                        }, (array) $value));
                        break;

                    // Описание регистрационного действия
                    case 'description':
                        $this->description = trim((string) $value);
                        break;
                }
            }
        }
    }

    /**
     * Возвращает описание метода ремонта.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Возвращает коды метода ремонта.
     *
     * @return string[]|array
     */
    public function getCodes(): array
    {
        return $this->codes;
    }
}
