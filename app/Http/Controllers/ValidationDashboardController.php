<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ValidationFailure;

class ValidationDashboardController extends Controller
{
    /**
     * Validation analytics dashboard.
     */
    public function dashboard()
    {
        $totalOrders = Order::count();

        $validationFailures =
            ValidationFailure::count();

        $successfulValidations =
            $totalOrders;

        $totalValidationAttempts =
            $successfulValidations +
            $validationFailures;

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

        $weekOrders = Order::whereBetween(
            'created_at',
            [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]
        )->count();

        $countryStats = Order::selectRaw(
            'country, COUNT(*) as total'
        )
            ->groupBy('country')
            ->orderByDesc('total')
            ->get();

        $currencyStats = Order::selectRaw(
            'currency, COUNT(*) as total'
        )
            ->groupBy('currency')
            ->orderByDesc('total')
            ->get();

        $statusStats = Order::selectRaw(
            'status, COUNT(*) as total'
        )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $recentOrders = Order::oldest()
            ->take(5)
            ->get();

        $recentFailures =
            ValidationFailure::oldest()
                ->take(5)
                ->get();

        return view(
            'validation.dashboard',
            compact(
                'totalOrders',
                'validationFailures',
                'successfulValidations',
                'totalValidationAttempts',
                'pendingOrders',
                'processingOrders',
                'deliveredOrders',
                'todayOrders',
                'weekOrders',
                'countryStats',
                'currencyStats',
                'statusStats',
                'recentOrders',
                'recentFailures'
            )
        );
    }

    /**
     * Validation failure history.
     */
    public function history()
    {
        $query = ValidationFailure::query();

        if (request()->filled('search')) {

            $search = request('search');

            $query->where(function ($q) use ($search) {

                $q->whereJsonContains(
                    'failed_fields',
                    $search
                )
                ->orWhere(
                    'form_type',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'ip_address',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $failures = $query
            ->oldest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'validation.history',
            compact('failures')
        );
    }
}