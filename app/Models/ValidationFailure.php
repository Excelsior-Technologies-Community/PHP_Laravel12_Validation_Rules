<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidationFailure extends Model
{
    protected $fillable = [
        'form_type',
        'failed_fields',
        'errors',
        'input_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'failed_fields' => 'array',
        'errors' => 'array',
        'input_data' => 'array',
    ];
}