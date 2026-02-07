<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Game;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Game');
    }

    public function view(AuthUser $authUser, Game $game): bool
    {
        return $authUser->can('View:Game');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Game');
    }

    public function update(AuthUser $authUser, Game $game): bool
    {
        return $authUser->can('Update:Game');
    }

    public function delete(AuthUser $authUser, Game $game): bool
    {
        return $authUser->can('Delete:Game');
    }

    public function restore(AuthUser $authUser, Game $game): bool
    {
        return $authUser->can('Restore:Game');
    }

    public function forceDelete(AuthUser $authUser, Game $game): bool
    {
        return $authUser->can('ForceDelete:Game');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Game');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Game');
    }

    public function replicate(AuthUser $authUser, Game $game): bool
    {
        return $authUser->can('Replicate:Game');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Game');
    }

}