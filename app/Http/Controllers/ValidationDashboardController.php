<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ValidationFailure;
use Illuminate\Support\Facades\DB;

class ValidationDashboardController extends Controller
{
    /**
     * Validation analytics & Bot anomaly dashboard.
     */
    public function dashboard()
    {
        $totalOrders = Order::count();
        $validationFailures = ValidationFailure::count();
        $successfulValidations = $totalOrders;
        $totalValidationAttempts = $successfulValidations + $validationFailures;

        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $todayOrders = Order::whereDate('created_at', today())->count();
        $weekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $suspiciousFailuresCount = ValidationFailure::where('is_suspicious', true)->count();

        $countryStats = Order::selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get();

        $currencyStats = Order::selectRaw('currency, COUNT(*) as total')
            ->groupBy('currency')
            ->orderByDesc('total')
            ->get();

        $statusStats = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Field Error Breakdown Calculation (Heatmap Analytics)
        |--------------------------------------------------------------------------
        */
        $allFailures = ValidationFailure::all();
        $fieldCounts = [];
        $totalFieldErrors = 0;

        foreach ($allFailures as $failure) {
            if (is_array($failure->failed_fields)) {
                foreach ($failure->failed_fields as $field) {
                    $fieldCounts[$field] = ($fieldCounts[$field] ?? 0) + 1;
                    $totalFieldErrors++;
                }
            }
        }

        arsort($fieldCounts);

        $fieldBreakdown = [];
        foreach ($fieldCounts as $field => $count) {
            $percentage = $totalFieldErrors > 0 ? round(($count / $totalFieldErrors) * 100, 1) : 0;
            $fieldBreakdown[] = [
                'field' => ucfirst(str_replace('_', ' ', $field)),
                'raw_field' => $field,
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Suspicious Bot IP Activity List
        |--------------------------------------------------------------------------
        */
        $suspiciousIps = ValidationFailure::selectRaw('ip_address, COUNT(*) as total_failures')
            ->groupBy('ip_address')
            ->having('total_failures', '>=', 2)
            ->orderByDesc('total_failures')
            ->take(5)
            ->get();

        $recentOrders = Order::latest()->take(5)->get();
        $recentFailures = ValidationFailure::latest()->take(5)->get();

        return view('validation.dashboard', compact(
            'totalOrders',
            'validationFailures',
            'successfulValidations',
            'totalValidationAttempts',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders',
            'todayOrders',
            'weekOrders',
            'suspiciousFailuresCount',
            'countryStats',
            'currencyStats',
            'statusStats',
            'fieldBreakdown',
            'suspiciousIps',
            'recentOrders',
            'recentFailures'
        ));
    }

    /**
     * Validation failure history & suspicious bot logs.
     */
    public function history()
    {
        $query = ValidationFailure::query();

        if (request()->filled('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->whereJsonContains('failed_fields', $search)
                    ->orWhere('form_type', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if (request()->filled('filter') && request('filter') === 'suspicious') {
            $query->where('is_suspicious', true);
        }

        $failures = $query->latest()->paginate(10)->withQueryString();

        return view('validation.history', compact('failures'));
    }
}