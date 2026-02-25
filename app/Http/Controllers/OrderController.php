<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    // ✅ SHOW FORM
    public function create()
    {
        $products = Product::all();

        return view('orders.create', compact('products'));
    }

    // ✅ STORE DATA
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