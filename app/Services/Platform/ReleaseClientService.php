<?php

declare(strict_types=1);

namespace App\Services\Platform;

use App\Enums\ClientLifecycle;
use App\Models\Client;
use Carbon\CarbonImmutable;

final class ReleaseClientService
{
    public function execute(Client $client): Client
    {
        $now = CarbonImmutable::now();

        $client->update([
            'lifecycle' => ClientLifecycle::Released,
            'released_at' => $now,
            'is_active' => false,
        ]);

        return $client->fresh();
    }
}
