<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Platform\ProcessSubscriptionLifecycleService;
use Illuminate\Console\Command;

final class ProcessSubscriptionLifecycle extends Command
{
    protected $signature = 'subscriptions:process-lifecycle';

    protected $description = 'Aplica gracia, suspensión y archivo por recuperación a licencias vencidas';

    public function handle(ProcessSubscriptionLifecycleService $service): int
    {
        $count = $service->execute();

        $this->info("Empresas procesadas: {$count}");

        return self::SUCCESS;
    }
}
