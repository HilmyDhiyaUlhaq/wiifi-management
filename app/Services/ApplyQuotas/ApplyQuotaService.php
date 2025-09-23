<?php
namespace App\Services\ApplyQuotas;

use App\Repositories\Transactions\TransactionUserPackageRepository;
use App\Repositories\Users\UserWiFiRepository;
use App\Services\Mikrotiks\ConnectioService;

class ApplyQuotaService
{

    public function __construct(
        private TransactionUserPackageRepository $transactionUserPackageRepository,
        private UserWiFiRepository $userWiFiRepository,
        private ConnectioService $connectioService

    ) {
        //
    }

    public function applyPromo($transactionUserPackageId)
    {
        $transactionUserPackage = $this->transactionUserPackageRepository->getTransactionUserPackageById($transactionUserPackageId);
        $userWifi = $this->userWiFiRepository->getUserWiFiByUserId($transactionUserPackage->user_id);

        if ($userWifi && $transactionUserPackage) {
            $quota = $userWifi->count_quota;
            $quota = $quota + $transactionUserPackage->quota;
            $this->userWiFiRepository->updateUserWiFiByUserId($transactionUserPackage->user_id, ['count_quota' => $quota, 'status' => 'active']);

            $userWifiAccounts = $userWifi->userWifiAccounts;
            if ($userWifiAccounts->isNotEmpty()) {
                foreach ($userWifiAccounts as $userWifiAccount) {
                    $this->connectioService->setStatusLeasesDhcp($userWifiAccount->id, true);
                }
            }
        }
    }

    public function dailyCalculateQuota($userId)
    {
        $userWifi = $this->userWiFiRepository->getUserWiFiByUserId($userId);
        if ($userWifi) {
            $status = 'active';
            $quota = $userWifi->count_quota;
            $userWifiAccounts = $userWifi->userWifiAccounts;
            $quota = $quota - 1;
            if ($quota < 1) {
                $status = 'inactive';
                $quota = 0;
                if ($userWifiAccounts->isNotEmpty()) {
                    foreach ($userWifiAccounts as $userWifiAccount) {
                        $this->connectioService->setStatusLeasesDhcp($userWifiAccount->id, false);
                    }
                }
            } else {
                foreach ($userWifiAccounts as $userWifiAccount) {
                    $this->connectioService->setStatusLeasesDhcp($userWifiAccount->id, true);
                }
            }
            $this->userWiFiRepository->updateUserWiFiByUserId($userId, ['count_quota' => $quota, 'status' => $status]);
        }
    }
}
