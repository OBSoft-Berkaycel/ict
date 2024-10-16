<?php
namespace App\Rules;

use App\Models\Products;
use Illuminate\Contracts\Validation\Rule;

class UniqueProductNameForUpdate implements Rule
{
    protected $productId;

    /**
     * Constructor to accept the current product ID.
     *
     * @param int $productId
     */
    public function __construct($productId)
    {
        $this->productId = $productId;
    }

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

        return strtolower(str_replace($turkish, $ascii, $string));
    }

    /**
     * Validate if the product name is unique during an update, considering Turkish character variations.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $normalizedValue = $this->normalizeTurkishCharacters($value);
        $currentProduct = Products::find($this->productId);

        if (!$currentProduct) {
            return false;
        }

        $currentProductName = $this->normalizeTurkishCharacters($currentProduct->name);

        if ($currentProductName === $normalizedValue) {
            return true;
        }

        $exists = Products::where('id', '!=', $this->productId)
            ->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, \'Ç\', \'C\'), \'Ğ\', \'G\'), \'İ\', \'I\'), \'Ö\', \'O\'), \'Ş\', \'S\'), \'Ü\', \'U\')) = ?', [$normalizedValue])
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
        return 'There is a product exists with that name. Please try another product name!';
    }
}
