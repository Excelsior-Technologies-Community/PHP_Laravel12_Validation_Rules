#  PHP_Laravel12_Validation_Rules

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![Spatie](https://img.shields.io/badge/Spatie-Validation%20Rules-orange)

---

##  Overview

This project demonstrates how to use **Spatie Laravel Validation Rules** in a **Laravel 12** application. It includes advanced validation such as:

* ISO Country Code validation
* ISO Currency validation
* Enum-based validation
* Model existence validation
* Delimited email validation
* Policy-based authorization validation

A simple **Order Management Form** is used to showcase real-world validation scenarios.

---

##  Features

* Laravel 12 setup
* Spatie validation rules integration
* Enum validation using PHP Enums
* Policy authorization validation
* Seeder-based product setup
* Order form with multiple product selection
* JSON column casting
* Clean UI form

---

##  Folder Structure

```
app/
 ├── Enums/
 │   └── OrderStatus.php
 ├── Http/
 │   ├── Controllers/
 │   │   └── OrderController.php
 │   └── Requests/
 │       └── StoreOrderRequest.php
 ├── Models/
 │   ├── Order.php
 │   └── Product.php
 └── Policies/
     └── OrderPolicy.php

database/
 ├── migrations/
 └── seeders/
     └── ProductSeeder.php

resources/views/orders/
 └── create.blade.php

routes/
 └── web.php
```

---

##  Requirements

* PHP >= 8.2
* Composer
* MySQL
* Laravel 12

---

## STEP 1 — Laravel Project Install

Terminal me:

```bash
composer create-project laravel/laravel spatie-demo
php artisan serve
```

---

## STEP 2 — Package Install

```bash
composer require spatie/laravel-validation-rules
composer require league/iso3166
```

---

## STEP 3 — Database Setup

`.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## STEP 4 — Required Files Create

```bash
php artisan make:model Product –m
php artisan make:model Order –m
php artisan make:controller OrderController
php artisan make:request StoreOrderRequest
php artisan make:policy OrderPolicy --model=Order
php artisan make:seeder ProductSeeder
```

---

## STEP 5 — Migration Code

### database/migrations/create_products_table.php

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price',8,2);
        $table->timestamps();
    });
}
```

### database/migrations/create_orders_table.php

```php
public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('country');
        $table->string('currency');
        $table->enum('status',['pending','processing','delivered']);
        $table->json('product_ids');
        $table->timestamps();
    });
}
```

Run:

```bash
php artisan migrate
```

---

## STEP 6 — Seeder

### database/seeders/ProductSeeder.php

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create(['name'=>'Laptop','price'=>50000]);
        Product::create(['name'=>'Mobile','price'=>20000]);
        Product::create(['name'=>'Keyboard','price'=>1500]);
    }
}
```

Run:

```bash
php artisan db:seed --class=ProductSeeder
```

---

## STEP 7 — Models

### app/Models/Product.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','price'];
}
```

### app/Models/Order.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'country',
        'currency',
        'status',
        'product_ids'
    ];

    protected $casts = [
        'product_ids' => 'array'
    ];
}
```

---

## STEP 8 — Enum Create

Create Folder :

```
app/Enums
```

### app/Enums/OrderStatus.php

```php
<?php

namespace App\Enums;

enum OrderStatus:string
{
    case PENDING='pending';
    case PROCESSING='processing';
    case DELIVERED='delivered';

    public function label(): string
    {
        return match($this){
            self::PENDING=>'Pending',
            self::PROCESSING=>'Processing',
            self::DELIVERED=>'Delivered',
        };
    }
}
```

---

## STEP 9 — OrderPolicy

### app/Policies/OrderPolicy.php

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function update(User $user, Order $order): bool
    {
        return true;
    }
}
```

---

## STEP 10 — Policy Register (Laravel 12)

### app/Providers/AppServiceProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Policies\OrderPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);
    }
}
```

---

## STEP 11 — Validation Request

### app/Http/Requests/StoreOrderRequest.php

```php
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
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'country' => ['required', new CountryCode()],
            'currency' => ['required', new Currency()],
            'status' => [
                'required',
                Rule::enum(OrderStatus::class)
            ],
            'product_ids' => [
                'required',
                'array',
                new ModelsExist(Product::class),
            ],
            'emails' => [
                'required',
                new Delimited('email')
            ],
            'order_id' => [
                'nullable',
                new Authorized('update', Order::class)
            ],
        ];
    }
}
```

---

## STEP 12 — Controller

### app/Http/Controllers/OrderController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        Order::create([
            'country' => $data['country'],
            'currency' => $data['currency'],
            'status' => $data['status'],
            'product_ids' => $data['product_ids'],
        ]);

        return redirect()->back()->with('success', 'Order Created Successfully!');
    }
}
```

---

## STEP 13 — Routes

### routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/order/create', [OrderController::class,'create']);

Route::post('/order/store', [OrderController::class,'store'])
    ->name('order.store');
```

---

## STEP 14 — View

Create:

```
resources/views/orders/create.blade.php
```

```html
<!DOCTYPE html>
<html>
<head>
    <title>Create Order</title>

    <style>

    * {
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, sans-serif;
    }

    body {
        background: linear-gradient(135deg,#eef2f7,#dfe6ee);
        margin: 0;
        padding: 40px;
    }

    .container {
        width: 520px;
        margin: auto;
        background: #ffffff;
        padding: 35px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: 0.3s;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
        font-weight: 600;
    }

    label {
        font-weight: 600;
        color: #34495e;
        margin-top: 18px;
        display: block;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 12px;
        margin-top: 6px;
        border-radius: 8px;
        border: 1px solid #dcdfe6;
        background: #fafafa;
        transition: all 0.25s ease;
        font-size: 14px;
    }

    input:focus,
    select:focus {
        border-color: #4CAF50;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(76,175,80,0.15);
        outline: none;
    }

    .products {
        margin-top: 8px;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e1e5eb;
        background: #fafbfc;
    }

    .products label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        margin-bottom: 8px;
        cursor: pointer;
    }

    input[type="checkbox"] {
        transform: scale(1.2);
        cursor: pointer;
    }

    button {
        width: 100%;
        margin-top: 25px;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg,#4CAF50,#43a047);
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(76,175,80,0.3);
    }

    .success {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .errors {
        background: #ffebee;
        color: #c62828;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    ul {
        margin: 0;
        padding-left: 20px;
    }

    @media(max-width:600px){
        .container{
            width:100%;
            padding:25px;
        }
    }

</style>
</head>

<body>

<div class="container">

<h2>Create Order</h2>

@if(session('success'))
<div class="success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="errors">
<ul>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST" action="{{ route('order.store') }}">
@csrf

<label>Country Code</label>
<input type="text" name="country" value="{{ old('country') }}" placeholder="IN">

<label>Currency</label>
<input type="text" name="currency" value="{{ old('currency') }}" placeholder="INR">

<label>Status</label>
<select name="status">
    <option value="">Select Status</option>
    <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>Pending</option>
    <option value="processing" {{ old('status')=='processing' ? 'selected' : '' }}>Processing</option>
    <option value="delivered" {{ old('status')=='delivered' ? 'selected' : '' }}>Delivered</option>
</select>

<label>Products</label>
<div class="products">
@foreach($products as $product)
<label>
<input 
    type="checkbox" 
    name="product_ids[]" 
    value="{{ $product->id }}"
    {{ in_array($product->id, old('product_ids', [])) ? 'checked' : '' }}
>
{{ $product->name }}
</label>
@endforeach
</div>

<label>Emails (comma separated)</label>
<input type="text" name="emails" value="{{ old('emails') }}" placeholder="a@gmail.com,b@gmail.com">

<button type="submit">Submit Order</button>

</form>

</div>

</body>
</html>
```

---

## STEP 15 — Run

```bash
php artisan serve
```

Browser:

```
http://127.0.0.1:8000/order/create
```
<img width="526" height="774" alt="Screenshot 2026-02-25 154106" src="https://github.com/user-attachments/assets/caaf4697-2032-4235-921d-f76b01c66159" />

---
<img width="520" height="177" alt="Screenshot 2026-02-25 154237" src="https://github.com/user-attachments/assets/1b8cd947-b184-4a36-a27b-eb474e36f7e4" />

---

## RESULT

* Form submits successfully
* Spatie validation rules run
* Data is saved in database

