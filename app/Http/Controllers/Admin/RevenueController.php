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

        return response()->json([
            'stats' => [
                'total_revenue' => $currentMonthRevenue,
                'revenue_growth' => round($revenueGrowth, 1),
                'active_subscriptions' => $activeSubscriptions,
            ],
            'by_service' => $revenueByService,
            'by_tier' => $revenueByTier,
        ]);
    }
}
