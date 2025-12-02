<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'approval_status',
        'progress_percentage',
        'completed_lessons_count',
        'xp_earned',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // public function user(): BelongsTo
    // {
    //     return $this->hasMany(User::class);
    // }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
