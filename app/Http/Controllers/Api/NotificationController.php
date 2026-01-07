<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            auth()->user()
                ->notifications()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($notification) => [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'timestamp' => $notification->created_at->diffForHumans(),
                    'createdAt' => $notification->created_at->toIso8601String(),
                    'read' => $notification->isRead(),
                    'readAt' => $notification->read_at?->toIso8601String(),
                    'icon' => $notification->icon,
                    'data' => $notification->data,
                ])
        );
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
}
