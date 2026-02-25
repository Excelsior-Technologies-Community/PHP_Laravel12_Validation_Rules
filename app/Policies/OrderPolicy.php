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