<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Spatie\ValidationRules\Rules\CountryCode;
use Spatie\ValidationRules\Rules\Currency;
use Spatie\ValidationRules\Rules\ModelsExist;
use Spatie\ValidationRules\Rules\Delimited;
use Spatie\ValidationRules\Rules\Authorized;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Order;
use App\Models\ValidationFailure;
use App\Enums\OrderStatus;

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
            | ISO Country Code
            |--------------------------------------------------------------------------
            */
            'country' => [
                'required',
                new CountryCode(),
            ],

            /*
            |--------------------------------------------------------------------------
            | ISO Currency Code
            |--------------------------------------------------------------------------
            */
            'currency' => [
                'required',
                new Currency(),
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
     * Record validation failures before redirecting back.
     */
    protected function failedValidation(Validator $validator): void
    {
        ValidationFailure::create([
            'form_type' => 'order',

            'failed_fields' => array_keys(
                $validator->errors()->toArray()
            ),

            'errors' => $validator->errors()->toArray(),

            'input_data' => [
                'country' => $this->input('country'),
                'currency' => $this->input('currency'),
                'status' => $this->input('status'),
                'product_ids' => $this->input('product_ids', []),
                'emails' => $this->input('emails'),
            ],

            'ip_address' => $this->ip(),

            'user_agent' => $this->userAgent(),
        ]);

        throw new HttpResponseException(
            back()
                ->withInput()
                ->withErrors($validator)
        );
    }
}