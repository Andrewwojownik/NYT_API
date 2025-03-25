<?php

namespace App\Rules;

use Biblys\Isbn\Isbn;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsbnRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Isbn::validateAsIsbn10($value);
            Isbn::validateAsIsbn13($value);
        } catch (\Exception $e) { // Will throw because third hyphen is misplaced
            $fail('The :attribute must valid ISBN10 or ISBN13 code.');
        }
    }
}
