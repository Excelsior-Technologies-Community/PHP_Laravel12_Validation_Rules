<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\ValidationFailure;
use App\Rules\GstinRule;
use App\Rules\PhoneWithCountryCodeRule;
use App\Rules\SanitizeXssRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Spatie\ValidationRules\Rules\Authorized;
use Spatie\ValidationRules\Rules\CountryCode;
use Spatie\ValidationRules\Rules\Currency;
use Spatie\ValidationRules\Rules\Delimited;
use Spatie\ValidationRules\Rules\ModelsExist;

class StoreOrderRequest extends FormRequest
{
    /**
     * Allow request authorization.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | ISO Country Code + XSS Prevention
            |--------------------------------------------------------------------------
            */
            'country' => [
                'required',
                new CountryCode(),
                new SanitizeXssRule(),
            ],

            /*
            |--------------------------------------------------------------------------
            | ISO Currency Code + XSS Prevention
            |--------------------------------------------------------------------------
            */
            'currency' => [
                'required',
                new Currency(),
                new SanitizeXssRule(),
            ],

            /*
            |--------------------------------------------------------------------------
            | Custom GSTIN / Tax ID Validation Rule
            |--------------------------------------------------------------------------
            */
            'gstin' => [
                'nullable',
                'string',
                new GstinRule(),
                new SanitizeXssRule(),
            ],

            /*
            |--------------------------------------------------------------------------
            | Custom International Phone Validation Rule
            |--------------------------------------------------------------------------
            */
            'phone' => [
                'nullable',
                'string',
                new PhoneWithCountryCodeRule(),
                new SanitizeXssRule(),
            ],

            /*
            |--------------------------------------------------------------------------
            | Enum Validation
            |--------------------------------------------------------------------------
            */
            'status' => [
                'required',
                Rule::enum(OrderStatus::class),
            ],

            /*
            |--------------------------------------------------------------------------
            | Product Existence Validation
            |--------------------------------------------------------------------------
            */
            'product_ids' => [
                'required',
                'array',
                'min:1',
                new ModelsExist(Product::class),
            ],

            /*
            |--------------------------------------------------------------------------
            | Delimited Email Validation
            |--------------------------------------------------------------------------
            */
            'emails' => [
                'required',
                new Delimited('email'),
                new SanitizeXssRule(),
            ],

            /*
            |--------------------------------------------------------------------------
            | Policy Authorization Validation
            |--------------------------------------------------------------------------
            */
            'order_id' => [
                'nullable',
                new Authorized('update', Order::class),
            ],
        ];
    }

    /**
     * Record validation failures & detect Bot/Fraud spam.
     */
    protected function failedValidation(Validator $validator): void
    {
        $ip = $this->ip();

        // Bot Anomaly Detection: check recent failures from this IP in the last 1 hour
        $recentFailuresCount = ValidationFailure::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        $isSuspicious = ($recentFailuresCount >= 2);

        ValidationFailure::create([
            'form_type' => 'order',
            'failed_fields' => array_keys($validator->errors()->toArray()),
            'errors' => $validator->errors()->toArray(),
            'input_data' => [
                'country' => $this->input('country'),
                'currency' => $this->input('currency'),
                'gstin' => $this->input('gstin'),
                'phone' => $this->input('phone'),
                'status' => $this->input('status'),
                'product_ids' => $this->input('product_ids', []),
                'emails' => $this->input('emails'),
                'order_id' => $this->input('order_id'),
            ],
            'ip_address' => $ip,
            'user_agent' => $this->userAgent(),
            'is_suspicious' => $isSuspicious,
        ]);

        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422));
        }

        throw new HttpResponseException(
            back()
                ->withInput()
                ->withErrors($validator)
        );
    }
}