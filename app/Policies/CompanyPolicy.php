<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Laravel\Nova\Http\Requests\NovaRequest;

class CompanyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (!$request->user()->isAdmin()) {
            return $query->where('id', $request->user()->id);
        }
        return $query;
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company)
    {
        // Admin can view any company, regular users can only view their own company's data
        return $user->isAdmin() || $company->id == $user->company_id;
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
    public function update(User $user, Company $company): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }
}
