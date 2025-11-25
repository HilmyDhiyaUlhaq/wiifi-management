<?php

namespace App\Console\Commands;

use App\Models\Users\UserWiFiAccount;
use App\Services\Mikrotiks\ConnectioService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncLeaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:lease';

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

        $connectionService = app(ConnectioService::class);
        UserWiFiAccount::where('status', 'NOT-SYNC')->chunk(100, function ($userWiFiAccounts) use ($connectionService) {
            $userWiFiAccounts->each(function ($userWiFiAccount) use ($connectionService) {
                try {
                    $connectionService->setLeasesDhcp($userWiFiAccount->id);
                } catch (Exception $e) {
                    Log::error('Error syncing lease for user WiFi account ' . $userWiFiAccount->id . ': ' . $e->getMessage());
                }
            });
        });
    }
}
