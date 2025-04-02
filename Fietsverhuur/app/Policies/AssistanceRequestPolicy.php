<?php

namespace App\Policies;

use App\Models\AssistanceRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssistanceRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, AssistanceRequest $assistanceRequest)
    {
        return $user->isAdmin() || $assistanceRequest->location->company_id == $user->company_id;
    }

    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->company_id !== null;
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AssistanceRequest $assistanceRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AssistanceRequest $assistanceRequest): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AssistanceRequest $assistanceRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AssistanceRequest $assistanceRequest): bool
    {
        return false;
    }
}
