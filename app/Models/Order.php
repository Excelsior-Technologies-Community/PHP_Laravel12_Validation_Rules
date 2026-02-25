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