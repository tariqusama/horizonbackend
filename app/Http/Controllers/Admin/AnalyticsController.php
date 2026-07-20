<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;
use App\Models\Purchase;
use App\Models\Ticket;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getDashboardStats(Request $request)
    {
        $usersCount = User::where('role', 'Client')->count();
        $casesCount = Application::whereNotIn('status', ['Approved', 'Rejected'])->count();
        $revenue = Purchase::sum('amount');
        $ticketsCount = Ticket::where('status', 'Open')->count();

        // Generate dummy sparkline data for UI (or we could calculate it historically)
        // Here we just calculate historically by week for the last 7 weeks
        $usersSparkline = $this->generateSparkline(User::class, 'created_at', 7);
        $casesSparkline = $this->generateSparkline(Application::class, 'created_at', 7);
        $revenueSparkline = $this->generateSparkline(Purchase::class, 'created_at', 7, 'amount');
        $ticketsSparkline = $this->generateSparkline(Ticket::class, 'created_at', 7);

        return response()->json([
            'users' => [
                'count' => $usersCount,
                'sparkline' => $usersSparkline
            ],
            'cases' => [
                'count' => $casesCount,
                'sparkline' => $casesSparkline
            ],
            'revenue' => [
                'total' => $revenue,
                'sparkline' => $revenueSparkline
            ],
            'tickets' => [
                'open' => $ticketsCount,
                'sparkline' => $ticketsSparkline
            ]
        ]);
    }

    public function getRecentActivity(Request $request)
    {
        $activities = AuditLog::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user' => $log->user ? $log->user->name : 'System',
                    'action' => $log->action,
                    'target' => $log->target,
                    'details' => $log->details,
                    'time' => $log->created_at->diffForHumans(),
                    'raw_time' => $log->created_at
                ];
            });

        return response()->json($activities);
    }

    public function getAnalyticsData(Request $request)
    {
        // 1. Monthly Revenue (Last 12 Months)
        $monthlyRevenue = collect(range(0, 11))->map(function ($i) {
            $date = Carbon::now()->subMonths(11 - $i);
            $sum = Purchase::whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month)
                           ->sum('amount');
            return [
                'month' => $date->format('M'),
                'revenue' => $sum
            ];
        });

        // 2. Case Distribution by Service Name
        $applications = Application::get();
        $totalCases = $applications->count();
        
        $distribution = $applications->groupBy(function($app) {
            return $app->title ?: 'Other';
        })->map(function($group, $key) use ($totalCases) {
            $count = $group->count();
            return [
                'name' => $key,
                'count' => $count,
                'percent' => $totalCases > 0 ? round(($count / $totalCases) * 100) : 0
            ];
        })->values()->sortByDesc('count');

        // Add colors to distribution
        $colors = ['bg-[#1B3A64]', 'bg-[#E3755D]', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400'];
        $distribution = $distribution->map(function ($item, $idx) use ($colors) {
            $item['color'] = $colors[$idx % count($colors)];
            return $item;
        });

        // 3. Average Processing Times
        $approvedApps = Application::where('status', 'Approved')->get();
        
        $processingTimes = $approvedApps->groupBy(function($app) {
            return $app->title ?: 'Unknown Service';
        })->map(function($group, $name) {
            $totalDays = $group->reduce(function ($carry, $app) {
                return $carry + $app->created_at->diffInDays($app->updated_at);
            }, 0);
            
            $avgDays = $totalDays / $group->count();
            $avgMonths = round($avgDays / 30.44, 1); // rough months
            
            return [
                'service_name' => $name,
                'avg_months' => $avgMonths
            ];
        })->values();

        return response()->json([
            'monthly_revenue' => $monthlyRevenue,
            'case_distribution' => $distribution,
            'processing_times' => $processingTimes
        ]);
    }

    private function generateSparkline($model, $dateField, $points, $sumField = null)
    {
        $data = [];
        for ($i = $points - 1; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek();
            $end = Carbon::now()->subWeeks($i)->endOfWeek();

            $query = $model::whereBetween($dateField, [$start, $end]);
            
            if ($sumField) {
                $data[] = $query->sum($sumField);
            } else {
                $data[] = $query->count();
            }
        }
        
        // ensure sparkline has at least some variation if empty
        if (array_sum($data) == 0) {
            return [0,0,0,0,0,0,0];
        }
        
        return $data;
    }
}
