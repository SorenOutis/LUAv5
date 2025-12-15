<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementMarkReadController extends Controller
{
    public function markAnnouncementAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'announcement_id' => 'required|integer',
            'type' => 'required|in:announcement,community_post',
        ]);

        $userId = Auth::id();
        $announcementId = $request->input('announcement_id');
        $type = $request->input('type');

        // Check if notification already exists
        $notification = Notification::where('user_id', $userId)
            ->where('type', $type)
            ->where('data->id', $announcementId)
            ->first();

        if (!$notification) {
            // Create notification record
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $type === 'announcement' ? 'Announcement' : 'Community Post',
                'message' => '',
                'icon' => $type === 'announcement' ? '📢' : '💬',
                'data' => ['id' => $announcementId],
                'read_at' => now(),
            ]);
        } else {
            // Mark existing notification as read
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAnnouncementsAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.type' => 'required|in:announcement,community_post',
        ]);

        $userId = Auth::id();
        $items = $request->input('items');

        foreach ($items as $item) {
            // Check if notification already exists
            $notification = Notification::where('user_id', $userId)
                ->where('type', $item['type'])
                ->where('data->id', $item['id'])
                ->first();

            if (!$notification) {
                // Create notification record
                Notification::create([
                    'user_id' => $userId,
                    'type' => $item['type'],
                    'title' => $item['type'] === 'announcement' ? 'Announcement' : 'Community Post',
                    'message' => '',
                    'icon' => $item['type'] === 'announcement' ? '📢' : '💬',
                    'data' => ['id' => $item['id']],
                    'read_at' => now(),
                ]);
            } else {
                // Mark existing notification as read
                $notification->markAsRead();
            }
        }

        return response()->json(['success' => true]);
    }
}
