<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    /**
     * Show order creation form.
     */
    public function create()
    {
        $products = Product::all();

        return view('orders.create', compact('products'));
    }

    /**
     * Store validated order.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Convert comma-separated emails into an array
        |--------------------------------------------------------------------------
        */
        $emails = collect(
            explode(',', $data['emails'])
        )
            ->map(fn ($email) => trim($email))
            ->filter()
            ->values()
            ->toArray();

        Order::create([
            'country' => strtoupper($data['country']),
            'currency' => strtoupper($data['currency']),
            'status' => $data['status'],
            'product_ids' => $data['product_ids'],
            'emails' => $emails,
        ]);

        return redirect()
            ->route('order.create')
            ->with('success', 'Order Created Successfully!');
    }

    /**
     * Display searchable and filterable orders.
     */
    public function index()
    {
        $query = Order::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if (request()->filled('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->where('country', 'like', "%{$search}%")
                    ->orWhere('currency', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        /*
        |--------------------------------------------------------------------------
        | Country Filter
        |--------------------------------------------------------------------------
        */
        if (request()->filled('country')) {
            $query->where(
                'country',
                strtoupper(request('country'))
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Currency Filter
        |--------------------------------------------------------------------------
        */
        if (request()->filled('currency')) {
            $query->where(
                'currency',
                strtoupper(request('currency'))
            );
        }

        $orders = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }
}