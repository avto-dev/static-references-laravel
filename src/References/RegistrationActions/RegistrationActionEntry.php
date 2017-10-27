<?php

namespace AvtoDev\StaticReferencesLaravel\References\RegistrationActions;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\References\AbstractReferenceEntry;

/**
 * Class RegistrationActionEntry.
 *
 * Сущность типа "Регистрационное действие".
 */
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
    public function configure($input = [])
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                switch ($key = Str::lower((string) $key)) {
                    // Коды регистрационного действия
                    case 'codes':
                        $value       = ! is_array($value) ? explode(',', (string) $value) : $value;
                        $this->codes = array_filter(array_map(function ($item) {
                            return intval($item);
                        }, (array) $value));
                        break;

                    // Описание регистрационного действия
                    case 'description':
                        $this->description = (string) trim((string) $value);
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Возвращает коды регистрационного действия.
     *
     * @return int[]
     */
    public function getCodes()
    {
        return $this->codes;
    }
}
