<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Deposit;
use App\Models\DepositItem;
use App\Models\Category;
use App\Models\User;
use App\Models\Rt;

class ReportController extends Controller
{
    private function nullableDateQuery(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        if ($value === null || $value === '' || strtolower((string) $value) === 'null') {
            return null;
        }

        return $value;
    }

    public function index(Request $request)
    {
        // Ambil filter tanggal dan pagination dari request
        $start = $this->nullableDateQuery($request, 'start');
        $end = $this->nullableDateQuery($request, 'end');
        $page = max(1, (int) $request->query('page', 1));
        $pageSize = max(1, (int) $request->query('page_size', 10));

        $depositQuery = Deposit::query();
        if ($start) {
            $depositQuery->whereDate('created_at', '>=', $start);
        }
        if ($end) {
            $depositQuery->whereDate('created_at', '<=', $end);
        }

        // Summary
        $total_weight = DepositItem::whereIn('deposit_id', $depositQuery->pluck('id'))->sum('weight');
        $total_amount = $depositQuery->sum('total_amount');
        $transaction_count = $depositQuery->count();
        $rt_count = User::whereIn('id', $depositQuery->pluck('user_id'))->distinct('rt_id')->count('rt_id');

        $summary = [
            'total_weight' => (float) $total_weight,
            'total_amount' => (int) $total_amount,
            'transaction_count' => (int) $transaction_count,
            'rt_count' => (int) $rt_count,
        ];

        // Top categories by total weight (filtered by date)
        $depositIds = $depositQuery->pluck('id');
        $top_categories = DepositItem::select('categories.name', DB::raw('SUM(deposit_items.weight) as weight'))
            ->join('categories', 'deposit_items.category_id', '=', 'categories.id')
            ->whereIn('deposit_items.deposit_id', $depositIds)
            ->groupBy('categories.name')
            ->orderByDesc('weight')
            ->limit(5)
            ->get()
            ->map(function($row) {
                return [
                    'name' => $row->name,
                    'weight' => (float) $row->weight,
                ];
            });

        // Reports: paginated
        $depositsPaginated = Deposit::with(['user.rt', 'depositItems.category'])
            ->when($start, function($q) use ($start) { return $q->whereDate('created_at', '>=', $start); })
            ->when($end, function($q) use ($end) { return $q->whereDate('created_at', '<=', $end); })
            ->orderByDesc('created_at')
            ->skip(($page-1)*$pageSize)
            ->take($pageSize)
            ->get();

        $reports = $depositsPaginated->map(function($deposit) {
            $categories = [];
            foreach ($deposit->depositItems as $item) {
                $catName = $item->category->name;
                if (!isset($categories[$catName])) {
                    $categories[$catName] = 0;
                }
                $categories[$catName] += (float) $item->weight;
            }
            return [
                'name' => $deposit->user->name,
                'rt' => $deposit->user && $deposit->user->rt ? $deposit->user->rt->name : null,
                'categories' => $categories,
                'date' => $deposit->created_at->format('Y-m-d'),
                'amount' => (int) $deposit->total_amount,
            ];
        });

        $totalReports = Deposit::when($start, function($q) use ($start) { return $q->whereDate('created_at', '>=', $start); })
            ->when($end, function($q) use ($end) { return $q->whereDate('created_at', '<=', $end); })
            ->count();

        return response()->json([
            'success' => true,
            'meta' => [
                'page' => $page,
                'page_size' => $pageSize,
                'total' => $totalReports,
                'total_pages' => ceil($totalReports/$pageSize),
            ],
            'data' => [
                'summary' => $summary,
                'top_categories' => $top_categories,
                'reports' => $reports,
            ]
        ]);
    }

    public function userReport(Request $request)
    {
        $start = $this->nullableDateQuery($request, 'start');
        $end = $this->nullableDateQuery($request, 'end');
        $page = max(1, (int) $request->query('page', 1));
        $pageSize = max(1, (int) $request->query('page_size', 10));

        $userQuery = User::query();
        $userQuery->with(['rt', 'deposits.depositItems.category']);

        // Filter user yang punya setoran pada periode
        if ($start || $end) {
            $userQuery->whereHas('deposits', function($q) use ($start, $end) {
                if ($start) $q->whereDate('created_at', '>=', $start);
                if ($end) $q->whereDate('created_at', '<=', $end);
            });
        }

        $total = $userQuery->count();
        $users = $userQuery->skip(($page-1)*$pageSize)->take($pageSize)->get();

        $data = $users->map(function($user) use ($start, $end) {
            // Filter deposit by date
            $deposits = $user->deposits();
            if ($start) $deposits->whereDate('created_at', '>=', $start);
            if ($end) $deposits->whereDate('created_at', '<=', $end);
            $deposits = $deposits->with('depositItems.category')->get();

            $total_weight = 0;
            $total_amount = 0;
            $transaction_count = $deposits->count();
            $categories = [];
            foreach ($deposits as $deposit) {
                $total_amount += (int) $deposit->total_amount;
                foreach ($deposit->depositItems as $item) {
                    $total_weight += (float) $item->weight;
                    $catName = $item->category->name;
                    if (!isset($categories[$catName])) $categories[$catName] = 0;
                    $categories[$catName] += (float) $item->weight;
                }
            }
            return [
                'name' => $user->name,
                'rt' => $user->rt ? $user->rt->name : null,
                'total_weight' => $total_weight,
                'total_amount' => $total_amount,
                'transaction_count' => $transaction_count,
                'categories' => $categories,
            ];
        });

        return response()->json([
            'success' => true,
            'meta' => [
                'page' => $page,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => ceil($total/$pageSize),
            ],
            'data' => $data,
        ]);
    }
}
