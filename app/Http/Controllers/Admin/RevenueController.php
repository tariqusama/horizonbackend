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

        $currentMonthRevenue = \App\Models\Application::whereBetween('created_at', [$startOfMonth, $now])->sum('paid_amount');
        $lastMonthRevenue = \App\Models\Application::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('paid_amount');
        
        $revenueGrowth = 0;
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        $activeSubscriptions = \App\Models\Application::where('paid_amount', '>', 0)->count();

        // Revenue by Service (for chart)
        $revenueByService = \App\Models\Application::select('title as service', DB::raw('SUM(paid_amount) as total'))
            ->where('paid_amount', '>', 0)
            ->groupBy('title')
            ->get()
            ->map(function ($item) {
                return [
                    'service' => $item->service ?: 'Unknown',
                    'revenue' => (float) $item->total,
                ];
            });

        // Revenue by Tier (for chart)
        $revenueByTier = \App\Models\Application::select('package_name as tier', DB::raw('SUM(paid_amount) as total'))
            ->where('paid_amount', '>', 0)
            ->groupBy('package_name')
            ->get()
            ->map(function ($item) {
                return [
                    'tier' => $item->tier ?: 'Standard',
                    'revenue' => (float) $item->total,
                ];
            });

        // Monthly Revenue (Last 6 Months for the chart)
        $monthlyRevenue = collect(range(0, 5))->map(function ($i) {
            $date = Carbon::now()->subMonths(5 - $i);
            $sum = \App\Models\Application::whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month)
                           ->where('paid_amount', '>', 0)
                           ->sum('paid_amount');
            return [
                'month' => $date->format('M'),
                'value' => (float) $sum
            ];
        });

        // Recent Transactions (Last 10)
        $recentTransactions = \App\Models\Application::orderBy('created_at', 'desc')
            ->where('paid_amount', '>', 0)
            ->take(10)
            ->get()
            ->map(function ($tx) {
                return [
                    'id' => 'TRX-' . $tx->id,
                    'title' => $tx->title ?: 'Custom Service',
                    'plan' => $tx->package_name ?: 'Standard',
                    'date' => $tx->created_at->format('M j, Y'),
                    'amount' => '$' . number_format($tx->paid_amount, 2),
                    'status' => 'Completed'
                ];
            });

        // Conversion Funnel
        $applicationsCreated = \App\Models\Application::count();
        $paymentsCompleted = \App\Models\Application::where('paid_amount', '>', 0)->count();
        $pendingPayments = \App\Models\Application::where(function($q) {
            $q->whereNull('paid_amount')->orWhere('paid_amount', '<=', 0);
        })->count();
        $conversionRate = $applicationsCreated > 0 ? round(($paymentsCompleted / $applicationsCreated) * 100, 1) : 0;

        // Performance Leaderboard
        $managers = \App\Models\User::whereIn('role', ['manager', 'admin'])->get();
        $leaderboard = $managers->map(function ($manager) {
            $completed = \App\Models\Application::where('manager_id', $manager->id)->where('progress', 'Completed')->count();
            $active = \App\Models\Application::where('manager_id', $manager->id)->where('progress', '!=', 'Completed')->count();
            
            // Calculate avg completion time (in days)
            $completedApps = \App\Models\Application::where('manager_id', $manager->id)->where('progress', 'Completed')->get();
            $avgDays = 0;
            if ($completedApps->count() > 0) {
                $totalDays = 0;
                foreach ($completedApps as $app) {
                    $totalDays += $app->created_at->diffInDays($app->updated_at);
                }
                $avgDays = round($totalDays / $completedApps->count());
            }

            // Calculate percentage (e.g. win rate or just a placeholder)
            $total = $completed + $active;
            $percent = $total > 0 ? round(($completed / $total) * 100) : 0;

            return [
                'name' => $manager->name,
                'role' => $manager->role === 'admin' ? 'immigration attorney' : 'case manager',
                'completed' => $completed,
                'active' => $active,
                'avg' => $avgDays . 'd avg',
                'percent' => $percent,
            ];
        })->filter(function ($item) {
            return $item['completed'] > 0 || $item['active'] > 0;
        })->sortByDesc('completed')->values()->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });

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
            ],
            'leaderboard' => $leaderboard
        ]);
    }
}
