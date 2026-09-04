<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class GenerateNotifications extends Command
{
    protected $signature = 'notifications:generate';

    protected $description = 'Generate PharmaDesk notifications';

    public function handle(
        NotificationService $notificationService
    ): int {
        $count = $notificationService->generateAll();

        $this->info(
            "Generated {$count} notification(s)."
        );

        return self::SUCCESS;
    }
}