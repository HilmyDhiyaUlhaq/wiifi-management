<?php

namespace App\Jobs;

use App\Repositories\Users\UserWiFiAccountRepository;
use App\Services\Mikrotiks\ConnectioService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncLeasesDhcpJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private $userWifiAccountId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $userWiFiAccountRepository = app(UserWiFiAccountRepository::class);
        $connectioService = app(ConnectioService::class);

        try {
            $userWiFiAccount = $userWiFiAccountRepository->getUserWiFIAccountById($this->userWifiAccountId);
            if ($userWiFiAccount && $userWiFiAccount->status == 'NOT-SYNC') {
                $connectioService->setLeasesDhcp($userWiFiAccount->id);
            }
        } catch (\Exception $e) {
            \Log::error('Error in SyncLeasesDhcpJob: ' . $e->getMessage());
        }
    }
}
