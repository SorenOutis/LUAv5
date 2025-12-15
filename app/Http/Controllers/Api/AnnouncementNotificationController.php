<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\CommunityPost;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AnnouncementNotificationController extends Controller
{
    public function getLatest(): JsonResponse
    {
        $userId = Auth::id();

        // Get read announcement IDs from database
        $readAnnouncementIds = Notification::where('user_id', $userId)
            ->where('type', 'announcement')
            ->whereNotNull('read_at')
            ->pluck('data->id')
            ->toArray();

        // Get read community post IDs from database
        $readCommunityPostIds = Notification::where('user_id', $userId)
            ->where('type', 'community_post')
            ->whereNotNull('read_at')
            ->pluck('data->id')
            ->toArray();

        $announcements = Announcement::where('is_active', true)
            ->whereNotIn('id', $readAnnouncementIds)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($announcement) => [
                'id' => $announcement->id,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => $announcement->description ?? substr(strip_tags($announcement->content), 0, 100),
                'icon' => '📢',
                'timestamp' => $announcement->published_at?->format('M d, Y H:i') ?? $announcement->created_at->format('M d, Y H:i'),
                'read' => false,
            ]);

        $communityPosts = CommunityPost::where('is_approved', true)
            ->whereNotIn('id', $readCommunityPostIds)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($post) => [
                'id' => $post->id + 10000, // Offset ID to avoid collision
                'type' => 'community_post',
                'title' => 'New Community Post',
                'message' => $post->title . ': ' . substr(strip_tags($post->content), 0, 80),
                'icon' => '💬',
                'timestamp' => $post->created_at->format('M d, Y H:i'),
                'read' => false,
            ]);

        // Merge and sort by timestamp
        $combined = $announcements->concat($communityPosts)
            ->sortByDesc(fn($item) => strtotime($item['timestamp']))
            ->values()
            ->take(10);

        return response()->json($combined);
    }
}
