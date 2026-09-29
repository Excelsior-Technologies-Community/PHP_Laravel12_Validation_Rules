<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GstinRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Validates GSTIN / Tax Identification Number.
     * Format: 2 digits (State code) + 10 chars (PAN) + 1 entity code + 1 Z + 1 checksum
     * Example: 29ABCDE1234F1Z5 or international format
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || empty(trim($value))) {
            return;
        }

        $cleanValue = strtoupper(trim($value));

        // GSTIN regex pattern (15 characters)
        $gstinPattern = '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/';

        // Fallback Tax ID pattern (Alphanumeric 10-15 uppercase chars)
        $taxIdPattern = '/^[A-Z0-9]{10,15}$/';

        if (! preg_match($gstinPattern, $cleanValue) && ! preg_match($taxIdPattern, $cleanValue)) {
            $fail("The {$attribute} must be a valid 15-character GSTIN / Tax Identification Number (e.g., 29AAAAA0000A1Z5).");
        }
    }
}
