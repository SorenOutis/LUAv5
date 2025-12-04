<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Quest;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Quest');
    }

    public function view(AuthUser $authUser, Quest $quest): bool
    {
        return $authUser->can('View:Quest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Quest');
    }

    public function update(AuthUser $authUser, Quest $quest): bool
    {
        return $authUser->can('Update:Quest');
    }

    public function delete(AuthUser $authUser, Quest $quest): bool
    {
        return $authUser->can('Delete:Quest');
    }

    public function restore(AuthUser $authUser, Quest $quest): bool
    {
        return $authUser->can('Restore:Quest');
    }

    public function forceDelete(AuthUser $authUser, Quest $quest): bool
    {
        return $authUser->can('ForceDelete:Quest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Quest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Quest');
    }

    public function replicate(AuthUser $authUser, Quest $quest): bool
    {
        return $authUser->can('Replicate:Quest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Quest');
    }

}