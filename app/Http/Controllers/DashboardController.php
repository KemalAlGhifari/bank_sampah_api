<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Deposit;
use App\Models\Notification;
use App\Models\Rt;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Mendapatkan summary statistik dashboard
    public function summary()
    {
        $totalUsers = User::count();
        $totalRt = Rt::count();
        $totalDepositKgThisMonth = Deposit::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->sum('total_kg');
        $totalCategories = Category::count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_rt' => $totalRt,
                'total_deposit_kg_this_month' => $totalDepositKgThisMonth,
                'total_categories' => $totalCategories,
            ],
        ]);
    }

    public function userSummary(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();
        $now = Carbon::now();
        $startDate = Carbon::today()->subDays(29);
        $scheduleLimit = (int) $request->input('schedule_limit', 10);

        if ($scheduleLimit < 1) {
            $scheduleLimit = 1;
        }

        if ($scheduleLimit > 50) {
            $scheduleLimit = 50;
        }

        // Total kg user
        $totalKg = $user->total_kg;

        // Upcoming schedules with priority: user -> rt -> all
        $nextSchedules = Schedule::with(['user', 'rt'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->where('type', 'rt')
                            ->where('rt_id', $user->rt_id);
                    })
                    ->orWhere('type', 'all');
            })
            ->where(function ($query) use ($today, $now) {
                $query->whereDate('date', '>', $today)
                    ->orWhere(function ($query) use ($today, $now) {
                        $query->whereDate('date', $today)
                            ->whereTime('time', '>=', $now->format('H:i:s'));
                    });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->orderByRaw(
                "CASE WHEN user_id = ? THEN 1 WHEN type = 'rt' AND rt_id = ? THEN 2 WHEN type = 'all' THEN 3 ELSE 4 END",
                [$user->id, $user->rt_id]
            )
            ->limit($scheduleLimit)
            ->get();

        // Notifications
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $unreadNotificationCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Deposit data terakhir 30 hari, hanya tanggal yang ada transaksi
        $depositRows = Deposit::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_kg) as total_kg'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('COUNT(*) as total_deposits')
            )
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Map langsung tanpa tanggal kosong
        $depositChart = $depositRows->map(function ($row) {
            return [
                'date' => $row->date,
                'total_kg' => (float) $row->total_kg,
                'total_amount' => (float) $row->total_amount,
                'total_deposits' => (int) $row->total_deposits,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'saldo' => (float) $user->saldo,
                    'total_kg' => (float) $totalKg,
                ],
                'next_schedules' => $nextSchedules,
                'notifications' => $notifications,
                'unread_notification_count' => $unreadNotificationCount,
                'deposit_chart_30_days' => $depositChart,
            ],
        ]);
    }
}