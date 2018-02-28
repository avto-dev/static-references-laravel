<?php

namespace AvtoDev\StaticReferences\References\RegistrationActions;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\AbstractReference;

/**
 * Class RegistrationActionsProvider.
 *
 * Справочник "Регистрационные действия".
 */
class RegistrationActions extends AbstractReference
{
    /**
     * {@inheritdoc}
     *
     * @var RegistrationActionEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance()
    {
        static $instance;

        return is_null($instance)
            ? $instance = StaticReferencesData::getRegistrationActions()
            : $instance;
    }

    /**
     * Получаем объект регистрационного действия по его коду.
     *
     * @param string|int $reg_action_code
     *
     * @return RegistrationActionEntry|null
     */
    public function getByCode($reg_action_code)
    {
        if (is_int($reg_action_code) || is_string($reg_action_code)) {
            // Очищаем входящее значение и приводим к числу
            $reg_action_code = (int) preg_replace('~[^0-9]~', '', $reg_action_code);
            foreach ($this->items as $reg_action) {
                if (in_array($reg_action_code, $reg_action->getCodes())) {
                    return $reg_action;
                }
            }
        }
    }

    /**
     * Проверяет наличие объекта регистрационного действия по его коду.
     *
     * @param string|int $reg_action_code
     *
     * @return bool
     */
    public function hasCode($reg_action_code)
    {
        return $this->getByCode($reg_action_code) instanceof RegistrationActionEntry;
    }

    /**
     * Возвращает объект регистрационного действия по его описанию. Поиск НЕ СТРОГИЙ - по наличию подстроки.
     *
     * @param string $description
     *
     * @return RegistrationActionEntry|null
     */
    public function getByDescription($description)
    {
        if (is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $registration_action) {
                if (Str::contains(Str::lower($registration_action->getDescription()), $description)) {
                    return $registration_action;
                }
            }
        }
    }

    /**
     * Проверяет наличие объекта регистрационного действия по его описанию.
     *
     * @param string $description
     *
     * @return bool
     */
    public function hasDescription($description)
    {
        return $this->getByDescription($description) instanceof RegistrationActionEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName()
    {
        return RegistrationActionEntry::class;
    }
}
