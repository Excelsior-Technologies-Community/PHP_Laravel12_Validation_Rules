<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * Store or update validated order.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Convert comma-separated emails into array
        |--------------------------------------------------------------------------
        */

        $emails = collect(
            explode(',', $data['emails'])
        )
            ->map(fn ($email) => trim($email))
            ->filter()
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Update existing order
        |--------------------------------------------------------------------------
        */

        if (!empty($data['order_id'])) {

            $order = Order::findOrFail($data['order_id']);

            $order->update([
                'country' => strtoupper($data['country']),
                'currency' => strtoupper($data['currency']),
                'status' => $data['status'],
                'product_ids' => $data['product_ids'],
                'emails' => $emails,
            ]);

            return redirect()
                ->route('orders.index')
                ->with('success', 'Order updated successfully!');
        }

        /*
        |--------------------------------------------------------------------------
        | Create new order
        |--------------------------------------------------------------------------
        */

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
     * Order management dashboard.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('country', 'like', "%{$search}%")
                    ->orWhere('currency', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Country Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('country')) {

            $query->where(
                'country',
                strtoupper($request->country)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Currency Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('currency')) {

            $query->where(
                'currency',
                strtoupper($request->currency)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        |
        | DEFAULT:
        | Order ID ASC
        |
        | This means:
        | #1
        | #2
        | #3
        | #4
        | #5
        | ...
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'country',
            'currency',
            'status',
            'created_at',
        ];

        $sort = in_array(
            $request->get('sort'),
            $allowedSorts
        )
            ? $request->get('sort')
            : 'id';

        $direction = $request->get('direction') === 'desc'
            ? 'desc'
            : 'asc';

        $query->orderBy($sort, $direction);

        /*
        |--------------------------------------------------------------------------
        | Per Page
        |--------------------------------------------------------------------------
        */

        $allowedPerPage = [
            5,
            10,
            25,
            50,
        ];

        $perPage = in_array(
            (int) $request->get('per_page'),
            $allowedPerPage
        )
            ? (int) $request->get('per_page')
            : 5;

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $orders = $query
            ->paginate($perPage)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalOrders = Order::count();

        $pendingOrders = Order::where(
            'status',
            'pending'
        )->count();

        $processingOrders = Order::where(
            'status',
            'processing'
        )->count();

        $deliveredOrders = Order::where(
            'status',
            'delivered'
        )->count();

        $todayOrders = Order::whereDate(
            'created_at',
            today()
        )->count();

        return view(
            'orders.index',
            compact(
                'orders',
                'totalOrders',
                'pendingOrders',
                'processingOrders',
                'deliveredOrders',
                'todayOrders'
            )
        );
    }

    /**
     * Show order details.
     */
    public function show(Order $order)
    {
        $products = Product::whereIn(
            'id',
            $order->product_ids ?? []
        )->get();

        return view(
            'orders.show',
            compact(
                'order',
                'products'
            )
        );
    }

    /**
     * Edit order.
     */
    public function edit(Order $order)
    {
        $products = Product::all();

        return view(
            'orders.edit',
            compact(
                'order',
                'products'
            )
        );
    }

    /**
     * Delete one order.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with(
                'success',
                'Order deleted successfully!'
            );
    }

    /**
     * Bulk delete orders.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'order_ids' => [
                'required',
                'array',
            ],

            'order_ids.*' => [
                'integer',
                'exists:orders,id',
            ],
        ]);

        Order::whereIn(
            'id',
            $request->order_ids
        )->delete();

        return redirect()
            ->route('orders.index')
            ->with(
                'success',
                count($request->order_ids)
                . ' order(s) deleted successfully!'
            );
    }

    /**
     * Duplicate an order.
     */
    public function duplicate(Order $order)
    {
        Order::create([
            'country' => $order->country,
            'currency' => $order->currency,
            'status' => 'pending',
            'product_ids' => $order->product_ids,
            'emails' => $order->emails,
        ]);

        return redirect()
            ->route('orders.index')
            ->with(
                'success',
                'Order duplicated successfully!'
            );
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Order::query();

        /*
        |--------------------------------------------------------------------------
        | Apply same filters as Orders page
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('country', 'like', "%{$search}%")
                    ->orWhere('currency', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('country')) {

            $query->where(
                'country',
                strtoupper($request->country)
            );
        }

        if ($request->filled('currency')) {

            $query->where(
                'currency',
                strtoupper($request->currency)
            );
        }

        if ($request->filled('date_from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CSV Sorting
        |--------------------------------------------------------------------------
        |
        | Export in Order ID ASC by default.
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'country',
            'currency',
            'status',
            'created_at',
        ];

        $sort = in_array(
            $request->get('sort'),
            $allowedSorts
        )
            ? $request->get('sort')
            : 'id';

        $direction = $request->get('direction') === 'desc'
            ? 'desc'
            : 'asc';

        $orders = $query
            ->orderBy($sort, $direction)
            ->get();

        return response()->streamDownload(
            function () use ($orders) {

                $file = fopen(
                    'php://output',
                    'w'
                );

                fputcsv(
                    $file,
                    [
                        'ID',
                        'Country',
                        'Currency',
                        'Status',
                        'Products',
                        'Emails',
                        'Created At',
                    ]
                );

                foreach ($orders as $order) {

                    fputcsv(
                        $file,
                        [
                            $order->id,
                            $order->country,
                            $order->currency,
                            $order->status,
                            count(
                                $order->product_ids ?? []
                            ),
                            implode(
                                ', ',
                                $order->emails ?? []
                            ),
                            $order->created_at,
                        ]
                    );
                }

                fclose($file);
            },
            'orders-' . now()->format(
                'Y-m-d-H-i-s'
            ) . '.csv',
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }
}