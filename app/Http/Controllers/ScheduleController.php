<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    protected NotificationController $notificationController;

    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    public function index(Request $request)
    {
        $schedules = Schedule::with('user')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate($request->input('per_page', 10));

        return response()->json($schedules);
    }

    public function show($id)
    {
        $schedule = Schedule::with('user')->find($id);

        if (! $schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        return response()->json($schedule);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scope' => 'sometimes|in:user,rt,all',
            'user_id' => 'sometimes|exists:users,id',
            'rt_id' => 'sometimes|exists:rts,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'alamat' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }
        $scope = $request->input('scope', 'user');

        if ($scope === 'user' && ! $request->has('user_id')) {
            return response()->json(['success' => false, 'message' => 'user_id is required when scope is user'], 400);
        }

        if ($scope === 'rt' && ! $request->has('rt_id')) {
            return response()->json(['success' => false, 'message' => 'rt_id is required when scope is rt'], 400);
        }

        // Determine target users based on scope
        if ($scope === 'user') {
            $targetUsers = collect([User::find($request->user_id)]);
        } elseif ($scope === 'rt') {
            $targetUsers = User::where('rt_id', $request->rt_id)->get();
        } else {
            // all
            $targetUsers = User::all();
        }

        // Create a single schedule record. For user-scoped schedule, set user_id.
        $scheduleData = [
            'title' => $request->title,
            'date' => $request->date,
            'time' => $request->time,
            'alamat' => $request->alamat,
            'notes' => $request->notes,
        ];

        if ($scope === 'user' && $targetUsers->first()) {
            $scheduleData['user_id'] = $targetUsers->first()->id;
        } else {
            $scheduleData['user_id'] = null;
        }

        $schedule = Schedule::create($scheduleData);

        $message = $this->buildScheduleMessage($schedule);

        // Create notification rows and send push for each target user
        foreach ($targetUsers as $user) {
            if (! $user) {
                continue;
            }

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Jadwal Baru',
                'message' => $message,
                'is_read' => false,
            ]);
        }

        // Send push notifications in batch (NotificationController handles individual sends)
        if ($targetUsers->isNotEmpty()) {
            $this->notificationController->sendToUsers($targetUsers->all(), 'Jadwal Baru', $message, [
                'schedule_id' => (string) $schedule->id,
                'type' => 'schedule',
                'scope' => $scope,
                'rt_id' => $request->rt_id ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Schedule created successfully',
            'schedule' => $schedule->load('user'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);

        if (! $schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'time' => 'sometimes',
            'alamat' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        $schedule->update([
            'user_id' => $request->user_id ?? $schedule->user_id,
            'title' => $request->title ?? $schedule->title,
            'date' => $request->date ?? $schedule->date,
            'time' => $request->time ?? $schedule->time,
            'alamat' => $request->has('alamat') ? $request->alamat : $schedule->alamat,
            'notes' => $request->has('notes') ? $request->notes : $schedule->notes,
        ]);

        $user = $schedule->user;
        if ($user) {
            $message = $this->buildScheduleMessage($schedule->fresh());

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Jadwal Diperbarui',
                'message' => $message,
                'is_read' => false,
            ]);

            $this->notificationController->sendToUser($user, 'Jadwal Diperbarui', $message, [
                'schedule_id' => (string) $schedule->id,
                'type' => 'schedule_update',
            ]);
        }

        return response()->json([
            'message' => 'Schedule updated successfully',
            'schedule' => $schedule->load('user'),
        ]);
    }

    public function destroy($id)
    {
        $schedule = Schedule::with('user')->find($id);

        if (! $schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $user = $schedule->user;
        $schedule->delete();

        if ($user) {
            $message = 'Jadwal Anda telah dihapus.';

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Jadwal Dihapus',
                'message' => $message,
                'is_read' => false,
            ]);

            $this->notificationController->sendToUser($user, 'Jadwal Dihapus', $message, [
                'schedule_id' => (string) $id,
                'type' => 'schedule_delete',
            ]);
        }

        return response()->json(['message' => 'Schedule deleted successfully']);
    }

    private function buildScheduleMessage(Schedule $schedule): string
    {
        $parts = [
            'Jadwal: ' . $schedule->title,
            'Tanggal: ' . $schedule->date,
            'Jam: ' . $schedule->time,
        ];

        if ($schedule->alamat) {
            $parts[] = 'Alamat: ' . $schedule->alamat;
        }

        if ($schedule->notes) {
            $parts[] = 'Catatan: ' . $schedule->notes;
        }

        return implode("\n", $parts);
    }
}
