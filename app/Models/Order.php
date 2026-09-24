<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'country',
        'currency',
        'status',
        'product_ids',
        'emails',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'emails' => 'array',
    ];
}