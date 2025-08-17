<?php

namespace App\Console\Commands;

use App\Models\Users\User;
use App\Services\ApplyQuotas\ApplyQuotaService;
use Illuminate\Console\Command;

class DailyCounterQuotaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-counter-quota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $applyQuotaService = app(ApplyQuotaService::class);
        User::chunk(100, function ($users) use ($applyQuotaService) {
            $users->each(function ($user) use ($applyQuotaService) {
                $applyQuotaService->dailyCalculateQuota($user->id);
            });
        });
    }
}
