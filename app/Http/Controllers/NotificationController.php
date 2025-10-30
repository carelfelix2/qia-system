<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'general',
                    'message' => $notification->data['message'] ?? '',
                    'data' => $notification->data,
                    'action_url' => $notification->data['action_url'] ?? null,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            });

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $notification = $user->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
