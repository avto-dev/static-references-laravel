<?php

namespace AvtoDev\StaticReferencesLaravel\Traits;

use Illuminate\Support\Str;

/**
 * Trait TransliterateTrait.
 *
 * Трейт, реализующий методы транслитерации.
 */
trait TransliterateTrait
{
    /**
     * Переводит строку в верхний регистр и производит "безопасную" транслитерацию (без опаски что одна буква будет
     * транслитерирована как две).
     *
     * @param string $string
     *
     * @return string
     */
    protected function uppercaseAndSafeTransliterate($string)
    {
        // Кириллические символы, для которых описан массив "мягких" замен $latin_chars.
        static $cyr_chars = [
            'А', 'В', 'Б', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Ф',
            'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ы', 'Э', 'Ю', 'Я',
        ];

        // Латинские аналоги кириллических символов, что описаны в массиве $cyr_chars.
        static $latin_chars = [
            'A', 'B', 'B', 'G', 'D', 'E', 'E', 'J', 'Z', 'I', 'I', 'K', 'L', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'F',
            'X', 'C', 'H', 'W', 'W', 'Y', 'E', 'U', 'Y',
        ];

        // Производим замену латинских символов, которые при дальнейшей транслитерации дают 2 символа на выходе,
        // вместо одного (например 'я' -> 'ya'), и переводим в верхний регистр
        $string = str_replace($cyr_chars, $latin_chars, Str::upper((string) $string));

        // Производим конечную транслитерацию
        return Str::ascii($string);
    }
}
