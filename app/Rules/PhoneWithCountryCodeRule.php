<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneWithCountryCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Validates phone number with country code (E.164 format).
     * Examples: +919876543210, +12025550123, +447911123456
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || empty(trim($value))) {
            return;
        }

        $cleanPhone = trim($value);

        // International phone E.164 standard regex pattern (+ followed by 7 to 15 digits)
        $phonePattern = '/^\+?[1-9]\d{6,14}$/';

        if (! preg_match($phonePattern, str_replace([' ', '-', '(', ')'], '', $cleanPhone))) {
            $fail("The {$attribute} must be a valid phone number with country code (e.g., +919876543210 or +12025550123).");
        }
    }
}
