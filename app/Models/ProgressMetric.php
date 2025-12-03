<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressMetric extends Model
{
    protected $fillable = [
        'name',
        'description',
        'current',
        'target',
        'unit',
        'icon',
        'category',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActive()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();
    }
}
