<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $user = app('auth')->user();

        if ($request->boolean('unread_only')) {
            $notifications = $user->unreadNotifications()->paginate()->withQueryString();
        } else {
            $notifications = $user->notifications()->paginate()->withQueryString();
        }

        return Inertia::render('Notification/Index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, string $id): array
    {
        $user = app('auth')->user();
        $now = now();
        $notification = $user->notifications()->find($id);
        $notification->update(['read_at' => $now]);

        if ($request->boolean('reload')) {
            $result =  $this->getTopTenNotifications();

            if ($result['status'] == 'success') {
                return [
                    'status' => 'success',
                    'message' => trans('Marked as read'),
                    'read_at' => $now,
                    'result' => $result['result'],
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => trans('Failed to Mark.'),
                ];
            }
        }

        return [
            'status' => 'success',
            'message' => trans('Marked as read'),
            'read_at' => $now,
        ];
    }

    /**
     * Mark all notifications as read.(清空所有消息，只是标记为已读)
     */
    public function empty(): array
    {
        $user = app('auth')->user();

        $user->unreadNotifications->markAsRead();

        return [
            'status' => 'success',
            'message' => trans('Successfully cleared.'),
        ];
    }

    /**
     * Get top ten notifications.
     */
    public function getTopTenNotifications(): array
    {
        $user = app('auth')->user();

        $topTenNotifications = $user->unreadNotifications()->take(10)->get();
        $notificationCount = $user->unreadNotifications()->count();

        return [
            'status' => 'success',
            'message' => trans('Successfully requested.'),
            'result' => [
                'topTenNotifications' => $topTenNotifications,
                'notificationCount' => $notificationCount,
            ],
        ];
    }
}
