<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SanitizeXssRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Validates input string to ensure it does not contain malicious HTML/script tags or SQL injection patterns.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->validateString($attribute, (string) $item, $fail);
            }
            return;
        }

        if (is_string($value)) {
            $this->validateString($attribute, $value, $fail);
        }
    }

    private function validateString(string $attribute, string $input, Closure $fail): void
    {
        $dangerousPatterns = [
            '/<script\b[^>]*>/i',
            '/javascript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/onclick=/i',
            '/eval\(/i',
            '/base64_decode/i',
            '/UNION\s+SELECT/i',
            '/DROP\s+TABLE/i',
            '/INSERT\s+INTO/i',
            '/DELETE\s+FROM/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $fail("The {$attribute} contains unsafe HTML/script tags or forbidden security payload.");
                return;
            }
        }
    }
}
