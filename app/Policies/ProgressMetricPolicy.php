<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProgressMetric;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgressMetricPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProgressMetric');
    }

    public function view(AuthUser $authUser, ProgressMetric $progressMetric): bool
    {
        return $authUser->can('View:ProgressMetric');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProgressMetric');
    }

    public function update(AuthUser $authUser, ProgressMetric $progressMetric): bool
    {
        return $authUser->can('Update:ProgressMetric');
    }

    public function delete(AuthUser $authUser, ProgressMetric $progressMetric): bool
    {
        return $authUser->can('Delete:ProgressMetric');
    }

    public function restore(AuthUser $authUser, ProgressMetric $progressMetric): bool
    {
        return $authUser->can('Restore:ProgressMetric');
    }

    public function forceDelete(AuthUser $authUser, ProgressMetric $progressMetric): bool
    {
        return $authUser->can('ForceDelete:ProgressMetric');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProgressMetric');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProgressMetric');
    }

    public function replicate(AuthUser $authUser, ProgressMetric $progressMetric): bool
    {
        return $authUser->can('Replicate:ProgressMetric');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProgressMetric');
    }

}