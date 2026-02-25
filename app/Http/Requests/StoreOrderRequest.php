<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\ValidationRules\Rules\CountryCode;
use Spatie\ValidationRules\Rules\Currency;
use Spatie\ValidationRules\Rules\ModelsExist;
use Spatie\ValidationRules\Rules\Delimited;
use Spatie\ValidationRules\Rules\Authorized;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Order;
use App\Enums\OrderStatus;

class StoreOrderRequest extends FormRequest
{
    // Allow request authorization
    public function authorize(): bool
    {
        return true;
    }

    // Validation rules for order form
    public function rules(): array
    {
        return [

            // Validate ISO country code (example: IN)
            'country' => ['required', new CountryCode()],

            // Validate ISO currency code (example: INR)
            'currency' => ['required', new Currency()],

            // Validate status using Enum values
            'status' => [
                'required',
                Rule::enum(OrderStatus::class)
            ],

            // Validate selected product IDs exist in products table
            'product_ids' => [
                'required',
                'array',
                new ModelsExist(Product::class),
            ],

            // Validate comma separated emails
            'emails' => [
                'required',
                new Delimited('email')
            ],

            // Check if user is authorized to update order (policy check)
            'order_id' => [
                'nullable',
                new Authorized('update', Order::class)
            ],
        ];
    }
}