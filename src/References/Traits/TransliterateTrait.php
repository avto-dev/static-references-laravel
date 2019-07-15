<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Traits;

use Illuminate\Support\Str;

trait TransliterateTrait
{
    /**
     * Make safe-transliterate + upper-casing.
     *
     * @param string $string
     *
     * @return string
     */
    protected function uppercaseAndSafeTransliterate(string $string): string
    {
        // Kyr-chars set for "safe" transliteration
        static $cyr_chars = [
            'А', 'В', 'Б', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Ф',
            'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ы', 'Э', 'Ю', 'Я',
        ];

        // Latin analogs for set above
        static $latin_chars = [
            'A', 'B', 'B', 'G', 'D', 'E', 'E', 'J', 'Z', 'I', 'I', 'K', 'L', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'F',
            'X', 'C', 'H', 'W', 'W', 'Y', 'E', 'U', 'Y',
        ];

        // Make replaces (like 'я' -> 'ya') and uppercase
        $string = \str_replace($cyr_chars, $latin_chars, Str::upper($string));

        // Make final transliteration
        return Str::ascii($string);
    }
}
