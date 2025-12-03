<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'due_date',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
