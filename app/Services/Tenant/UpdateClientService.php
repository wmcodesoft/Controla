<?php

declare(strict_types=1);

namespace App\Services\Tenant;

use App\Models\Client;
use Illuminate\Support\Str;

final class UpdateClientService
{
    /** @param array<string, mixed> $attributes */
    public function execute(Client $client, array $attributes): Client
    {
        unset($attributes['plan_tier'], $attributes['max_structures']);

        if (isset($attributes['slug'])) {
            $attributes['slug'] = Str::slug((string) $attributes['slug']);
        }

        if (isset($attributes['login_suffix'])) {
            $attributes['login_suffix'] = Str::lower((string) $attributes['login_suffix']);
        }

        $client->update($attributes);

        return $client->fresh();
    }
}
