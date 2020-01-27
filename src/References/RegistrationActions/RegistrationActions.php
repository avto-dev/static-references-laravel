<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\RegistrationActions;

use AvtoDev\StaticReferences\References\AbstractReference;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use Illuminate\Support\Str;

class RegistrationActions extends AbstractReference
{
    /**
     * @var RegistrationActionEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance(): StaticReferenceInterface
    {
        static $instance;

        return $instance ?? $instance = StaticReferencesData::getRegistrationActions();
    }

    /**
     * Получаем объект регистрационного действия по его коду.
     *
     * @param string|int|mixed $reg_action_code
     *
     * @return RegistrationActionEntry|null
     */
    public function getByCode($reg_action_code): ?RegistrationActionEntry
    {
        if (\is_int($reg_action_code) || \is_string($reg_action_code)) {
            // Очищаем входящее значение и приводим к числу
            $reg_action_code = (int) \preg_replace('~\D~', '', (string) $reg_action_code);
            foreach ($this->items as $reg_action) {
                if (\in_array($reg_action_code, $reg_action->getCodes(), true)) {
                    return $reg_action;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие объекта регистрационного действия по его коду.
     *
     * @param string|int $reg_action_code
     *
     * @return bool
     */
    public function hasCode($reg_action_code): bool
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
    public function getByDescription($description): ?RegistrationActionEntry
    {
        if (\is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $registration_action) {
                if (Str::contains(Str::lower($registration_action->getDescription()), $description)) {
                    return $registration_action;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие объекта регистрационного действия по его описанию.
     *
     * @param string $description
     *
     * @return bool
     */
    public function hasDescription($description): bool
    {
        return $this->getByDescription($description) instanceof RegistrationActionEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName(): string
    {
        return RegistrationActionEntry::class;
    }
}
