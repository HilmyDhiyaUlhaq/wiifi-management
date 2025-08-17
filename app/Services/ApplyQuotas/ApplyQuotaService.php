<?php
namespace App\Services\ApplyQuotas;

use App\Repositories\Transactions\TransactionUserPackageRepository;
use App\Repositories\Users\UserWiFiRepository;

class ApplyQuotaService
{

    public function __construct(
        private TransactionUserPackageRepository $transactionUserPackageRepository,
        private UserWiFiRepository $userWiFiRepository
    ) {
        //
    }

    public function applyPromo($transactionUserPackageId, $status = false)
    {
        $transactionUserPackage = $this->transactionUserPackageRepository->getTransactionUserPackageById($transactionUserPackageId);
        $userWifi = $this->userWiFiRepository->getUserWiFiByUserId($transactionUserPackage->user_id);
        if (!$status) {
            $status = $transactionUserPackage->status == 'request';
        }
        if ($userWifi && $transactionUserPackage && $status) {
            $quota = $userWifi->count_quota;
            $quota = $quota + $transactionUserPackage->quota;
            $this->userWiFiRepository->updateUserWiFiByUserId($transactionUserPackage->user_id, ['count_quota' => $quota, 'status' => 'active']);
        }
    }

    public function dailyCalculateQuota($userId)
    {
        $userWifi = $this->userWiFiRepository->getUserWiFiByUserId($userId);
        if ($userWifi) {
            $status = 'active';
            $quota = $userWifi->count_quota;
            $quota = $quota - 1;
            if ($quota < 1) {
                $status = 'inactive';
                $quota = 0;
            }
            $this->userWiFiRepository->updateUserWiFiByUserId($userId, ['count_quota' => $quota, 'status' => $status]);
        }
    }
}