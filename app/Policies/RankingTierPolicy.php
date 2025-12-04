<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RankingTier;
use Illuminate\Auth\Access\HandlesAuthorization;

class RankingTierPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RankingTier');
    }

    public function view(AuthUser $authUser, RankingTier $rankingTier): bool
    {
        return $authUser->can('View:RankingTier');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RankingTier');
    }

    public function update(AuthUser $authUser, RankingTier $rankingTier): bool
    {
        return $authUser->can('Update:RankingTier');
    }

    public function delete(AuthUser $authUser, RankingTier $rankingTier): bool
    {
        return $authUser->can('Delete:RankingTier');
    }

    public function restore(AuthUser $authUser, RankingTier $rankingTier): bool
    {
        return $authUser->can('Restore:RankingTier');
    }

    public function forceDelete(AuthUser $authUser, RankingTier $rankingTier): bool
    {
        return $authUser->can('ForceDelete:RankingTier');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RankingTier');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RankingTier');
    }

    public function replicate(AuthUser $authUser, RankingTier $rankingTier): bool
    {
        return $authUser->can('Replicate:RankingTier');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RankingTier');
    }

}