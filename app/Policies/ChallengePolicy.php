<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Challenge;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChallengePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Challenge');
    }

    public function view(AuthUser $authUser, Challenge $challenge): bool
    {
        return $authUser->can('View:Challenge');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Challenge');
    }

    public function update(AuthUser $authUser, Challenge $challenge): bool
    {
        return $authUser->can('Update:Challenge');
    }

    public function delete(AuthUser $authUser, Challenge $challenge): bool
    {
        return $authUser->can('Delete:Challenge');
    }

    public function restore(AuthUser $authUser, Challenge $challenge): bool
    {
        return $authUser->can('Restore:Challenge');
    }

    public function forceDelete(AuthUser $authUser, Challenge $challenge): bool
    {
        return $authUser->can('ForceDelete:Challenge');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Challenge');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Challenge');
    }

    public function replicate(AuthUser $authUser, Challenge $challenge): bool
    {
        return $authUser->can('Replicate:Challenge');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Challenge');
    }

}