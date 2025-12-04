<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Streak;
use Illuminate\Auth\Access\HandlesAuthorization;

class StreakPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Streak');
    }

    public function view(AuthUser $authUser, Streak $streak): bool
    {
        return $authUser->can('View:Streak');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Streak');
    }

    public function update(AuthUser $authUser, Streak $streak): bool
    {
        return $authUser->can('Update:Streak');
    }

    public function delete(AuthUser $authUser, Streak $streak): bool
    {
        return $authUser->can('Delete:Streak');
    }

    public function restore(AuthUser $authUser, Streak $streak): bool
    {
        return $authUser->can('Restore:Streak');
    }

    public function forceDelete(AuthUser $authUser, Streak $streak): bool
    {
        return $authUser->can('ForceDelete:Streak');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Streak');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Streak');
    }

    public function replicate(AuthUser $authUser, Streak $streak): bool
    {
        return $authUser->can('Replicate:Streak');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Streak');
    }

}