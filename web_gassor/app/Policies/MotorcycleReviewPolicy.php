<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\MotorcycleReview;
use App\Models\User;

class MotorcycleReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|Admin $user): bool
    {
        // Admin model (Filament admin panel)
        if ($user instanceof Admin) {
            return true;
        }

        // User model (regular users)
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|Admin $user, MotorcycleReview $motorcycleReview): bool
    {
        // Admin model (Filament admin panel)
        if ($user instanceof Admin) {
            return true;
        }

        // User model (regular users)
        return $user->role === 'admin' || $user->id === $motorcycleReview->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User|Admin $user): bool
    {
        // Admin model (Filament admin panel)
        if ($user instanceof Admin) {
            return true;
        }

        // User model (regular users)
        return $user->role === 'admin' || $user->role === 'penyewa';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|Admin $user, MotorcycleReview $motorcycleReview): bool
    {
        // Admin model (Filament admin panel)
        if ($user instanceof Admin) {
            return true;
        }

        // User model (regular users)
        return $user->role === 'admin' || $user->id === $motorcycleReview->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User|Admin $user, MotorcycleReview $motorcycleReview): bool
    {
        // Admin model (Filament admin panel)
        if ($user instanceof Admin) {
            return true;
        }

        // User model (regular users)
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User|Admin $user, MotorcycleReview $motorcycleReview): bool
    {
        // Admin model (Filament admin panel)
        if ($user instanceof Admin) {
            return true;
        }

        // User model (regular users)
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User|Admin $user, MotorcycleReview $motorcycleReview): bool
    {
        // Admin model (Filament admin panel)
        if ($user instanceof Admin) {
            return true;
        }

        // User model (regular users)
        return $user->role === 'admin';
    }
}
