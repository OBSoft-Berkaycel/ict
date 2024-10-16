<?php

namespace App\Rules;
namespace App\Rules;

use App\Models\Products;
use Illuminate\Contracts\Validation\Rule;

class UniqueProductName implements Rule
{
    /**
     * Normalize Turkish characters to their ASCII equivalents.
     *
     * @param string $string
     * @return string
     */
    private function normalizeTurkishCharacters($string)
    {
        $turkish = ['Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü', 'ç', 'ğ', 'ı', 'ö', 'ş', 'ü'];
        $ascii   = ['C', 'G', 'I', 'O', 'S', 'U', 'c', 'g', 'i', 'o', 's', 'u'];

        // Convert Turkish characters to ASCII equivalents and make it lowercase
        return strtolower(str_replace($turkish, $ascii, $string));
    }

    /**
     * Validate the product name.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function passes($attribute, $value)
    {
        // Normalize the input value
        $normalizedValue = $this->normalizeTurkishCharacters($value);

        // Check if any existing product name, when normalized, matches the new product name
        $exists = Products::whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, \'Ç\', \'C\'), \'Ğ\', \'G\'), \'İ\', \'I\'), \'Ö\', \'O\'), \'Ş\', \'S\'), \'Ü\', \'U\')) = ?', [$normalizedValue])
            ->orWhereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, \'ç\', \'c\'), \'ğ\', \'g\'), \'ı\', \'i\'), \'ö\', \'o\'), \'ş\', \'s\'), \'ü\', \'u\')) = ?', [$normalizedValue])
            ->exists();

        return !$exists;
    }

    /**
     * Custom error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This product name already exists with a similar variation.';
    }
}
