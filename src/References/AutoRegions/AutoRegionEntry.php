<?php

namespace AvtoDev\StaticReferencesLaravel\References\AutoRegions;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\References\AbstractReferenceEntry;

/**
 * Class AutoRegionEntry.
 *
 * Сущность типа "Регион субъекта".
 */
class AutoRegionEntry extends AbstractReferenceEntry
{
    /**
     * Заголовок региона.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Варианты короткого наименования региона.
     *
     * @var string[]|null
     */
    protected $short_titles;

    /**
     * Код субъекта РФ.
     *
     * @see <https://goo.gl/LnRyLS>
     *
     * @var int|null
     */
    protected $region_code;

    /**
     * Автомобильные коды (коды ГИБДД).
     *
     * @var int[]|null
     */
    protected $auto_codes;

    /**
     * Код региона по ОКАТО.
     *
     * @var string|null
     */
    protected $okato;

    /**
     * Код региона по стандарту ISO-31662.
     *
     * @var string|null
     */
    protected $iso_31662;

    /**
     * Тип (республика/край/и т.д.).
     *
     * @var string|null
     */
    protected $type;

    /**
     * {@inheritdoc}
     */
    public function configure($input = [])
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                switch ($key = Str::lower((string) $key)) {
                    // Заголовок региона
                    case 'title':
                        $this->title = (string) trim($value);
                        break;

                    // Варианты короткого наименования региона
                    case 'short':
                        $value = ! is_array($value)
                            ? explode(',', (string) $value)
                            : $value;
                        $this->short_titles = array_filter(array_map(function ($item) {
                            return trim($item);
                        }, (array) $value));
                        break;

                    // Код субъекта РФ
                    case 'code':
                        $this->region_code = (int) preg_replace('~[^0-9]~', '', (string) $value);
                        break;

                    // Автомобильные коды (коды ГИБДД)
                    case 'gibdd':
                    case 'gibddcode':
                    case 'gibdd_code':
                    case 'auto_code':
                    case 'autocode':
                        $value = ! is_array($value)
                            ? explode(',', (string) $value)
                            : $value;
                        $this->auto_codes = array_filter(array_map(function ($item) {
                            return (int) preg_replace('~[^0-9]~', '', (string) $item);
                        }, (array) $value));
                        break;

                    // Код региона по ОКАТО
                    case 'okato':
                        $this->okato = preg_replace('~[^0-9-]~', '', (string) $value);
                        break;

                    // Код региона по стандарту ISO-31662
                    case 'code_iso_31662':
                    case 'codeiso31662':
                    case 'iso_31662':
                    case 'iso31662':
                        $this->iso_31662 = Str::upper(
                            preg_replace('~[^a-z-]~i', '', (string) $value)
                        );
                        break;

                    // Тип (республика/край/и т.д.)
                    case 'type':
                        $this->type = (string) trim((string) $value);
                        break;
                }
            }
        }
    }

    /**
     * Возвращает код субъекта РФ.
     *
     * @return int|null
     */
    public function getRegionCode()
    {
        return $this->region_code;
    }

    /**
     * Возвращает автомобильные коды (коды ГИБДД).
     *
     * @return int[]|null
     */
    public function getAutoCodes()
    {
        return $this->auto_codes;
    }

    /**
     * Возвращает код региона по стандарту ISO-31662.
     *
     * @return null|string
     */
    public function getIso31662()
    {
        return $this->iso_31662;
    }

    /**
     * Возвращает код региона по ОКАТО.
     *
     * @return null|string
     */
    public function getOkato()
    {
        return $this->okato;
    }

    /**
     * Возвращает варианты короткого наименования региона.
     *
     * @return string[]|null
     */
    public function getShortTitles()
    {
        return $this->short_titles;
    }

    /**
     * Возвращает заголовок региона.
     *
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Возвращает тип (республика/край/и т.д.).
     *
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }
}
