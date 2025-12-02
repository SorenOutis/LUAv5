<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoursesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch enrolled courses with enrollment data (only approved)
        $enrolledCourses = Course::query()
            ->whereHas('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('approval_status', 'approved');
            })
            ->with(['enrollments' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('approval_status', 'approved');
            }])
            ->get()
            ->map(fn ($course) => $this->formatCourseData($course, $user))
            ->values();

        // Fetch pending courses (approval_status = 'pending')
        $pendingCourses = Course::query()
            ->whereHas('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('approval_status', 'pending');
            })
            ->with(['enrollments' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('approval_status', 'pending');
            }])
            ->get()
            ->map(fn ($course) => $this->formatCourseData($course, $user))
            ->values();

        // Fetch completed courses (only approved)
        $completedCourses = Course::query()
            ->whereHas('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('approval_status', 'approved')
                    ->whereNotNull('completed_at');
            })
            ->with(['enrollments' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('approval_status', 'approved');
            }])
            ->get()
            ->map(fn ($course) => $this->formatCourseData($course, $user))
            ->values();

        // Fetch available courses (not enrolled)
        $availableCourses = Course::query()
            ->whereDoesntHave('enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('is_active', true)
            ->get()
            ->map(fn ($course) => $this->formatCourseData($course, $user))
            ->values();

        // Calculate stats
        $stats = [
            'totalEnrolled' => $enrolledCourses->count(),
            'totalCompleted' => $completedCourses->count(),
            'averageProgress' => $enrolledCourses->isNotEmpty()
                ? (int) $enrolledCourses->avg(fn ($course) => $course['progress'])
                : 0,
            'totalXPEarned' => $enrolledCourses->sum(fn ($course) => $course['xpEarned']),
        ];

        return Inertia::render('Courses', [
            'enrolledCourses' => $enrolledCourses,
            'pendingCourses' => $pendingCourses,
            'completedCourses' => $completedCourses,
            'availableCourses' => $availableCourses,
            'stats' => $stats,
        ]);
    }

    public function enroll(Course $course)
    {
        $user = Auth::user();

        // Check if already enrolled
        $existingEnrollment = CourseEnrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('message', 'Already enrolled in this course');
        }

        // Create new enrollment with pending approval
        CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'approval_status' => 'pending',
            'progress_percentage' => 0,
            'completed_lessons_count' => 0,
            'xp_earned' => 0,
        ]);

        return redirect()->back()->with('message', 'Enrollment request submitted. Please wait for teacher approval.');
    }

    private function formatCourseData(Course $course, $user)
    {
        $enrollment = $course->enrollments->first();

        // Map lowercase enum to title case
        $difficultyMap = [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Advanced',
        ];

        return [
            'id' => $course->id,
            'name' => $course->title,
            'description' => $course->description,
            'instructor' => 'Instructor', // TODO: Add instructor field to courses table or relationship
            'progress' => $enrollment ? $enrollment->progress_percentage : 0,
            'completedLessons' => $enrollment ? $enrollment->completed_lessons_count : 0,
            'totalLessons' => $course->total_lessons,
            'xpEarned' => $enrollment ? $enrollment->xp_earned : 0,
            'nextDeadline' => $enrollment && $enrollment->completed_at
                ? 'Completed'
                : '-',
            'category' => 'General', // TODO: Add category field to courses table
            'difficulty' => $difficultyMap[$course->difficulty_level] ?? 'Beginner',
            'thumbnail' => $course->thumbnail_url,
        ];
    }
}
