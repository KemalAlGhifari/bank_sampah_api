<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    private function nullableDateQuery(Request $request, string $key): ?string
    {
        $value = $request->query($key);

        if ($value === null || $value === '' || strtolower((string) $value) === 'null') {
            return null;
        }

        return $value;
    }

    private function mapDeposit(Deposit $deposit): array
    {
        return [
            'type' => 'deposit',
            'id' => $deposit->id,
            'title' => 'Setoran',
            'amount' => (float) $deposit->total_amount,
            'total_kg' => (float) $deposit->total_kg,
            'status' => $deposit->status,
            'created_at' => $deposit->created_at->toIso8601String(),
            'items' => $deposit->depositItems->map(function ($item) {
                return [
                    'category_id' => $item->category_id,
                    'category_name' => $item->category?->name,
                    'weight' => (float) $item->weight,
                    'subtotal' => (float) $item->subtotal,
                ];
            })->values(),
        ];
    }

    private function mapWithdraw(Withdraw $withdraw): array
    {
        return [
            'type' => 'withdraw',
            'id' => $withdraw->id,
            'title' => 'Penarikan',
            'amount' => (float) $withdraw->amount,
            'status' => $withdraw->status,
            'created_at' => $withdraw->created_at->toIso8601String(),
            'items' => [],
        ];
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $page = max(1, (int) $request->query('page', 1));
        $pageSize = max(1, (int) $request->query('page_size', 10));
        $type = $request->query('type');
        $start = $this->nullableDateQuery($request, 'start');
        $end = $this->nullableDateQuery($request, 'end');

        $depositQuery = Deposit::with(['depositItems.category'])
            ->where('user_id', $user->id)
            ->when($start, function ($query) use ($start) {
                $query->whereDate('created_at', '>=', $start);
            })
            ->when($end, function ($query) use ($end) {
                $query->whereDate('created_at', '<=', $end);
            });

        $withdrawQuery = Withdraw::where('user_id', $user->id)
            ->when($start, function ($query) use ($start) {
                $query->whereDate('created_at', '>=', $start);
            })
            ->when($end, function ($query) use ($end) {
                $query->whereDate('created_at', '<=', $end);
            });

        $deposits = $type === 'withdraw' ? collect() : $depositQuery->latest()->get();
        $withdraws = $type === 'deposit' ? collect() : $withdrawQuery->latest()->get();

        $history = $deposits->map(fn (Deposit $deposit) => $this->mapDeposit($deposit))
            ->merge($withdraws->map(fn (Withdraw $withdraw) => $this->mapWithdraw($withdraw)))
            ->sortByDesc('created_at')
            ->values();

        $totalDepositsAmount = $deposits->sum('total_amount');
        $totalDepositsKg = $deposits->sum('total_kg');
        $totalWithdrawAmount = $withdraws->sum('amount');

        $total = $history->count();
        $offset = ($page - 1) * $pageSize;
        $items = $history->slice($offset, $pageSize)->values();

        return response()->json([
            'success' => true,
            'meta' => [
                'page' => $page,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => (int) ceil($total / $pageSize),
            ],
            'data' => [
                'summary' => [
                    'total_setoran' => (float) $totalDepositsAmount,
                    'total_kg_setoran' => (float) $totalDepositsKg,
                    'total_penarikan' => (float) $totalWithdrawAmount,
                    'saldo_bersih' => (float) ($totalDepositsAmount - $totalWithdrawAmount),
                ],
                'history' => $items,
            ],
        ]);
    }
}