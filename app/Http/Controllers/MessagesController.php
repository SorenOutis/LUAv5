<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessagesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Inertia::render('Messages', [
            'messages' => [
                [
                    'id' => 1,
                    'from' => 'Admin Team',
                    'fromAvatar' => '👨‍💼',
                    'subject' => 'Welcome to the Platform',
                    'preview' => 'Welcome to our learning platform! We are excited to have you on board...',
                    'body' => 'Welcome to our learning platform! We are excited to have you on board. You can start exploring courses, tracking your progress, and earning achievements. If you have any questions, feel free to reach out to our support team.',
                    'timestamp' => '2 days ago',
                    'isRead' => true,
                    'type' => 'system',
                ],
                [
                    'id' => 2,
                    'from' => 'Course Instructor',
                    'fromAvatar' => '👩‍🏫',
                    'subject' => 'Feedback on Your Quiz',
                    'preview' => 'Great job on the latest quiz! You scored 95% and demonstrated...',
                    'body' => 'Great job on the latest quiz! You scored 95% and demonstrated excellent understanding of the material. Your attention to detail is impressive. Keep up the great work!',
                    'timestamp' => '1 day ago',
                    'isRead' => false,
                    'type' => 'message',
                ],
                [
                    'id' => 3,
                    'from' => 'System Notification',
                    'fromAvatar' => '🔔',
                    'subject' => 'New Course Available',
                    'preview' => 'A new course "Advanced React Patterns" has been added to your interests...',
                    'body' => 'A new course "Advanced React Patterns" has been added to your interests. Based on your learning history, we think you might enjoy this course. Click here to enroll.',
                    'timestamp' => '6 hours ago',
                    'isRead' => false,
                    'type' => 'notification',
                ],
                [
                    'id' => 4,
                    'from' => 'Learning Platform',
                    'fromAvatar' => '📢',
                    'subject' => 'January Learning Challenge Announcement',
                    'preview' => 'Join our January Learning Challenge for a chance to win exclusive...',
                    'body' => 'Join our January Learning Challenge for a chance to win exclusive badges and certificates! Complete 10 lessons this month to earn a special "January Champion" badge.',
                    'timestamp' => '3 hours ago',
                    'isRead' => false,
                    'type' => 'announcement',
                ],
                [
                    'id' => 5,
                    'from' => 'Fellow Learner',
                    'fromAvatar' => '👤',
                    'subject' => 'Study Group Invitation',
                    'preview' => 'Hi! I noticed you are taking the Python course. Want to join our...',
                    'body' => 'Hi! I noticed you are taking the Python course. Want to join our study group? We meet online every Wednesday at 7 PM. Looking forward to learning together!',
                    'timestamp' => '30 minutes ago',
                    'isRead' => false,
                    'type' => 'message',
                ],
                [
                    'id' => 6,
                    'from' => 'Achievement Unlocked',
                    'fromAvatar' => '⭐',
                    'subject' => 'You Unlocked "Speed Learner" Achievement',
                    'preview' => 'Congratulations! You completed 5 lessons in one day...',
                    'body' => 'Congratulations! You completed 5 lessons in one day and unlocked the "Speed Learner" achievement! This is a rare achievement earned by only 35% of our users.',
                    'timestamp' => 'Just now',
                    'isRead' => false,
                    'type' => 'notification',
                ],
            ],
            'stats' => [
                'unreadCount' => 4,
                'totalMessages' => 6,
                'lastMessageTime' => 'Just now',
            ],
        ]);
    }
}
