<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    public function unread()
    {
        $user = auth()->user();

        return response()->json([
            'count' => $this->notificationService
                ->unreadCount($user),

            'notifications' => $this->notificationService
                ->unread($user, 10)
                ->map(function ($notification) {
                    return $this->formatNotification(
                        $notification
                    );
                })
                ->values(),
        ]);
    }

    public function markAsRead(string $id)
    {
        abort_unless(
            $this->notificationService->markAsRead(
                auth()->user(),
                $id
            ),
            404
        );

        return response()->json([
            'success' => true,
        ]);
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(
            auth()->user()
        );

        return response()->json([
            'success' => true,
        ]);
    }

    protected function formatNotification($notification): array
    {
        $data = $notification->data ?? [];

        return [
            'id' => $notification->id,
            'type' => $data['type'] ?? 'system',
            'title' => $data['title'] ?? 'Notification',
            'message' => $data['message'] ?? '',
            'url' => $data['url'] ?? route('notifications.index'),
            'read_at' => $notification->read_at,
            'time' => $notification->created_at?->diffForHumans(),
        ];
    }
}