<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AssignmentSubmission;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentSubmissionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AssignmentSubmission');
    }

    public function view(AuthUser $authUser, AssignmentSubmission $assignmentSubmission): bool
    {
        return $authUser->can('View:AssignmentSubmission');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AssignmentSubmission');
    }

    public function update(AuthUser $authUser, AssignmentSubmission $assignmentSubmission): bool
    {
        return $authUser->can('Update:AssignmentSubmission');
    }

    public function delete(AuthUser $authUser, AssignmentSubmission $assignmentSubmission): bool
    {
        return $authUser->can('Delete:AssignmentSubmission');
    }

    public function restore(AuthUser $authUser, AssignmentSubmission $assignmentSubmission): bool
    {
        return $authUser->can('Restore:AssignmentSubmission');
    }

    public function forceDelete(AuthUser $authUser, AssignmentSubmission $assignmentSubmission): bool
    {
        return $authUser->can('ForceDelete:AssignmentSubmission');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AssignmentSubmission');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AssignmentSubmission');
    }

    public function replicate(AuthUser $authUser, AssignmentSubmission $assignmentSubmission): bool
    {
        return $authUser->can('Replicate:AssignmentSubmission');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AssignmentSubmission');
    }

}