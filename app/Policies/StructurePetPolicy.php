<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\StructurePet;
use App\Models\User;

final class StructurePetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('client.pets.manage');
    }

    public function view(User $user, StructurePet $pet): bool
    {
        return $user->can('client.pets.manage')
            && $user->canAccessClient((int) $pet->client_id);
    }

    public function create(User $user): bool
    {
        return $user->can('client.pets.manage');
    }

    public function update(User $user, StructurePet $pet): bool
    {
        return $user->can('client.pets.manage')
            && $user->canAccessClient((int) $pet->client_id);
    }

    public function delete(User $user, StructurePet $pet): bool
    {
        return $this->update($user, $pet);
    }
}
