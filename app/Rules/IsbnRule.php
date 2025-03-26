<?php

namespace App\Rules;

use Biblys\Isbn\Isbn;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsbnRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->checkIsbn10($value) && !$this->checkIsbn13($value)) {
            $fail('The :attribute must valid ISBN10 or ISBN13 code.');
        }
    }

    private function checkIsbn10(string $isbn): bool
    {
        try {
            $isbn10 = Isbn::convertToIsbn10($isbn);
            Isbn::validateAsIsbn10($isbn10);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkIsbn13(string $isbn): bool
    {
        try {
            $isbn13 = Isbn::convertToIsbn13($isbn);
            Isbn::validateAsIsbn13($isbn13);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
