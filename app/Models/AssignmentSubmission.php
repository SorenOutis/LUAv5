<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    protected $fillable = [
         'user_id',
         'assignment_id',
         'file_path',
         'notes',
         'status',
         'grade',
         'feedback',
         'xp',
     ];

    protected $casts = [
         'grade' => 'decimal:2',
         'xp' => 'integer',
         'created_at' => 'datetime',
         'updated_at' => 'datetime',
     ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}
