<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\RegistrationActions;

use AvtoDev\StaticReferences\References\AbstractReferenceEntry;
use Illuminate\Support\Str;

class RegistrationActionEntry extends AbstractReferenceEntry
{
    /**
     * Коды регистрационного действия.
     *
     * @var int[]|array
     */
    protected $codes;

    /**
     * Описание регистрационного действия.
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
                        $value       = ! \is_array($value)
                            ? explode(',', (string) $value)
                            : $value;
                        $this->codes = \array_filter(\array_map('intval', (array) $value));
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
     * Возвращает описание регистрационного действия.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Возвращает коды регистрационного действия.
     *
     * @return int[]
     */
    public function getCodes(): array
    {
        return $this->codes;
    }
}
