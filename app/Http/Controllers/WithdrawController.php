<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;




class WithdrawController extends Controller
{
    public function running(Request $request)
    {
        $pageSize = max(1, (int) $request->query('page_size', 10));

        $withdraws = Withdraw::with('user')
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->latest()
            ->paginate($pageSize);

        return response()->json([
            'success' => true,
            'data' => $withdraws,
        ]);
    }

    public function cancel($id)
    {
        $withdraw = DB::transaction(function () use ($id) {
            $withdraw = Withdraw::lockForUpdate()->find($id);

            if (! $withdraw) {
                abort(response()->json(['message' => 'Withdraw not found'], 404));
            }

            if ($withdraw->user_id !== request()->user()->id) {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk membatalkan withdraw ini',
                ], 403));
            }

            if ($withdraw->status !== 'pending') {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Withdraw sudah diproses',
                ], 400));
            }

            $user = User::lockForUpdate()->findOrFail($withdraw->user_id);
            $user->increment('saldo', $withdraw->amount);

            $withdraw->update(['status' => 'rejected']);

            return $withdraw->refresh()->load('user');
        });

        return response()->json([
            'success' => true,
            'message' => 'Withdraw canceled successfully',
            'withdraw' => $withdraw,
        ]);
    }

    public function adminSummary(Request $request)
    {
        $pageSize = max(1, (int) $request->query('page_size', 10));
        $status = $request->query('status');
        $userId = $request->query('user_id');

        $counts = [
            'pending' => Withdraw::where('status', 'pending')->count(),
            'approved' => Withdraw::where('status', 'approved')->count(),
            'rejected' => Withdraw::where('status', 'rejected')->count(),
        ];

        $withdraws = Withdraw::with(['user.rt'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate($pageSize);

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'pending_count' => $counts['pending'],
                    'approved_count' => $counts['approved'],
                    'rejected_count' => $counts['rejected'],
                ],
                'withdraws' => $withdraws,
            ],
        ]);
    }

    public function index(Request $request)
    {
        $pageSize = $request->query('page_size', 10);
        $status = $request->query('status');
        $userId = $request->query('user_id');

        $withdraws = Withdraw::with('user')
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate($pageSize);

        return response()->json($withdraws);
    }

    public function show($id)
    {
        $withdraw = Withdraw::with('user')->find($id);

        if (! $withdraw) {
            return response()->json(['message' => 'Withdraw not found'], 404);
        }

        return response()->json($withdraw);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        $userId = $request->user()->id;

        $withdraw = DB::transaction(function () use ($request, $userId) {
            $user = User::lockForUpdate()->findOrFail($userId);
            $pendingAmount = Withdraw::where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount');
            $availableSaldo = (float) $user->saldo - (float) $pendingAmount;
            $amount = (float) $request->amount;

            if ($amount > $availableSaldo) {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi',
                    'available_saldo' => $availableSaldo,
                ], 400));
            }

            $user->decrement('saldo', $amount);

            return Withdraw::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'pending',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Withdraw request created successfully',
            'withdraw' => $withdraw->load('user'),
        ], 201);
    }

    public function approve($id)
    {
        $withdraw = DB::transaction(function () use ($id) {
            $withdraw = Withdraw::lockForUpdate()->find($id);

            if (! $withdraw) {
                abort(response()->json(['message' => 'Withdraw not found'], 404));
            }

            if ($withdraw->status !== 'pending') {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Withdraw sudah diproses',
                ], 400));
            }

            $user = User::lockForUpdate()->findOrFail($withdraw->user_id);

            if ((float) $withdraw->amount > (float) $user->saldo) {
                abort(response()->json([
                    'success' => false,
                    'message' => 'Saldo user tidak mencukupi',
                    'saldo' => (float) $user->saldo,
                ], 400));
            }

           
            $withdraw->update(['status' => 'approved']);

            return $withdraw->refresh()->load('user');
        });

        return response()->json([
            'success' => true,
            'message' => 'Withdraw approved successfully',
            'withdraw' => $withdraw,
        ]);
    }

    public function reject($id)
    {
        $withdraw = Withdraw::find($id);

        if (! $withdraw) {
            return response()->json(['message' => 'Withdraw not found'], 404);
        }

        if ($withdraw->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Withdraw sudah diproses',
            ], 400);
        }

        $user = User::find($withdraw->user_id);

        $user->increment('saldo', $withdraw->amount);

        $withdraw->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Withdraw rejected successfully',
            'withdraw' => $withdraw->load('user'),
        ]);
    }
}
