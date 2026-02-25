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