<?php

namespace App\Http\Controllers;

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Contract\Messaging;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Send notification to a specific user
     *
     * Returns an array with keys: success (bool), message_id|null, error|null
     */
    public function sendToUser(User $user, string $title, string $message, array $data = []): array
    {
        if (empty($user->fcm_token)) {
            Log::warning('User has no FCM token', ['user_id' => $user->id ?? null]);

            return [
                'success' => false,
                'message_id' => null,
                'error' => 'no_fcm_token',
            ];
        }

        try {
            $cloudMessage = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification(
                    Notification::create($title, $message)
                );

            // Add custom data if provided
            if (!empty($data)) {
                $cloudMessage = $cloudMessage->withData($data);
            }

            Log::debug('Sending Firebase cloud message', [
                'user_id' => $user->id ?? null,
                'fcm_token' => $user->fcm_token,
                'title' => $title,
                'data' => $data,
            ]);

            $messageId = $this->messaging->send($cloudMessage);

            $messageIdStr = is_scalar($messageId) ? (string)$messageId : json_encode($messageId);

            Log::info('Firebase notification sent', [
                'user_id' => $user->id ?? null,
                'title' => $title,
                'message_id' => $messageIdStr,
                'data' => $data,
            ]);

            return [
                'success' => true,
                'message_id' => $messageIdStr,
                'error' => null,
            ];
        } catch (\Throwable $e) {
            Log::error('Firebase notification error', [
                'user_id' => $user->id ?? null,
                'title' => $title,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message_id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send to multiple users
     */
    public function sendToUsers(array $users, string $title, string $message, array $data = []): array
    {
        $results = [];

        foreach ($users as $user) {
            $results[] = $this->sendToUser($user, $title, $message, $data);
        }

        return $results;
    }

    /**
     * Test endpoint - send notification
     */
    public function send()
    {
        try {
            $user = User::find(2);

            if (!$user) {
                Log::warning('No user found for test notification');
                return response()->json(['error' => 'No users found'], 404);
            }

            $title = 'Test Notification';
            $message = 'This is a test notification from Laravel.';
            $data = ['key' => 'value']; // Optional custom data

            Log::info('Attempting to send test notification', ['user_id' => $user->id]);

            $result = $this->sendToUser($user, $title, $message, $data);

            if (!empty($result['success'])) {
                return response()->json(['message' => 'Notification sent successfully', 'message_id' => $result['message_id']]);
            }

            return response()->json(['error' => 'Failed to send notification', 'details' => $result['error']], 500);
        } catch (\Throwable $e) {
            Log::error('Error sending test notification', ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while sending notification'], 500);
        }
    }
}