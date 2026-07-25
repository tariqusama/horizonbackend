<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    // GET /api/admin/revenue
    public function getRevenueData()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $currentMonthRevenue = Purchase::whereBetween('created_at', [$startOfMonth, $now])->sum('amount');
        $lastMonthRevenue = Purchase::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('amount');
        
        $revenueGrowth = 0;
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        $activeSubscriptions = Purchase::where('status', 'Completed')->count();

        // Revenue by Service (for chart)
        $revenueByService = Purchase::select('service_id', DB::raw('SUM(amount) as total'))
            ->where('status', 'Completed')
            ->groupBy('service_id')
            ->with('service')
            ->get()
            ->map(function ($item) {
                return [
                    'service' => $item->service ? $item->service->name : 'Unknown',
                    'revenue' => (float) $item->total,
                ];
            });

        // Revenue by Tier (for chart)
        $revenueByTier = Purchase::select('services.tier', DB::raw('SUM(purchases.amount) as total'))
            ->join('services', 'purchases.service_id', '=', 'services.id')
            ->where('purchases.status', 'Completed')
            ->groupBy('services.tier')
            ->get()
            ->map(function ($item) {
                return [
                    'tier' => $item->tier,
                    'revenue' => (float) $item->total,
                ];
            });

        // Monthly Revenue (Last 6 Months for the chart)
        $monthlyRevenue = collect(range(0, 5))->map(function ($i) {
            $date = Carbon::now()->subMonths(5 - $i);
            $sum = Purchase::whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month)
                           ->where('status', 'Completed')
                           ->sum('amount');
            return [
                'month' => $date->format('M'),
                'value' => (float) $sum
            ];
        });

        // Recent Transactions (Last 10)
        $recentTransactions = Purchase::with('service')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($tx) {
                return [
                    'id' => 'TRX-' . $tx->id,
                    'title' => $tx->service ? $tx->service->name : 'Custom Service',
                    'plan' => $tx->service ? $tx->service->tier : 'Standard',
                    'date' => $tx->created_at->format('M j, Y'),
                    'amount' => '$' . number_format($tx->amount, 2),
                    'status' => $tx->status
                ];
            });

        // Conversion Funnel
        $applicationsCreated = \App\Models\Application::count();
        $paymentsCompleted = Purchase::where('status', 'Completed')->count();
        $pendingPayments = Purchase::where('status', 'Pending')->count();
        $conversionRate = $applicationsCreated > 0 ? round(($paymentsCompleted / $applicationsCreated) * 100, 1) : 0;

        return response()->json([
            'stats' => [
                'total_revenue' => $currentMonthRevenue,
                'revenue_growth' => round($revenueGrowth, 1),
                'active_subscriptions' => $activeSubscriptions,
            ],
            'by_service' => $revenueByService,
            'by_tier' => $revenueByTier,
            'monthly_revenue' => $monthlyRevenue,
            'recent_transactions' => $recentTransactions,
            'funnel_stats' => [
                'applications_created' => $applicationsCreated,
                'payments_completed' => $paymentsCompleted,
                'pending_payments' => $pendingPayments,
                'conversion_rate' => $conversionRate
            ]
        ]);
    }
}
