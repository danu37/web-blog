<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait HandlesEditorialAuthorization
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isEditor();
    }

    public function view(User $user, mixed $model): bool
    {
        return $user->isEditor();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, mixed $model): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, mixed $model): bool
    {
        return $user->isEditor();
    }

    public function restore(User $user, mixed $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, mixed $model): bool
    {
        return false;
    }
}
