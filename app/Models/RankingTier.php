<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingTier extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'color',
        'min_rank',
        'max_rank',
        'description',
        'sort_order',
    ];
}
